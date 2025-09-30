<?php

namespace App\Services\Models;

use App\Repositories\Models\InventoryRepository;

class InventoryService extends BaseService
{
    public function __construct(InventoryRepository $repository)
    {
        parent::__construct($repository);
    }
}
