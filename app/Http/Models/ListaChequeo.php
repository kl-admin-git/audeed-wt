<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeo extends Model
{
    protected $table = 'lista_chequeo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'publicacion_destino',
        'entidad_evaluada',
        'estado',
        'usuario_id',
        'tipo_ponderados',
        'favorita',
        'espacio_mb',
        'modelo_id',
    ];
}
