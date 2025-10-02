<?php

namespace App\Services\Models;

use App\Repositories\Models\CurrencyRepository;

class CurrencyService extends BaseService
{
    public function __construct(CurrencyRepository $repository)
    {
        parent::__construct($repository);
    }
}
