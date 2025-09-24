<?php

namespace App\Services\Models;

use App\Repositories\Models\VoidedRepository;

class VoidedService extends BaseService
{
    protected $voidedRepository;

    public function __construct(VoidedRepository $repository)
    {
        parent::__construct($repository);
    }
}
