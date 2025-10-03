<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\Models\IdentificationTypeService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    private $userService;

    private $IdentificationTypeService;

    public function __construct(UserService $userService, IdentificationTypeService $IdentificationTypeService)
    {
        $this->userService = $userService;
        $this->IdentificationTypeService = $IdentificationTypeService;
    }

    public function index()
    {
        return Inertia::render('User');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $userId = $request->input('user_id');
        $perPage = $request->input('per_page', 5);
        $users = $this->userService->getUsers();
        $data = $this->userService->getModel($search, $startDate, $endDate, $userId, $perPage);

        return Inertia::render('User/List', [
            'data' => $data,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'users' => $users,
            'userId' => $userId,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $identificationTypes = $this->IdentificationTypeService->getSimpleData();

        return Inertia::render('User/Create', [
            'identificationTypes' => $identificationTypes,
        ]);
    }

    public function store(Request $request)
    {
        $this->userService->StoreUser($request->all());

        return redirect()->route('users.list')->banner('Usuario creado');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $this->userService->getEditData($request->id);

        return Inertia::render('User/Edit', [
            'data' => $data,
            'identificationTypes' => $this->IdentificationTypeService->getSimpleData(),
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        $this->userService->UpdateUser($request->id, $request->validated());

        return redirect()->route('users.list')->banner('Usuario actualizado');
    }

    public function destroy(User $user)
    {
        //
    }
}
