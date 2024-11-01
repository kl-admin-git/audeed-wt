<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'ponderado',
        'orden_categoria',
        'orden_lista',
        'lista_chequeo_id',
        'id_etiqueta'
    ];
}
