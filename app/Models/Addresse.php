<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Addresse extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'ubigeo_cod',
        'address',
        'reference',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function ubigeo(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_cod', 'cod_ubigeo');
    }

    public function scopeSearchAddress(Builder $query, $search)
    {
        return $query->whereRaw('address ILIKE ?', ["%{$search}%"]);
    }
}
