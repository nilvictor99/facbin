<?php

namespace App\Repositories\Models;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Product $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $customerId = null, $perPage = 5)
    {
        $query = $this->model->withRelations();

        if ($search || $startDate || $endDate || $customerId) {
            $query = $query->searchData($search, $startDate, $endDate, $customerId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getCustomers()
    {
        return $this->model->withProfile()->get();
    }

    public function storeData($data)
    {
        return $this->model->create($data);
    }

    public function getDataById($id)
    {
        return $this->model->withEditRelations()->findOrFail($id);
    }

    public function updateData($id, $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record;
    }
}
