<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'business_name',
        'trade_name',
        'address',
        'ubigeo',
        'urbanization',
        'phone',
        'website',
        'founding_date',
        'industry',
        'status',
        'logo',
        'primary_contact',
        'sponsor',
        'default_shipping_mode',
        'user_id',
    ];

    protected $casts = [
        'founding_date' => 'date',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
