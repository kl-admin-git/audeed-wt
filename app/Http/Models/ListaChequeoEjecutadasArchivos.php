<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEjecutadasArchivos extends Model
{
    protected $table = 'lista_chequeo_ejec_archivos';

    protected $fillable = [
        'archivo_codificado', 
        'archivo_alias',
        'lista_chequeo_ejec_respuesta_id'
    ];
}
