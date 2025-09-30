<?php

namespace App\Services\Models;

use App\Repositories\Models\IdentificationTypeRepository;

class IdentificationTypeService extends BaseService
{
    public function __construct(IdentificationTypeRepository $repository)
    {
        parent::__construct($repository);
    }
}
