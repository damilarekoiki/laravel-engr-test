<?php
namespace App\Repositories;

use App\Contracts\BatchedClaimInterface;
use App\Contracts\ClaimInterface;
use App\Models\Claim;
use App\Services\ClaimService;

class ClaimRepository extends AbstractRepository implements ClaimInterface {

    private ClaimService $claimService;
    private BatchedClaimInterface $batchedClaimRepository;

    public function __construct(Claim $claim, ClaimService $claimService, BatchedClaimInterface $batchedClaimRepository) {

        parent::__construct($claim);

        $this->model = $claim;
        $this->claimService = $claimService;
        $this->batchedClaimRepository = $batchedClaimRepository;

    }

    public function store(array $data): Claim {
        $claim = $this->model->create($data);
        return $claim;
    }

    public function storeClaimItems(Claim $claim, array $claimItems): void {
        $claim->claim_items()->createMany($claimItems);
    }

    public function storeClaimWithItems(array $data): void {
        // Store the claim
        $claim = $this->store($data);

        // Store the claim's items
        $this->storeClaimItems($claim, $data['claim_items']);
    }


    public function batchClaims(): array {
        $claims = $this->model
        ->with(['insurer' => function ($query) {
            $query->with(['specialty_costs', 'priority_costs']);
        }])
        ->whereNull('processed_at')
        ->join('insurers', 'insurers.id', '=', 'claims.insurer_id')
        ->join('insurer_specialty_costs', 'insurer_specialty_costs.insurer_id', '=', 'insurers.id')
        ->orderBy('total_amount', 'DESC')
        ->orderBy('priority_level', 'DESC')
        ->orderBy('insurer_specialty_costs.percent_cost', 'DESC') // Secondary sorting by orders
        ->groupBy('claims.id')
        ->get(); // Limit to the total processing capacity

        $batchedClaims = $this->claimService->batchClaims($claims);

        $this->batchedClaimRepository->truncate();
        $this->batchedClaimRepository->storeMany($batchedClaims);

        return $batchedClaims;

        // TODO: Add these comments to the Readme

        // Loop through claims

        // If numberOfBatches for the insurrer in $number_of_batches_made_for_insurer
        // equals $claim->insurer_maximum_batch_size, we skip the batching for the claim

        // If the numberOfClaims in $claims_batched_for_insurer
        // equals $claim->daily_processing_capacity, we put the claim in the next day batch

        // Check the $claims_batched_for_provider, if there is a claim with
        // same insurer_id, total_amount, priority_level, insurer_specialty_percent_cost and
        // submission_date > $claim->submission_date, we put claim in front of it, else put claim
        // at the end of $claims_batched_for_provider

        // when we add place a claim in $claims_batched_for_provider, we chack the last claim with
        // the same indentifier meets the daily_processing_capacity and maximum_batch_size criteria
        // If it fails numberOfBatches, we skip.
        // If it fails daily_processing_capacity, we put the claim in the next day batch


    }
}