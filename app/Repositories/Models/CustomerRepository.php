<?php

namespace App\Repositories\Models;

use App\Models\Customer;
use App\Services\Utils\CodeGeneratorService;

class CustomerRepository extends BaseRepository
{
    protected $CodeGeneratorService;

    const RELATIONS = [];

    public function __construct(Customer $model, CodeGeneratorService $CodeGeneratorService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->CodeGeneratorService = $CodeGeneratorService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $customerId = null, $perPage = 5)
    {
        $query = $this->model->withProfile();

        if ($search || $startDate || $endDate || $customerId) {
            $query = $query->searchData($search, $startDate, $endDate, $customerId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getCustomers()
    {
        return $this->model->withProfile()->get();
    }

    public function storeData(array $data)
    {
        $customer = $this->model->create(['code' => $this->CodeGeneratorService->generate('alphanumeric', 8)]);

        $profileData = [
            'identification_type_id' => $data['identification_type_id'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'paternal_surname' => $data['paternal_surname'] ?? null,
            'maternal_surname' => $data['maternal_surname'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'full_name' => $data['name'].' '.($data['paternal_surname'] ?? '').' '.($data['maternal_surname'] ?? ''),
        ];
        $customer->profile()->create($profileData);

        $addressData = [
            'ubigeo_cod' => $data['ubigeo_cod'] ?? null,
            'address' => $data['address'] ?? null,
            'reference' => $data['reference'] ?? null,
        ];
        $customer->address()->create($addressData);

        $contactData = [
            'contact_type' => $data['contact_type'] ?? null,
            'contact_value' => $data['contact_value'] ?? null,
        ];
        $customer->contacts()->create($contactData);

        return $customer;
    }

    public function getDataById($id)
    {
        return $this->model->withEditRelations()->findOrFail($id);
    }

    public function updateData($id, $data)
    {
        $customer = $this->model->findOrFail($id);
        $customer->update();

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
        $customer->profile()->update($profileData);

        $addressData = [
            'ubigeo_cod' => $data['ubigeo_cod'] ?? null,
            'address' => $data['address'] ?? null,
            'reference' => $data['reference'] ?? null,
        ];
        $customer->address()->update($addressData);

        $contactData = [
            'contact_type' => $data['contact_type'] ?? null,
            'contact_value' => $data['contact_value'] ?? null,
        ];
        $customer->contacts()->update($contactData);

        return $customer;
    }
}
