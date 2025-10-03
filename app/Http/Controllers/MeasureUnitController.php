<?php

namespace App\Http\Controllers;

use App\Models\MeasureUnit;
use App\Services\Models\MeasureUnitService;
use Illuminate\Http\Request;

class MeasureUnitController extends Controller
{
    private $measureUnitService;

    public function __construct(MeasureUnitService $measureUnitService)
    {
        $this->measureUnitService = $measureUnitService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = $this->measureUnitService->searchData($query);

        return $results;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(MeasureUnit $measureUnit)
    {
        //
    }

    public function edit(MeasureUnit $measureUnit)
    {
        //
    }

    public function update(Request $request, MeasureUnit $measureUnit)
    {
        //
    }

    public function destroy(MeasureUnit $measureUnit)
    {
        //
    }
}
