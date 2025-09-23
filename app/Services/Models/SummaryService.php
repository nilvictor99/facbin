<?php

namespace App\Services\Models;

use App\Repositories\Models\SummaryRepository;

class SummaryService extends BaseService
{
    protected $summaryRepository;

    public function __construct(SummaryRepository $repository)
    {
        parent::__construct($repository);
    }
}
