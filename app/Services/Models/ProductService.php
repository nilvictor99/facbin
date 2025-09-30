<?php

namespace App\Services\Models;

use App\Repositories\Models\ProductRepository;

class ProductService extends BaseService
{
    public function __construct(ProductRepository $repository)
    {
        parent::__construct($repository);
    }
}
