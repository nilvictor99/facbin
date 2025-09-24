<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'measure_unit_id',
        'stock',
        'sale_price',
        'purchase_price',
        'active',
        'characters',
        'instructions',
        'currency_id',
    ];

    protected $casts = [
        'characters' => 'array',
        'active' => 'boolean',
    ];

    public function measureUnit()
    {
        return $this->belongsTo(MeasureUnit::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
