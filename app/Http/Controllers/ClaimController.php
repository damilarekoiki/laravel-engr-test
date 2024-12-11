<?php

namespace App\Http\Controllers;

use App\Contracts\ClaimInterface;
use App\Http\Requests\ClaimRequest;
use App\Services\ClaimService;

class ClaimController extends Controller
{
    //
    private ClaimInterface $claimRepository;
    private ClaimService $claimService;

    public function __construct(ClaimInterface $claimRepository, ClaimService $claimService) {
        $this->claimRepository = $claimRepository;
        $this->claimService = $claimService;
    }

    public function store(ClaimRequest $request) {
        $claimData = $request->validated();

        // Claim's items
        $claimData['claim_items'] = $this->claimService->transformClaimItems($claimData['claim_items']);

        // Total amount (or monetary value) of claim
        $claimData['total_amount']= $this->claimService->calculateTotalAmount($claimData['claim_items']);
        
        $this->claimRepository->storeClaimWithItems($claimData);
    }
}
