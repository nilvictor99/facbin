<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Services\Models\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    private $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Inventory $inventory)
    {
        //
    }

    public function edit(Inventory $inventory)
    {
        //
    }

    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    public function destroy(Inventory $inventory)
    {
        //
    }
}
