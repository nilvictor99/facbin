<?php

namespace App\Models;

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
}
