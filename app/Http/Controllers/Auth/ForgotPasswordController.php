<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Usuario;
use App\Mail\MailRecuperarPassword;

class ForgotPasswordController extends Controller
{
    protected $usuario;
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;        
    }

    public function Index()
    {
        return view('Auth.forgot');
    }

    public function IndexNuevoPassword()
    {
        return view('Auth.forgot_recovery');
    }

    public function ActualizarPassword(Request $request)
    {
        $idUsuario = $request->get('idUsuario');
        $nuevoPassword = $request->get('nuevoPassword');

        $arrayUpdate = [
            'password' => bcrypt($nuevoPassword), 
            'password_visible' => $nuevoPassword
        ];

        $respuestaUpdate = $this->usuario->where('id','=',$idUsuario)->update($arrayUpdate);

        if($respuestaUpdate)
        {
            return $this->FinalizarRetorno(
                201,
                $this->MensajeRetorno('El usuario ',201)
            );
        }
    }
    

    public function RecuperarPassword(Request $request)
    {
        $email = $request->get('email');

        $usuario = $this->usuario->where('correo','=',$email)->first();

        if(!is_null($usuario))
        {
            \Mail::to($email)->send(new MailRecuperarPassword($usuario));
            return $this->FinalizarRetorno(
                202,
                $this->MensajeRetorno('',202)
            );
        }
        else
        {
            return $this->FinalizarRetorno(
                404,
                $this->MensajeRetorno('El usuario ',404)
            );
        }
        
    }
}
