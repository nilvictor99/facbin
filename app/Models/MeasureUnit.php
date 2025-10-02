<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MeasureUnit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeSearchData(Builder $query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('code', 'LIKE', "%{$searchTerm}%")
            ->orWhere('symbol', 'LIKE', "%{$searchTerm}%");
    }
}
