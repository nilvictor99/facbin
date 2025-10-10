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

    public function StoreUser($data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        $user = $this->model->create($userData);
        $profileData = [
            'identification_type_id' => $data['identification_type_id'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'paternal_surname' => $data['paternal_surname'] ?? null,
            'maternal_surname' => $data['maternal_surname'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'full_name' => $data['name'].' '.($data['paternal_surname'] ?? '').' '.($data['maternal_surname'] ?? ''),
        ];
        $user->profile()->create($profileData);

        return $user;
    }

    public function getEditData($id)
    {
        return $this->model->withUserProfile()->findOrFail($id);
    }

    public function UpdateUser($id, $data)
    {
        $user = $this->model->findOrFail($id);
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        $user->update($userData);

        $profileData = [
            'identification_type_id' => $data['identification_type_id'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'name' => $data['name'] ?? null,
            'paternal_surname' => $data['paternal_surname'] ?? null,
            'maternal_surname' => $data['maternal_surname'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'full_name' => $data['name'].' '.($data['paternal_surname'] ?? '').' '.($data['maternal_surname'] ?? ''),
        ];
        $user->profile()->update($profileData);

        return $user;
    }

    public function DeleteData($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();

        return true;
    }
}
