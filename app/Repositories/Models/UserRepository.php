<?php

namespace App\Repositories\Models;

use App\Models\User;

class UserRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(User $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $userId = null, $perPage = 5)
    {

        $query = $this->model->withUserProfile();

        if ($search || $startDate || $endDate || $userId) {
            $query->searchData($search, $startDate, $endDate, $userId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getUsers()
    {
        return $this->model->all();
    }
}
