<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAccionManuDetalle extends Model
{
    protected $table = 'plan_accion_manu_det';
    
    protected $fillable = [
        'plan_accio_man_opc_id', 'lista_cheq_ejec_respuesta_id', 'respuesta'
    ];
}
