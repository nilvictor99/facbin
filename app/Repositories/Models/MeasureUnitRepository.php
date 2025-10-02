<?php

namespace App\Repositories\Models;

use App\Models\MeasureUnit;

class MeasureUnitRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(MeasureUnit $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function searchData($query)
    {
        return $this->model->searchData($query)->take(10)->get();
    }
}
