<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRespuestaPonderadoPredeterminado extends Model
{
    protected $table = 'tipo_respuesta_ponderado_pred';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'valor_original', 
        'ponderado',
        'orden',
        'tipo_respuesta_id'
    ];
}
