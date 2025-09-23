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
}
