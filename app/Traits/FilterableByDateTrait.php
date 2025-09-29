<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterableByDateTrait
{
    public function scopeFilterByDateRange(Builder $query, string $field, ?string $startDate, ?string $endDate): Builder
    {
        if (! empty($startDate)) {
            $query->whereDate($field, '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate($field, '<=', $endDate);
        }

        return $query;
    }

    public function scopeFilterByExactDate(Builder $query, string $field, string $date): Builder
    {
        return $query->whereDate($field, $date);
    }

    public function scopeFilterByMonthYear(Builder $query, string $field, int $month, int $year): Builder
    {
        return $query->whereMonth($field, $month)->whereYear($field, $year);
    }
}
