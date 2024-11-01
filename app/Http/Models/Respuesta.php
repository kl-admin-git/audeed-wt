<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuesta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tipo_respuesta_ponderado_pred_id', 
        'valor_personalizado',
        'ponderado',
        'pregunta_id'
    ];
}
