<?php

namespace App\Services\Models;

use App\Repositories\Models\CustomerRepository;

class CustomerService extends BaseService
{
    public function __construct(CustomerRepository $repository)
    {
        parent::__construct($repository);
    }
}
