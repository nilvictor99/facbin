<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\Models\CustomerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Customer $customer)
    {
        //
    }

    public function edit(Customer $customer)
    {
        //
    }

    public function update(Request $request, Customer $customer)
    {
        //
    }

    public function destroy(Customer $customer)
    {
        //
    }
}
