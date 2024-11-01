<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccion extends Model
{
    protected $table = 'plan_accion';

    protected $fillable = [
        'tipo_pa', 
        'obligatorio',
        'alerta',
        'pregunta_id',
        'respuesta_id'
    ];
}
