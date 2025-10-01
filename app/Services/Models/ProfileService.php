<?php

namespace App\Services\Models;

use App\Repositories\Models\ProfileRepository;

class ProfileService extends BaseService
{
    public function __construct(ProfileRepository $repository)
    {
        parent::__construct($repository);
    }
}
