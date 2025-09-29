<?php

namespace App\Repositories\Models;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Customer $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $customerId = null, $perPage = 5)
    {
        $query = $this->model->withProfile();

        if ($search || $startDate || $endDate || $customerId) {
            $query = $query->searchData($search, $startDate, $endDate, $customerId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getCustomers()
    {
        return $this->model->withProfile()->get();
    }
}
