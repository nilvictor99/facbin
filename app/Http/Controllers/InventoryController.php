<?php

namespace App\Http\Controllers;

use App\Services\Models\InventoryService;
use App\Services\Models\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    private $inventoryService;

    private $productService;

    public function __construct(InventoryService $inventoryService, ProductService $productService)
    {
        $this->inventoryService = $inventoryService;
        $this->productService = $productService;
    }

    public function index()
    {
        return Inertia::render('Inventory');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $perPage = $request->input('per_page', 5);
        $data = $this->inventoryService->getModel($search, $startDate, $endDate, $perPage);

        return Inertia::render('Inventory/List', [
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
        $data = $this->productService->getDataInventory();

        return Inertia::render('Inventory/Create', [
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $this->inventoryService->storeInventory($request->all());

        return redirect()->route('inventory.list')->banner('Inventario creado correctamente');
    }

    public function edit(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }
}
