<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['ruc', 'razon_social', 'nombre_comercial', 'direccion', 'ubigeo'];
}
