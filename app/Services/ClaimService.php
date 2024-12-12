<?php
namespace App\Services;

use Carbon\Carbon;

class ClaimService {

    private $number_of_batches_made_for_insurer = [];
    private $number_of_claims_batched_for_insurer = [];
    private $all_batched_claims = [];

    public function __construct() {
        $this->number_of_batches_made_for_insurer = []; // T
        $this->number_of_claims_batched_for_insurer = []; // insurer_id => numberOfClaims
        $this->all_batched_claims = []; // [$claims]
    }

    public function calculateTotalAmount(array $claimItems): float {
        return array_sum(array_column($claimItems, 'sub_total'));
    }

    public function transformClaimItems(array $claimItems): array {
        return array_map(function ($item) {
            return [
                'name' => $item['name'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'sub_total' => $item['unit_price'] * $item['quantity']
            ];
        }, $claimItems);
    }

    public function prepareClaimData(array $data): array {
        // Claim's items
        $data['claim_items'] = $this->transformClaimItems($data['claim_items']);

        // Total amount (or monetary value) of claim
        $data['total_amount']= $this->calculateTotalAmount($data['claim_items']);

        return $data;
    }

    private function insurrerBatchesIsFilled($insurer_id, $insurer_maximum_batch_size) {
        if(isset($this->number_of_batches_made_for_insurer[$insurer_id])) {
            return $this->number_of_batches_made_for_insurer[$insurer_id] >= $insurer_maximum_batch_size;
        }

        return false;

        
    }

    private function getProcessingDateOfClaim($insurer_id, $insurer_daily_processing_capacity, $date = null) {

        if(isset($this->number_of_claims_batched_for_insurer[$insurer_id][$date])) {

            // If daily capacity is filled
            if (
                $this->number_of_claims_batched_for_insurer[$insurer_id][$date] >= $insurer_daily_processing_capacity
            ) {
                // Move claim to the next day's batch
                $next_day = Carbon::parse($date);
                $claimDate = $next_day->addDay()->toDateString();
            }else {
                $claimDate = $date;
            }
            
        } else {
            $claimDate = count($this->all_batched_claims) == 0
                ? now()->format('Y-m-d')
                : end($this->all_batched_claims)->get('date');
        }

        return $claimDate;
    }

    private function getClaimHavingSameSortWeight($claim, $insurer_batched_claims) {

        $claim_having_same_sort_weight = null;

        $index = null;

        foreach ($insurer_batched_claims as $key => $batched_claim) {
            if (
                $batched_claim->get('total_amount') === $claim->get('total_amount') &&
                $batched_claim->get('priority_level') === $claim->get('priority_level') &&
                $batched_claim->get('insurer_specialty_percent_cost') === $claim->get('insurer_specialty_percent_cost')
            ) {
                $claim_having_same_sort_weight = $batched_claim;
                $index = $key;
                break;
            }
        }

        if($claim_having_same_sort_weight) {
            $claim_having_same_sort_weight->arrayIndex = $index;
        }

        return $claim_having_same_sort_weight;
    }

    public function calculatePercentCostOnMonetaryValueOnNthDay(int $nthDay): float {
        return 20 + ( (30 * ($nthDay - 1)) / 29 );
    }

    public function calculateSpecialtyCost($claim): float {
        return $claim->insurer->specialty_costs
        ->firstWhere('specialty_id', $claim->specialty_id)
        ?->percent_cost ?? 0.0;
    }

    public function calculatePriorityCost($claim): float {
        return $claim->insurer->priority_costs
        ->firstWhere('priority_level', $claim->priority_level)
        ?->percent_cost ?? 0.0;
    }

    public function calculateProcessingCost($claim, $claimDate): float {
        $day = (int) Carbon::parse($claimDate)->format('d');
        $costOnMoneytaryValueOnNthDay = $this->calculatePercentCostOnMonetaryValueOnNthDay($day);
        $costOnSpecialty = $this->calculateSpecialtyCost($claim);
        $costOnPriority = $this->calculatePriorityCost($claim);

        return $costOnMoneytaryValueOnNthDay + $costOnSpecialty + $costOnPriority;

        
    }

    private function prepareBatchedClaimForStorage($claim, $claimDate) {
        // $claimArray = $claim->toArray();
        return collect([
            'indentifier' => "{$claim->provider->name} $claimDate",
            'claim_id' => $claim->id,
            'date' => $claimDate,
            'processing_cost' => $this->calculateProcessingCost($claim, $claimDate),
            'insurer_id' => $claim->insurer_id,
            'total_amount' => $claim->total_amount
        ]);
    }

    private function incrementNumberOfClaimsBatchedForInsurerOnDate($insurer_id, $claimDate) {
        if(isset($this->number_of_claims_batched_for_insurer[$insurer_id][$claimDate])) {
            $this->number_of_claims_batched_for_insurer[$insurer_id][$claimDate] += 1;
        } else {
            $this->number_of_claims_batched_for_insurer[$insurer_id][$claimDate] = 1;
        }
    }

    private function incrementNumberOfBatchedForInsurer($insurer_id) {
        if(isset($this->number_of_batches_made_for_insurer[$insurer_id])) {
            $this->number_of_batches_made_for_insurer[$insurer_id] += 1;
        } else {
            $this->number_of_batches_made_for_insurer[$insurer_id] = 1;
        }
    }

    public function batchClaims($claims) {
        foreach ($claims as $claim) {
            $insurer_id = $claim->insurer_id;
            $insurer_daily_processing_capacity = $claim->insurer->daily_processing_capacity;

            // All the claims that belong to an insurer
            $insurer_batched_claims = array_filter($this->all_batched_claims, function ($batched_claim) use ($insurer_id) {
                return $batched_claim->get('insurer_id') == $insurer_id;
            }) ?? [];

            // This date determines the batch that a claim belongs to
            $claimDate = $this->getProcessingDateOfClaim($insurer_id, $insurer_daily_processing_capacity);

            // Skip batching if maximum batch size is reached for the insurer
            if ($this->insurrerBatchesIsFilled($insurer_id, $claim->insurer->maximum_batch_size)) {
                continue;
            }

            $inserted = false;

            $claim_having_same_sort_weight = $this->getClaimHavingSameSortWeight($claim, $insurer_batched_claims);

            // If the claim has the same sort weight but differ in submission date
            // TODO: use the preferred date set by insurer instead of submission date
            if (!empty($claim_having_same_sort_weight) &&
                $claim_having_same_sort_weight?->get('submission_date') > $claim->submission_date
            ) {
                $date = $claim_having_same_sort_weight?->date;

                $claimDate = $this->getProcessingDateOfClaim($insurer_id, $insurer_daily_processing_capacity, $date);

                // If the claim can be processed on date of $claim_having_same_sort_weight,
                // then we move claim to the front of it.
                if($claimDate == $date) {
                    $batched = $this->prepareBatchedClaimForStorage($claim, $claimDate);
                    array_splice($this->all_batched_claims, $claim_having_same_sort_weight->arrayIndex, 0, $batched);
                    $inserted = true;
                    $this->incrementNumberOfClaimsBatchedForInsurerOnDate($insurer_id, $claimDate);
                    continue;
                }
                
            }

            // If claim cannot be moved to the front, we push it to the end of all_batched_claims
            if (!$inserted) {
                $batched = $batched = $this->prepareBatchedClaimForStorage($claim, $claimDate);
                $last_claim = end($this->all_batched_claims);

                array_push($this->all_batched_claims, $batched);
                
                $this->incrementNumberOfClaimsBatchedForInsurerOnDate($insurer_id, $claimDate);

                if(empty($last_claim) || $last_claim->get('date') < $claimDate) {
                    $this->incrementNumberOfBatchedForInsurer($insurer_id);
                }
            }
        }
    }

}