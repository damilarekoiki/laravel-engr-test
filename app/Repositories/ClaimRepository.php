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
        ->with(['provider', 'insurer' => function ($query) {
            $query->with(['specialty_costs', 'priority_costs']);
        }])
        ->whereNull('processed_at')
        ->join('insurers', 'insurers.id', '=', 'claims.insurer_id')
        ->join('insurer_specialty_costs', 'insurer_specialty_costs.insurer_id', '=', 'insurers.id')
        ->orderBy('total_amount', 'DESC')  // Most important constraint
        ->orderBy('priority_level', 'DESC') // Priority level comes next
        ->orderBy('insurer_specialty_costs.percent_cost', 'DESC') // Then specialty cost
        ->groupBy('claims.id')
        ->get();

        $batchedClaims = $this->claimService->batchClaims($claims);

        $this->batchedClaimRepository->truncate();
        $this->batchedClaimRepository->storeMany($batchedClaims);

        return $batchedClaims;


    }
}