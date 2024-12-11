<?php
namespace App\Services;

class ClaimService {

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

}