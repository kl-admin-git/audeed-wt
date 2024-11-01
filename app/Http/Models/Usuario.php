<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Crypt;
class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre_completo',
        'identificacion',
        'correo',
        'telefono',
        'cargo_id',
        'perfil_id',
        'establecimiento_id',
        'usuario',
        'password',
        'password_visible',
        'url_imagen',
        'cuenta_principal_id',
        'establecimiento_id'
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // $this->attributes['password'] = \Hash::make($value);
            $this->attributes['password'] = bcrypt($value);
            // $this->attributes['password'] = Crypt::encrypt($value);
        }
    }

    // public function getPasswordAttribute()
    // {
    //     $password = Crypt::encrypt($this->attributes['password']);
    //     return $password;
    // }

    protected $hidden = ['password', 'remember_token'];

}
