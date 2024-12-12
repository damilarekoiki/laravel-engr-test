<?php
namespace App\Contracts;

interface InsurerInterface {

    public function getAllInsurerTotalBatchSize(): float;

    public function getAllInsurerDailyProcessingCapacity(): float;
}