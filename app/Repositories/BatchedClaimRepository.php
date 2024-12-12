<?php
namespace App\Repositories;

use App\Contracts\BatchedClaimInterface;
use App\Models\BatchedClaim;

class BatchedClaimRepository extends AbstractRepository implements BatchedClaimInterface {


    public function __construct(BatchedClaim $insurer) {

        parent::__construct($insurer);

        $this->model = $insurer;

    }

    public function truncate(): void {
        $this->model->truncate();
    }

    public function storeMany(array $data): void {
        $this->model->insertOrIgnore($data);
    }

}