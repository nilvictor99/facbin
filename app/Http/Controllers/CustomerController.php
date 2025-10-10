<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Services\Models\CustomerService;
use App\Services\Models\IdentificationTypeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    private $IdentificationTypeService;

    private $customerService;

    public function __construct(CustomerService $customerService, IdentificationTypeService $IdentificationTypeService)
    {
        $this->customerService = $customerService;
        $this->IdentificationTypeService = $IdentificationTypeService;
    }

    public function index()
    {
        return Inertia::render('Customer');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $customerId = $request->input('customer_id');
        $perPage = $request->input('per_page', 5);
        $customers = $this->customerService->getCustomers();
        $data = $this->customerService->getModel($search, $startDate, $endDate, $customerId, $perPage);

        return Inertia::render('Customer/List', [
            'data' => $data,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'customers' => $customers,
            'customerId' => $customerId,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $identificationTypes = $this->IdentificationTypeService->getSimpleData();

        return Inertia::render('Customer/Create', [
            'identificationTypes' => $identificationTypes,
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $this->customerService->storeData($request->validated());

        return redirect()->route('customers.index')->banner('Cliente creado');
    }

    public function show(Customer $customer)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $this->customerService->getDataById($request->id);

        return Inertia::render('Customer/Edit', [
            'data' => $data,
            'identificationTypes' => $this->IdentificationTypeService->getSimpleData(),
        ]);
    }

    public function update(CustomerUpdateRequest $request)
    {
        $this->customerService->updateData($request->id, $request->validated());

        return redirect()->route('customers.index')->banner('Cliente actualizado');
    }

    public function destroy(Request $request)
    {
        $this->customerService->deleteData($request->id);

        return redirect()->route('customers.index')->banner('Cliente eliminado');
    }
}
