<?php

namespace App\Services\Models;

use App\Repositories\Models\MeasureUnitRepository;

class MeasureUnitService extends BaseService
{
    protected $measureUnitRepository;

    public function __construct(MeasureUnitRepository $repository)
    {
        parent::__construct($repository);
    }
}
