<?php

namespace App\Repositories\Models;

use App\Models\Summary;

class SummaryRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Summary $model)
    {
        parent::__construct($model, self::RELATIONS);
    }
}
