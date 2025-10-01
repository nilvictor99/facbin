<?php

namespace App\Repositories\Models;

use App\Models\IdentificationType;

class IdentificationTypeRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(IdentificationType $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getSimpleData()
    {
        return $this->model->simpleData();
    }
}
