<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function address()
    {
        return $this->morphOne(Addresse::class, 'addressable');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function scopeNextCode(Builder $query)
    {
        $lastBranch = $query->orderBy('id', 'desc')->first();
        $nextCode = $lastBranch ? (int) $lastBranch->id + 1 : 1;

        return str_pad((string) $nextCode, 4, '0', STR_PAD_LEFT);
    }
}
