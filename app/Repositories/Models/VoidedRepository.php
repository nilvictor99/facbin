<?php

namespace App\Repositories\Models;

use App\Models\Voided;

class VoidedRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Voided $model)
    {
        parent::__construct($model, self::RELATIONS);
    }
}
