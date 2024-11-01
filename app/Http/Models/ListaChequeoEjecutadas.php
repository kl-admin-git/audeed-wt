<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEjecutadas extends Model
{
    protected $table = 'lista_chequeo_ejecutadas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'lista_chequeo_id', 
        'evaluado_id',
        'usuario_id',
        'latitud',
        'longitud',
        'direccion',
        'estado',
        'fecha_realizacion',
        'finished_at',
        'obsgeneral'
    ];
}
