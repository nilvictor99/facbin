<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class Ubigeo extends Model
{
    use Searchable;

    protected $primaryKey = 'cod_ubigeo';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cod_ubigeo',
        'departament',
        'province',
        'district',
        'full_ubigeo',
    ];

    protected $casts = [
        'cod_ubigeo' => 'string',
    ];

    #[SearchUsingFullText(['cod_ubigeo'])]
    public function toSearchableArray(): array
    {
        return [
            'province' => $this->province,
            'district' => $this->district,
            'cod_ubigeo' => $this->cod_ubigeo,
        ];
    }

    public function getFullUbigeoAttribute(): string
    {
        return "{$this->departament} - {$this->province} - {$this->district}";
    }

    public function scopeSearchData(Builder $query, $searchTerm)
    {
        return $query->where('district', 'LIKE', "%{$searchTerm}%")
            ->orWhere('province', 'LIKE', "%{$searchTerm}%")
            ->orWhere('cod_ubigeo', 'LIKE', "%{$searchTerm}%");
    }
}
