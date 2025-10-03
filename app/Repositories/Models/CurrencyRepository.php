<?php

namespace App\Repositories\Models;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Currency $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getAll()
    {
        return $this->model->all();
    }
}
