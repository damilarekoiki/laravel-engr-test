<?php

namespace App\Http\Controllers;

use App\Contracts\ClaimInterface;
use App\Http\Requests\ClaimRequest;
use App\Jobs\BatchClaimsJob;
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
        logger('got here');
        $claimData = $request->validated();
        
        $claimData = $this->claimService->prepareClaimData($claimData);

        $this->claimRepository->storeClaimWithItems($claimData);

        BatchClaimsJob::dispatch();

        return response()->json(['message' => 'Claim created successfully'], 200);
    }
}
