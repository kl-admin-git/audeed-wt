<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPagos extends Model
{
    protected $table = 'plan_pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'plan_id', 
        'fecha_inicio',
        'fecha_fin',
        'tipo_pago',
        'cuenta_principal_tarjeta_id',
        'cuenta_principal_id'
    ];
}
