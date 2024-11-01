<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function Index()
    {
        return view('Auth.login');
    }

    public function Autenticacion(Request $request)
    {
        $correo = $request->get('email');
        $password = $request->get('password');
        $recuerdame = $request->get('recuerdame');
        $recuerdame = ($recuerdame == 'true' ? true : false );
        
        $credenciales = 
        [
            'correo' => $correo,
            'password' => $password
        ];
        
        if (Auth::attempt($credenciales,$recuerdame)) 
        {
            $perfilExacto = 1;
            switch (auth()->user()->perfil_id)
            {
                case 1: // ADMINISTRADOR
                    $perfilExacto = 1;
                    break;

                case 2: // COLABORADOR
                    //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                    $esResponsableEmpresa = \DB::table('empresa')->where('usuario_id','=',auth()->user()->id)->first();

                    if(!is_null($esResponsableEmpresa))
                        $perfilExacto = 2;


                    // //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                    $esResponsableEstablecimiento = \DB::table('establecimiento')->where('usuario_id','=',auth()->user()->id)->first();
                    if(!is_null($esResponsableEstablecimiento))
                        $perfilExacto = 3;

                    if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                        $perfilExacto = 4;

                    break;

                default:

                    break;
            };

            return $this->FinalizarRetorno(
                202,
                $this->MensajeRetorno('El correo electrónico ',202),
                $perfilExacto
            );
        } 
        else 
        {
            return $this->FinalizarRetorno(
                400,
                $this->MensajeRetorno('',400)
            );
        }
        
    }

    public function logout(Request $request)
    {
        if(!is_null(auth()->user()))
        {
            $idUsuario = auth()->user()->id;
        
            Auth::logout();
            
            \DB::table('usuario')
            ->where('id', '=' ,$idUsuario)
            ->update(['remember_token' => null]);
        }

        return redirect('/');
    }
}
