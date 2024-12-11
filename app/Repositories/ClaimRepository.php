<?php
namespace App\Repositories;

use App\Contracts\ClaimInterface;
use App\Models\Claim;
use App\Services\ClaimService;

class ClaimRepository extends AbstractRepository implements ClaimInterface {

    private ClaimService $claimService;
    private Claim $storedClaim;

    public function __construct(Claim $claim, ClaimService $claimService) {

        parent::__construct($claim);

        $this->model = $claim;
        $this->claimService = $claimService;

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
}