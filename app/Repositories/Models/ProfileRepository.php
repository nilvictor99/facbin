<?php

namespace App\Repositories\Models;

use App\Models\Profile;

class ProfileRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Profile $model)
    {
        parent::__construct($model, self::RELATIONS);
    }
}
