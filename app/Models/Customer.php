<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;

class Customer extends Model
{
    protected $fillable = [
        'code',
    ];

    #[SearchUsingFullText(['full_name'])]
    public function toSearchableArray(): array
    {
        return [
            'full_name' => $this->profile->full_name,
        ];
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function address()
    {
        return $this->morphOne(Addresse::class, 'addressable');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function scopeWithProfile(Builder $query)
    {
        return $query->with(['profile']);
    }

    public function scopeWithEditRelations(Builder $query)
    {
        return $query->with(['profile', 'address.ubigeo', 'contacts']);
    }

    public function scopeSearchCustomerData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('profile', function ($q2) use ($search) {
                $q2->where('full_name', 'ILIKE', "%{$search}%");
            })
                ->orWhereHas('contacts', function ($q2) use ($search) {
                    $q2->where('contact_value', 'ILIKE', "%{$search}%");
                });
        });
    }

    public function scopeFilterByCustomer(Builder $query, $customerId)
    {
        return $query->when($customerId, function ($query) use ($customerId) {
            $query->where('id', $customerId);
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null, $customerId = null)
    {
        if (! empty($search)) {
            $query->searchCustomerData($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }
        if (! empty($customerId)) {
            $query->filterByCustomer($customerId);
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
