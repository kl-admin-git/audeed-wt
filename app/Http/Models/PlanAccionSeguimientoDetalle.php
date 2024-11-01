<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccionSeguimientoDetalle extends Model
{
    protected $table = 'plan_accion_seguimiento_detalle';

    protected $fillable = [
        'archivo',
        'descripcion',
        'id_plan_accion_seguimiento'
    ];
}
