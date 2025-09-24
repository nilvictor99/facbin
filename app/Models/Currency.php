<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'format',
        'exchange_rate',
        'active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
