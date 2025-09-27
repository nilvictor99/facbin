<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    protected $fillable = [
        'inventory_id',
        'product_id',
        'starting_amount',
        'ending_amount',
        'difference',
        'movement_type',
        'observation',
        'product_stock',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
