<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\Models\InvoiceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    private $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        return Inertia::render('Invoice');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $perPage = $request->input('per_page', 5);
        $data = $this->invoiceService->getModel($search, $startDate, $endDate, $perPage);

        return Inertia::render('Invoice/List', [
            'data' => $data,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Invoice $invoice)
    {
        //
    }

    public function edit(Invoice $invoice)
    {
        //
    }

    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        //
    }
}
