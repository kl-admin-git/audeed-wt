<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccionManual extends Model
{
    protected $table = 'plan_accion_manual';

    protected $fillable = [
        'id',
        'plan_accion_id', 
        'requerido',
        'plan_accion_man_opc_id'
    ];
}
