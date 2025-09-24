<?php

namespace App\Services\Models;

use App\Repositories\Models\InvoiceRepository;

class InvoiceService extends BaseService
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepository $repository)
    {
        parent::__construct($repository);
    }
}
