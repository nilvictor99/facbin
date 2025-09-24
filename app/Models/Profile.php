<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'profileable_id',
        'profileable_type',
        'identification_type_id',
        'document_number',
        'name',
        'paternal_surname',
        'maternal_surname',
        'full_name',
        'gender',
        'date_of_birth',
        'civil_status',
        'education_level',
        'blood_type',
        'description',
        'adicional_data',
        'characteristics',
        'photo',
        'active',
    ];

    protected $casts = [
        'adicional_data' => 'array',
        'characteristics' => 'array',
    ];

    public function identificationType()
    {
        return $this->belongsTo(IdentificationType::class);
    }

    public function profileable()
    {
        return $this->morphTo();
    }
}
