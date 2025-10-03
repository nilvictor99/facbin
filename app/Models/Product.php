<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['currency']);
    }

    public function scopeWithEditRelations(Builder $query)
    {
        return $query->with(['currency', 'measureUnit']);
    }

    public function scopeSearchProductData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhereHas('measureUnit', function ($q2) use ($search) {
                    $q2->where('name', 'ILIKE', "%{$search}%");
                });
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null)
    {
        if (! empty($search)) {
            $query->searchProductData($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }

        return $query;
    }

    public function scopeFilterByDateRange(Builder $query, $startDate, $endDate)
    {
        if (! empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

    public function scopeGetDataInventory(Builder $query)
    {
        return $query->select('id', 'name', 'stock');
    }
}
