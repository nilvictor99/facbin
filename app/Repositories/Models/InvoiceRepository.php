<?php

namespace App\Repositories\Models;

use App\Models\Invoice;

class InvoiceRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Invoice $model)
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
}
