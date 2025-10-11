<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['customer.profile', 'company', 'details']);
    }

    public function scopeSearchInvoiceData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('serie', 'ILIKE', "%{$search}%")
                ->orWhereHas('customer.profile', function ($q2) use ($search) {
                    $q2->where('full_name', 'ILIKE', "%{$search}%")
                        ->orWhere('document_number', 'ILIKE', "%{$search}%");
                });
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null)
    {
        if (! empty($search)) {
            $query->searchInvoiceData($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }

        return $query;
    }

    public function scopeFilterByDateRange(Builder $query, $startDate, $endDate)
    {
        if (! empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }
}
