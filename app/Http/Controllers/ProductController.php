<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Models\CurrencyService;
use App\Services\Models\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    private $productService;

    private $currencyService;

    public function __construct(ProductService $productService, CurrencyService $currencyService)
    {
        $this->productService = $productService;
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        return Inertia::render('Product');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $perPage = $request->input('per_page', 5);
        $data = $this->productService->getModel($search, $startDate, $endDate, $perPage);

        return Inertia::render('Product/List', [
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
        return Inertia::render('Product/Create', [
            'currencies' => $this->currencyService->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $this->productService->storeData($request->all());

        return redirect()->route('products.index')->banner('Producto creado ');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $this->productService->getDataById($request->id);

        return Inertia::render('Product/Edit', [
            'data' => $data,
            'currencies' => $this->currencyService->getAll(),
        ]);
    }

    public function update(ProductUpdateRequest $request)
    {
        $this->productService->updateData($request->id, $request->validated());

        return redirect()->route('products.list')->banner('Producto actualizado');
    }

    public function destroy(Product $product)
    {
        //
    }
}
