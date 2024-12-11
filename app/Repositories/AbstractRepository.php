<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected Model $model;

    /**
     * AbstractRepository constructor.
     *
     * @param $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}