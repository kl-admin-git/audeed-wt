<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaPrincipalTarjeta extends Model
{
    protected $table = 'cuenta_principal_tarjeta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'banco', 
        'tipo_tarjeta',
        'tipo_tarjeta_2',
        'nombre_tarjeta',
        'identificacion',
        'numero',
        'token',
        'id_customer'
    ];
}
