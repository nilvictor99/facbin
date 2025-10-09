<?php

namespace App\Repositories\Models;

use App\Enums\MovementTypeEnum;
use App\Models\Inventory;
use App\Services\Auth\AuthService;

class InventoryRepository extends BaseRepository
{
    protected $authService;

    const RELATIONS = [];

    public function __construct(Inventory $model, AuthService $authService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->authService = $authService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $perPage = 5)
    {
        $query = $this->model->withRelations();

        if ($search || $startDate || $endDate) {
            $query = $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getLocations()
    {
        return $this->model->withLocation()->get();
    }

    public function storeInventory(array $data)
    {
        $inventory = $this->model->create([
            'user_id' => $this->authService->getAuthUser()->id,
        ]);

        if (isset($data['inventoryDetails']) && is_array($data['inventoryDetails'])) {
            foreach ($data['inventoryDetails'] as $detail) {
                $movementType = $this->calculateMovementType(
                    $detail['starting_amount'],
                    $detail['ending_amount']
                );
                $inventory->inventoryDetails()->create([
                    'product_id' => $detail['product_id'],
                    'starting_amount' => $detail['starting_amount'],
                    'ending_amount' => $detail['ending_amount'],
                    'difference' => $detail['difference'],
                    'movement_type' => $movementType,
                    'observation' => $detail['observation'],
                    'product_stock' => $detail['ending_amount'],
                ]);
            }
        }

        return $inventory;
    }

    private function calculateMovementType(float $startingAmount, float $endingAmount): string
    {
        if ($endingAmount > $startingAmount) {
            return MovementTypeEnum::INCOME->value;
        } elseif ($endingAmount < $startingAmount) {
            return MovementTypeEnum::OUTPUT->value;
        } else {
            return MovementTypeEnum::STABLE->value;
        }
    }

    public function getDataById($id)
    {
        return $this->model->withEditRelations()->findOrFail($id);
    }

    public function updateInventory($id, array $data)
    {
        $inventory = $this->model->findOrFail($id);

        if (isset($data['inventoryDetails']) && is_array($data['inventoryDetails'])) {
            $inventory->inventoryDetails()->delete();
            foreach ($data['inventoryDetails'] as $detail) {
                $movementType = $this->calculateMovementType(
                    $detail['starting_amount'],
                    $detail['ending_amount']
                );
                $inventory->inventoryDetails()->create([
                    'product_id' => $detail['product_id'],
                    'starting_amount' => $detail['starting_amount'],
                    'ending_amount' => $detail['ending_amount'],
                    'difference' => $detail['difference'],
                    'movement_type' => $movementType,
                    'observation' => $detail['observation'],
                    'product_stock' => $detail['ending_amount'],
                ]);
            }
        }

        return $inventory;
    }
}
