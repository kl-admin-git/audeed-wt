<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEtiquetas extends Model
{
    protected $table = 'categoria_etiquetas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'descripcion',
        'cuenta_principal_id'
    ];
}
