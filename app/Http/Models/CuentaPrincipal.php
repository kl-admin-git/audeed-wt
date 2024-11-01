<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaPrincipal extends Model
{
    protected $table = 'cuenta_principal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'correo_electronico', 
        'clave',
        'pais_consecutivo' ,
        'celular_numero',
        'sector_id',
        'plan_id',
        'cuenta_principal_tarjeta_id'
    ];

    public function setClaveAttribute($value)
    {
        if (!empty($value)) {
            // $this->attributes['clave'] = \Hash::make($value);
            $this->attributes['clave'] = bcrypt($value);
        }
    }
}
