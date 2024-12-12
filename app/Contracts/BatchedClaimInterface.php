<?php
namespace App\Contracts;

use App\Models\BatchedClaim;

interface BatchedClaimInterface {
    
    public function storeMany(array $data): void;

    public function truncate(): void;

}