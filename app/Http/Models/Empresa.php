<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'identificacion',
        'correo' ,
        'telefono',
        'direccion', 
        'ciudad_id',
        'sector_id' ,
        'usuario_id',
        'estado',
        'url_imagen',
        'cuenta_principal_id'
    ];
}
