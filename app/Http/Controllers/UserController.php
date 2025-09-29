<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
