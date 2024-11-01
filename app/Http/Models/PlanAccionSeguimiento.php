<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccionSeguimiento extends Model
{
    protected $table = 'plan_accion_seguimiento';

    protected $fillable = [
        'usuario_id',
        'observacion',
        'estado',
        'plan_accion_id'
    ];
}
