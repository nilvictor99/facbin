<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'serie',
        'correlativo',
        'tipo_doc',
        'fecha_emision',
        'subtotal',
        'igv_percentage',
        'igv',
        'total',
        'estado',
        'customer_id',
        'company_id',
        'xml_path',
        'cdr_path',
        'ticket',
        'doc_respuesta',
        'cadena_ticket',
        'url_ticket',
        'url_a4',
        'cadena_xml',
        'url_xml',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
