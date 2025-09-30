<?php

namespace App\Repositories\Models;

use App\Models\Inventory;

class InventoryRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Inventory $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $perPage = 5)
    {
        $query = $this->model->withRelations();

        if ($search || $startDate || $endDate) {
            $query = $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getLocations()
    {
        return $this->model->withLocation()->get();
    }
}
