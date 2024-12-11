<?php
namespace App\Contracts;

use App\Models\Claim;

interface ClaimInterface {

    public function store(array $data): Claim;

    public function storeClaimItems(Claim $claim, array $claimItems): void;

    public function storeClaimWithItems(array $data): void;
}