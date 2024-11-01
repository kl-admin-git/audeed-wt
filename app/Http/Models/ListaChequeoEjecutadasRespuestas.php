<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEjecutadasRespuestas extends Model
{
    protected $table = 'lista_chequeo_ejec_respuestas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'pregunta_id', 
        'ponderado_pregunta',
        'categoria_id',
        'ponderado_categoria',
        'respuesta_id',
        'no_aplica',
        'lista_chequeo_ejec_id',
        'respuesta_abierta'
    ];
}
