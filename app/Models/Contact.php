<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'contactable_type',
        'contactable_id',
        'contact_type',
        'contact_value',
        'country_code',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
