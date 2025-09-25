<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'user_id',
        'movementable_type',
        'movementable_id',
        'backup',
        'description',
    ];

    protected $casts = [
        'backup' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movementable()
    {
        return $this->morphTo();
    }
}
