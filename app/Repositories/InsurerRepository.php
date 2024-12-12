<?php
namespace App\Repositories;

use App\Contracts\InsurerInterface;
use App\Models\Insurer;

class InsurerRepository extends AbstractRepository implements InsurerInterface {


    public function __construct(Insurer $insurer) {

        parent::__construct($insurer);

        $this->model = $insurer;

    }

    public function getAllInsurerTotalBatchSize(): float
    {
        return $this->model->sum('maximum_batch_size');
    }

    public function getAllInsurerDailyProcessingCapacity(): float
    {
        return $this->model->sum('daily_processing_capacity');
    }

}