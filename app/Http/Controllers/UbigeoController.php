<?php

namespace App\Http\Controllers;

use App\Models\Ubigeo;
use App\Services\Models\UbigeoService;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
    private $ubigeoService;

    public function __construct(UbigeoService $ubigeoService)
    {
        $this->ubigeoService = $ubigeoService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = $this->ubigeoService->search($query);

        return $results;
    }

    public function index() {}

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Ubigeo $ubigeo)
    {
        //
    }

    public function edit(Ubigeo $ubigeo)
    {
        //
    }

    public function update(Request $request, Ubigeo $ubigeo)
    {
        //
    }

    public function destroy(Ubigeo $ubigeo)
    {
        //
    }
}
