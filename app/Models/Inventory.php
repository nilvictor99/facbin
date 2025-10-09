<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventoryDetails()
    {
        return $this->hasMany(InventoryDetail::class);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'inventoryDetails']);
    }

    public function scopeWithEditRelations($query)
    {
        return $query->with(['user', 'inventoryDetails.product']);
    }

    public function scopeSearchInventoryData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($q2) use ($search) {
                $q2->where('name', 'ILIKE', "%{$search}%");
            });
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null)
    {
        if (! empty($search)) {
            $query->searchInventoryData($search);
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
}
