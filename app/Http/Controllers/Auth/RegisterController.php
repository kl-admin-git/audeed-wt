<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Models\Usuario;
use App\Http\Models\CuentaPrincipal;
use App\Http\Models\Pais;
use App\Http\Models\ListaChequeoModelos;
use App\Http\Models\Sector;
use App\Http\Models\Empresa;
use App\Http\Models\Establecimiento;
use App\Http\Models\ListaChequeoEjecutadas;
use App\Http\Models\ListaChequeo;
use App\Mail\MailCrearCuenta;

class RegisterController extends Controller
{
    use AuthenticatesUsers;
    
    protected $usuarios,$cuentaPrincipal,$sector,$empresa,$establecimiento,$modelos;

    public function __construct(Usuario $usuarios, 
    ListaChequeoEjecutadas $listaEjecutada,
    CuentaPrincipal $cuentaPrincipal,
    Pais $pais,Sector $sector,
    Empresa $empresa,
    Establecimiento $establecimiento,
    ListaChequeoModelos $modelos)
    {
        $this->usuarios = $usuarios;
        $this->cuentaPrincipal = $cuentaPrincipal;
        $this->pais = $pais;
        $this->sector = $sector;
        $this->empresa = $empresa;
        $this->establecimiento = $establecimiento;
        $this->listaEjecutada = $listaEjecutada;
        $this->modelos = $modelos;

    }

    public function Index()
    {
       
        $sectores = $this->sector->select('id','nombre')
        ->orderBy('nombre','ASC')
        ->where('estado','=', 1)
        ->get();

        $paises = $this->pais
        ->select('id','indicativo','nombre',
        \DB::raw('CONCAT(indicativo," (",nombre,")") AS CONCATENACION')
        )->get();

        return view('Auth.register',compact('paises','sectores'));
    }

    public function IndexColaborador()
    {
        if(!is_null(auth()->user()))
        {
            $idListaChequeo = decrypt(\Request::segment(3));
            \Redirect::to('/listachequeo/ejecucion/'.$idListaChequeo)->send();
        }
        // DECRYPT
        $idUsuario = decrypt(\Request::segment(2));
        $idListaChequeo = decrypt(\Request::segment(3));

        $informacionUsuarioCreadorListaChequeo = $this->usuarios->where('id','=',$idUsuario)->first();

        $empresas = $this->empresa->where('cuenta_principal_id','=',$informacionUsuarioCreadorListaChequeo->cuenta_principal_id)->get();

        return view('Auth.register_new_users',compact('empresas'));
    }

    public function CambioDeEmpresas(Request $resquest)
    {
        $idListaChequeo = $resquest->get('idEmpresa');

        $establecimientos = $this->establecimiento->where('empresa_id','=',$idListaChequeo)->get();    

        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos',202),
            $establecimientos
        );
    }

    public function RegistrarCuentaColaborador(request $request)
    {
        
        $nombreCompleto = $request->get('nombreCompleto');
        $email = $request->get('email');
        $password = $request->get('password');
        $password_visible = $request->get('password');
        $empresa = $request->get('empresa');
        $establecimiento = $request->get('establecimiento');
        $idUsuario = $request->get('idCuentaPrincipal');
        $informacionUsuarioCreadorListaChequeo = $this->usuarios->where('id','=',$idUsuario)->first();
        $idCuentaPrincipal = $informacionUsuarioCreadorListaChequeo->cuenta_principal_id;
        $idListaChequeo = $request->get('idListaChequeo');

        if (\DB::table('usuario')->where('correo', '=', $email)->exists()) 
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'Este correo electrónico ya existe')
            );
        }

        $usuarios = new $this->usuarios;
        $usuarios->fill([
            'nombre_completo' => $nombreCompleto, 
            'correo' => $email,
            'perfil_id' => 2,
            'usuario' => $email,
            'password' => $password,
            'password_visible' => $password_visible,
            'establecimiento_id' => $establecimiento,
            'cuenta_principal_id' => $idCuentaPrincipal
        ]); 

        if($usuarios->save())
        {
            $credenciales = 
            [
                'correo' => $email,
                'password' => $password
            ];
            
            if(Auth::attempt($credenciales))
            {                    
                
                $arrayInsertar = [
                    'lista_chequeo_id' => $idListaChequeo, 
                    'usuario_id' => $usuarios->id,
                    'fecha_realizacion' => date('Y-m-d')
                ];

                $listaEjecutada = new $this->listaEjecutada;
                $listaEjecutada->fill($arrayInsertar);
                
                if($listaEjecutada->save())
                {
                    // return redirect('/listachequeo/ejecucion/'.$idListaChequeo.'/'.$listaEjecutada->id);
                    return $this->FinalizarRetorno(
                        200,
                        $this->MensajeRetorno('El usuario ',200),
                        array('idListaChequeo' => $idListaChequeo,'idListaEjecutada' => $listaEjecutada->id)
                    );
                }
            }
        }

    }

    public function RegistrarCuenta(request $request)
    {
        
        
        $correo = $request->get('correo');
        $paisCodigo = ($request->get('paisCodigo') == '' ? NULL : $request->get('paisCodigo'));
        $telefono = ($request->get('telefono') == '' ? NULL : $request->get('telefono'));
        $password = $request->get('password');
        $password_visible = $request->get('password');
        $sector = $request->get('sector');
        // $modelos  = $this->modelos->get();
        // $modeloSector = \DB::table('modelos_sector')->where('sector_id','=',$sector)->first();
       
     
        if (\DB::table('cuenta_principal')->where('correo_electronico', '=', $correo)->exists()) 
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'Este correo electrónico ya existe')
            );
        }

        if(!is_null($telefono))
        {
            if (\DB::table('cuenta_principal')->where('celular_numero', '=', $telefono)->exists()) 
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'Este número telefónico ya existe')
                );
            }
        }

        $cuentaPrincipal = new $this->cuentaPrincipal;
        $cuentaPrincipal->fill(
        [
            'correo_electronico' => $correo, 
            'clave' => $password,
            'pais_consecutivo' => $paisCodigo,
            'celular_numero' => $telefono,
            'sector_id' => $sector,
            'plan_id' => NULL 
        ]);

        if($cuentaPrincipal->save())
        {

            $empresa = new $this->empresa;
            $empresa->fill(
            [
                'nombre' => 'Mi empresa', 
                'sector_id' => $sector,
                'cuenta_principal_id' => $cuentaPrincipal->id
            ]);

            if($empresa->save())
            {
                $establecimiento = new $this->establecimiento;
                $establecimiento->fill(
                [
                    'nombre' => 'Establecimiento principal', 
                    'empresa_id' => $empresa->id
                ]);

                if($establecimiento->save())
                {
                    $usuarios = new $this->usuarios;
                    $usuarios->fill([
                        'nombre_completo' => 'Admin', 
                        'correo' => $correo,
                        'telefono' => $telefono,
                        'identificacion' => NULL,
                        'perfil_id' => 1,
                        'usuario' => $correo,
                        'password' => $password,
                        'password_visible' => $password_visible,
                        'establecimiento_id' => $establecimiento->id,
                        'cuenta_principal_id' => $cuentaPrincipal->id
                    ]); 
            
                    if($usuarios->save())
                    {
                        // se agrega el sector y los modelos

                        // if ($modelos->count() > 0) {
                        //     if (is_null($modeloSector)) {
                              
                        //         foreach ($modelos as $key => $value) {
                        //           $insert = \DB::table('modelos_sector')->insert( 
                        //               ['modelo_id' => $value->id, 'sector_id' => $sector]
                        //           );
                        //         }
                        //     }
                        // }
                        $credenciales = 
                        [
                            'correo' => $correo,
                            'password' => $password
                        ];
                        
                        if(Auth::attempt($credenciales))
                        {
                            $usuario = $this->usuarios->where('id','=',$usuarios->id)->first();
                            \Mail::to($usuario->correo)->send(new MailCrearCuenta($usuario));

                            return $this->FinalizarRetorno(
                                200,
                                $this->MensajeRetorno('El usuario',200)
                            );
                        }
                    }
                    else
                    {
                        return $this->FinalizarRetorno(
                            400,
                            $this->MensajeRetorno('el usuario',400)
                        );
                    }
                }
            }
        }

    }
}
