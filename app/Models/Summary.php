<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'fec_generacion',
        'fec_resumen',
        'correlativo',
        'company_id',
        'estado',
        'ticket',
        'xml_path',
        'cdr_path',
        'details',
    ];

    protected $casts = [
        'fec_generacion' => 'datetime',
        'fec_resumen' => 'datetime',
        'details' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
