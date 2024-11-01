<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Pais;
use App\Http\Models\Establecimiento;
use App\Http\Models\Departamento;
use App\Http\Models\Ciudad;
use App\Http\Models\Empresa;
use App\Http\Models\Usuario;
use App\Http\Models\Zonas;

class AdministracionEstablecimientosController extends Controller
{
    protected $pais,$establecimiento,$departamento,$ciudad,$empresa,$usuario, $zonas;
    public function __construct(Pais $pais, Establecimiento $establecimiento, Departamento $departamento, Ciudad $ciudad, Empresa $empresa, Usuario $usuario, Zonas $zonas)
    {
        $this->pais = $pais;
        $this->establecimiento = $establecimiento;
        $this->departamento = $departamento;
        $this->ciudad = $ciudad;
        $this->empresa = $empresa;
        $this->usuario = $usuario;
        $this->zonas = $zonas;
        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function Index()
    {
        $cuentaPrincipal =  auth()->user()->cuenta_principal_id;
        $paises = $this->pais->select('id','indicativo','nombre',
            \DB::raw('CONCAT(indicativo," (",nombre,")") AS CONCATENACION')
        )
        ->orderBy('nombre','ASC')
        ->where('estado','=',1)
        ->get();

        $establecimientos = $this->establecimiento
        ->select('establecimiento.*')
        ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
        ->orderBy('establecimiento.nombre','ASC')
        ->where('establecimiento.estado','=',1);

        $ciudades = $this->ciudad->select('ciudad.id','ciudad.nombre')
        ->Join('establecimiento AS e','e.ciudad_id','=','ciudad.id')
        ->orderBy('ciudad.nombre','ASC')
        ->groupBy('ciudad.nombre')
        ->where('ciudad.estado','=',1)
        ->get();

        $zonas = $this->zonas->where('estado','=',1)
        ->where('cuenta_principal_id', '=', $cuentaPrincipal)->get();

        $usuariosResponsables = $this->usuario->select('usuario.id','usuario.nombre_completo')
        ->Join('establecimiento AS e','e.usuario_id','=','usuario.id')
        ->orderBy('usuario.nombre_completo','ASC')
        ->groupBy('usuario.nombre_completo');

        $usuariosPopUp = $this->usuario->select('usuario.id','usuario.nombre_completo')
        ->where([
            ['usuario.estado','=', 1],
            ['usuario.cuenta_principal_id','=', auth()->user()->cuenta_principal_id]            
        ])
        ->orderBy('usuario.nombre_completo','ASC')
        ->get();
            
        $empresas = $this->empresa->select(
            'empresa.id',
            'empresa.identificacion',
            'empresa.nombre',
            'empresa.direccion'
        );

        switch (auth()->user()->perfil_id) {
            case 1: // ADMINISTRADOR
                $empresas = $empresas->where([
                    ['empresa.cuenta_principal_id','=',auth()->user()->cuenta_principal_id]
                ])
                ->get();

                $usuariosResponsables = $usuariosResponsables->where([
                    ['usuario.estado','=', 1],
                    ['usuario.cuenta_principal_id','=', auth()->user()->cuenta_principal_id]            
                ])->get();

                $establecimientos = $establecimientos->where([
                    ['em.cuenta_principal_id','=',auth()->user()->cuenta_principal_id]
                ])->get();

                break;

            case 2: // COLABORADOR
                //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                $esResponsableEmpresa = $this->FuncionParaSaberSiEsResponsableEmpresa(auth()->user()->id);

                if(!is_null($esResponsableEmpresa))
                {
                    $empresas = $empresas->where([
                        ['empresa.id','=',$esResponsableEmpresa->id]
                    ])
                    ->get();


                    $usuariosResponsables = $usuariosResponsables
                    ->Join('empresa AS em','em.id','=','e.empresa_id')
                    ->where([
                        ['usuario.estado','=', 1],
                        ['em.id','=', $esResponsableEmpresa->id]            
                    ])->get();

                    $establecimientos = $establecimientos->where([
                        ['em.id','=', $esResponsableEmpresa->id]
                    ])->get();
                }

                //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                $esResponsableEstablecimiento = $this->FuncionParaSaberSiEsResponsableEstablecimiento(auth()->user()->id);

                if(!is_null($esResponsableEstablecimiento))
                {
                    $empresas = [];

                    $usuariosResponsables = $usuariosResponsables
                    ->where([
                        ['usuario.estado','=', 1],
                        ['e.id','=', $esResponsableEstablecimiento->id]            
                    ])->get();

                    $establecimientos = $establecimientos->where([
                        ['establecimiento.id','=', $esResponsableEstablecimiento->id]
                    ])->get();
                }
                
                if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                    return redirect('/dashboard');

                break;
            
            default:

                break;
        };

        return view('Admin.administracion_establecimientos',
            compact('paises','establecimientos','empresas','usuariosResponsables','ciudades','zonas','usuariosPopUp')
        );
    }

    public function CrearEstablecimiento(Request $request)
    {
        $nombreEstablecimiento = $request->get('nombreEstablecimiento');
        $codigo = ($request->get('codigo') == '' ? NULL : $request->get('codigo'));
        $correo = ($request->get('correo') == '' ? NULL : $request->get('correo'));
        $direccion = ($request->get('direccion') == '' ? NULL : $request->get('direccion'));
        $telefono = ($request->get('telefono') == '' ? NULL : $request->get('telefono'));
        $idPais = ($request->get('idPais') == 0 ? NULL : $request->get('idPais'));
        $idDepartamento = ($request->get('idDepartamento') == 0 ? NULL : $request->get('idDepartamento'));
        $idCiudad = ($request->get('idCiudad') == 0 ? NULL : $request->get('idCiudad'));
        $idEmpresa = $request->get('idEmpresa');
        $idZona = ($request->get('idZona') == 0 ? NULL : $request->get('idZona'));
        $idResponsable = ($request->get('idResponsable') == 0 ? NULL : $request->get('idResponsable'));

        if(!is_null($codigo))
        {
            if ($this->establecimiento
            ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
            ->where([['establecimiento.codigo', '=', $codigo],['em.cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]])->exists()) 
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'El código usado ya existe')
                );
            }
        }
        
        if(!is_null($correo))
        {
            if ($this->establecimiento
            ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
            ->where([['establecimiento.correo', '=', $correo],['em.cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]])->exists()) 
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'El correo electrónico usado ya existe')
                );
            }
        }

        $establecimiento = new $this->establecimiento;

        $establecimiento->fill(
        [
            'nombre' => $nombreEstablecimiento, 
            'codigo' => $codigo,
            'correo' => $correo,
            'telefono' => $telefono,
            'direccion' => $direccion, 
            'ciudad_id' => $idCiudad,
            'empresa_id' => $idEmpresa,
            'zona_id' => $idZona,
            'usuario_id' => $idResponsable
        ]);

        if($establecimiento->save())
        {
            if(!is_null($idResponsable))
            {
                if($this->establecimiento->where('usuario_id','=',$idResponsable)->exists())
                    $this->establecimiento->where('usuario_id','=',$idResponsable)->update(['usuario_id' => NULL]);

                $this->establecimiento->where('id','=',$establecimiento->id)->update(['usuario_id' => $idResponsable]);
            }

            return $this->FinalizarRetorno(
                200,
                $this->MensajeRetorno('El establecimiento ',200)
            );
        }
    }

    public function ConsultarEstablecimientos(Request $request)
    {
        $idCuentaPrincipal = $request->get('idCuentaPrincipal');
        
        $paginacion = $request->get('paginacion');
        $filtros = json_decode($request->get('arrayFiltros'));

        $establecimientos = $this->FuncionTraerEstablecimientosPorPaginacion($idCuentaPrincipal,$paginacion,$filtros);

        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos',202),
            $establecimientos
        );
    }

    public function ActualizarEstadoEstablecimiento(Request $request)
    {
        $idEstablecimiento = $request->get('idEstablecimiento');
        $estadoActual = $request->get('estadoActual');

        $estadoCambiado = 0;
        if($estadoActual == 0)
            $estadoCambiado = 1;
        else if($estadoActual == 1)
            $estadoCambiado = 0;
        
        $respuestaUpdate = $this->establecimiento->where('id','=',$idEstablecimiento)
        ->update(
        [
            'estado' => $estadoCambiado
        ]);

        if($respuestaUpdate)
        {
            return $this->FinalizarRetorno(
                201,
                $this->MensajeRetorno('La empresa',201),
                $estadoCambiado
            );
        }
    }

    public function EliminarEstablecimiento(Request $request)
    {
        $idEstablecimiento = $request->get('idEstablecimiento');
        $respuesta = '';

        $cantidadEstablecimientos = $this->usuario->select(
            \DB::raw('count(usuario.establecimiento_id) as cantidad')
        )->where('usuario.establecimiento_id', '=', $idEstablecimiento)->get();
        
        if($cantidadEstablecimientos[0]->cantidad == 0){
            $msg = 'Se ha eliminado la zona.';
            $status = 201;
            $respuesta = $this->establecimiento->where('id', $idEstablecimiento)->delete();
            if($respuesta)
            {
                return $this->FinalizarRetorno(
                    203,
                    $this->MensajeRetorno('El establecimiento ',203)
                );  
            }
            else
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'El establecimiento no pudo eliminarse')
                ); 
            }
        }else{
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'No se puede eliminar el establecimiento debido a que tiene colaboradores asignados.')
            ); 
        }
       
        
    }

    public function ConsultaEditarEstablecimiento(Request $request)
    {
        $idEstablecimiento = $request->get('idEstablecimiento');
        $establecimiento = $this->establecimiento->select(
            'establecimiento.id',
            'establecimiento.nombre',
            \DB::raw('IF(establecimiento.codigo IS NULL,"",establecimiento.codigo) AS codigo'),
            \DB::raw('IF(establecimiento.correo IS NULL,"",establecimiento.correo) AS correo'),
            \DB::raw('IF(establecimiento.telefono IS NULL,"",establecimiento.telefono) AS TELEFONO'),
            \DB::raw('IF(establecimiento.direccion IS NULL,"",establecimiento.direccion) AS direccion'),
            'establecimiento.estado',
            \DB::raw('IF(p.id IS NULL,"0",p.id) AS PAIS_ID'),
            \DB::raw('IF(d.id IS NULL,"0",d.id) AS DEPARTAMENTO_ID'),
            \DB::raw('IF(c.id IS NULL,"0",c.id) AS CIUDAD_ID'),
            'em.id AS EMPRESA',
            \DB::raw('(IF(zona.nombre IS NULL, "Sin zona", zona.id)) AS ZONA'),
            \DB::raw('IF(u.id IS NULL,"0",u.id) AS RESPONSABLE')
        )
        ->leftJoin('ciudad AS c','c.id','=','establecimiento.ciudad_id')
        ->leftJoin('departamento AS d','d.id','=','c.departamento_id')
        ->leftJoin('pais AS p','p.id','=','d.pais_id')
        ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
        ->leftJoin('zonas AS zona','zona.id','=','establecimiento.zona_id')
        ->leftJoin('usuario AS u','u.id','=','establecimiento.usuario_id')
        ->where('establecimiento.id','=',$idEstablecimiento)->first();
        
        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos',202),
            $establecimiento
        );
    }

    public function EditarEstablecimiento(Request $request)
    {
        $idEstablecimiento = $request->get('idEstablecimiento');
        $nombreEstablecimiento = $request->get('nombreEstablecimiento');
        $codigo = ($request->get('codigo') == '' ? NULL : $request->get('codigo'));
        $correo = ($request->get('correo') == '' ? NULL : $request->get('correo'));
        $direccion = ($request->get('direccion') == '' ? NULL : $request->get('direccion'));
        $telefono = ($request->get('telefono') == '' ? NULL : $request->get('telefono'));
        $idPais = ($request->get('idPais') == 0 ? NULL : $request->get('idPais'));
        $idDepartamento = ($request->get('idDepartamento') == 0 ? NULL : $request->get('idDepartamento'));
        $idCiudad = ($request->get('idCiudad') == 0 ? NULL : $request->get('idCiudad'));
        $idEmpresa = $request->get('idEmpresa');
        $idZona = ($request->get('idZona') == 0 ? NULL : $request->get('idZona'));
        $idResponsable = ($request->get('idResponsable') == 0 ? NULL : $request->get('idResponsable'));

        if(!is_null($codigo))
        {
            if ($this->establecimiento
            ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
            ->where([
                ['establecimiento.codigo', '=', $codigo],
                ['establecimiento.id', '!=', $idEstablecimiento],
                ['em.cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]
            ])->exists()) 
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'El código usado ya existe')
                );
            }
        }
        
        if(!is_null($correo))
        {
            if ($this->establecimiento
            ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
            ->where([
                ['establecimiento.correo', '=', $correo],
                ['establecimiento.id', '!=', $idEstablecimiento],
                ['em.cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]
            ])->exists()) 
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406,'El correo usado ya existe')
                );
            }
        }
        
        if(!is_null($idResponsable))
        {
            if($this->establecimiento->where('usuario_id','=', $idResponsable)->exists())
                $this->establecimiento->where('usuario_id','=',$idResponsable)->update(['usuario_id' => NULL]);
        }
        
        $respuestaUpdate = $this->establecimiento->where('id','=',$idEstablecimiento)
        ->update(
        [
            'nombre' => $nombreEstablecimiento, 
            'codigo' => $codigo,
            'correo' => $correo,
            'telefono' => $telefono,
            'direccion' => $direccion, 
            'ciudad_id' => $idCiudad,
            'empresa_id' => $idEmpresa,
            'zona_id' => $idZona,
            'usuario_id' => $idResponsable
        ]);

        if($respuestaUpdate)
        {
            $establecimientoActualizado = $this->FuncionTraerEstablecimientoPorId($idEstablecimiento);
            return $this->FinalizarRetorno(
                201,
                $this->MensajeRetorno('El establecimiento ',201),
                $establecimientoActualizado
            );
        }
    }

    public function FuncionTraerEstablecimientoPorId($idEstablecimiento)
    {
        $establecimiento = $this->establecimiento->select(
            'establecimiento.id',
            'establecimiento.nombre',
            \DB::raw('IF(establecimiento.codigo IS NULL,"Sin código",establecimiento.codigo) AS codigo'),
            \DB::raw('IF(establecimiento.correo IS NULL,"Sin correo electrónico",establecimiento.correo) AS correo'),
            \DB::raw('IF(establecimiento.telefono IS NULL,"Sin teléfono",establecimiento.telefono) AS TELEFONO'),
            \DB::raw('IF(establecimiento.direccion IS NULL,"Sin dirección",establecimiento.direccion) AS direccion'),
            'establecimiento.estado',
            \DB::raw('IF(c.nombre IS NULL,"Sin ciudad",CONCAT(c.nombre,", ",p.nombre)) AS CIUDAD'),
            'em.nombre AS EMPRESA',
            \DB::raw('(IF(zona.nombre IS NULL, "Sin zona", zona.nombre)) AS ZONA'),
            \DB::raw('IF(u.nombre_completo IS NULL,"Sin responsable",u.nombre_completo) AS RESPONSABLE'),
            \DB::raw('(SELECT count(su.establecimiento_id) as cantidad_usuarios FROM usuario su WHERE su.establecimiento_id = establecimiento.id) as colaboradores')
        )
        ->leftJoin('ciudad AS c','c.id','=','establecimiento.ciudad_id')
        ->leftJoin('departamento AS d','d.id','=','c.departamento_id')
        ->leftJoin('pais AS p','p.id','=','d.pais_id')
        ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
        ->leftJoin('zonas AS zona','zona.id','=','establecimiento.zona_id')
        ->leftJoin('usuario AS u','u.id','=','establecimiento.usuario_id')
        ->where('establecimiento.id','=',$idEstablecimiento)->first();
        
        return $establecimiento;
    }

    public function TraerEstablecimientosPaginacion(Request $request)
    {
        $idCuentaPrincipal = $request->get('idCuentaPrincipal');
        $paginacion = $request->get('paginacion');
        $filtros = json_decode($request->get('arrayFiltros'));

        $establecimientos = $this->FuncionTraerEstablecimientosPorPaginacion($idCuentaPrincipal,$paginacion,$filtros);

        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos',202),
            $establecimientos
        );
    }

    public function FuncionTraerEstablecimientosPorPaginacion($idCuentaPrincipal,$paginacion=1,$filtros=[])
    {
        $resultadoLimit = $this->CalculoPaginacion($paginacion);
    
        $desde = $resultadoLimit['desde'];
        $hasta = $resultadoLimit['hasta'];
        $cantidadRegistros = 9;
        $filtro_array = [];

        foreach ($filtros as $key => $filtro) 
        {

            switch ($key) {
                case 'filtro_nombre_establecimiento':
                    if($filtro != '')
                        array_push($filtro_array,['establecimiento.nombre', '=', $filtro]);
                    break;

                case 'filtro_codigo':
                    if($filtro != '')
                        array_push($filtro_array,['establecimiento.codigo', '=', $filtro]);
                    break;

                case 'filtro_correo':
                    if($filtro != '')
                        array_push($filtro_array,['establecimiento.correo', '=', $filtro]);
                    break;

                case 'filtro_ciudad':
                    if($filtro != '')
                        array_push($filtro_array,['c.id', '=', $filtro]);
                    break;

                case 'filtro_empresa':
                    if($filtro != '')
                        array_push($filtro_array,['em.id', '=', $filtro]);
                    break;

                case 'filtro_zona':
                        if($filtro != '')
                            array_push($filtro_array,['zona.id', '=', $filtro]);
                    break;

                case 'filtro_responsable':
                    if($filtro != '')
                        array_push($filtro_array,['u.id', '=', $filtro]);
                    break;

                default:
                    
                    break;
            }
            
        }

        $establecimientos = $this->establecimiento->select(
            'establecimiento.id',
            'establecimiento.nombre',
            \DB::raw('IF(establecimiento.codigo IS NULL,"Sin código",establecimiento.codigo) AS codigo'),
            \DB::raw('IF(establecimiento.correo IS NULL,"Sin correo electrónico",establecimiento.correo) AS correo'),
            \DB::raw('IF(establecimiento.telefono IS NULL,"Sin teléfono",establecimiento.telefono) AS TELEFONO'),
            \DB::raw('IF(establecimiento.direccion IS NULL,"Sin teléfono",establecimiento.direccion) AS direccion'),
            'establecimiento.estado',
            \DB::raw('IF(c.nombre IS NULL,"Sin ciudad",CONCAT(c.nombre,", ",p.nombre)) AS CIUDAD'),
            'em.nombre AS EMPRESA',
            \DB::raw('(IF(zona.nombre IS NULL, "Sin zona", zona.nombre)) AS ZONA'),
            \DB::raw('IF(u.nombre_completo IS NULL,"Sin responsable",u.nombre_completo) AS RESPONSABLE'),
            \DB::raw('(SELECT count(usuario.establecimiento_id) as cantidad_usuarios FROM usuario WHERE usuario.establecimiento_id = establecimiento.id) as colaboradores')
        )
        ->leftJoin('ciudad AS c','c.id','=','establecimiento.ciudad_id')
        ->leftJoin('departamento AS d','d.id','=','c.departamento_id')
        ->leftJoin('pais AS p','p.id','=','d.pais_id')
        ->Join('empresa AS em','em.id','=','establecimiento.empresa_id')
        ->leftJoin('zonas AS zona','zona.id','=','establecimiento.zona_id')
        ->leftJoin('usuario AS u','u.id','=','establecimiento.usuario_id');

        switch (auth()->user()->perfil_id) {
            case 1: // ADMINISTRADOR
                $establecimientos = $establecimientos->where('em.cuenta_principal_id','=',$idCuentaPrincipal);
                break;

            case 2: // COLABORADOR
                //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                $esResponsableEmpresa = $this->FuncionParaSaberSiEsResponsableEmpresa(auth()->user()->id);

                if(!is_null($esResponsableEmpresa))
                    $establecimientos = $establecimientos->where('em.id','=',$esResponsableEmpresa->id);

                //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                $esResponsableEstablecimiento = $this->FuncionParaSaberSiEsResponsableEstablecimiento(auth()->user()->id);

                if(!is_null($esResponsableEstablecimiento))
                    $establecimientos = $establecimientos->where('establecimiento.id','=',$esResponsableEstablecimiento->id);

                break;
            
            default:

                break;
        };

        if(COUNT($filtro_array) != 0)
        {
            $establecimientos = $establecimientos->where(function($query) use ($filtro_array)
            {
                foreach ($filtro_array as $keys => $oW) 
                {
                    $query->where($oW[0], '=', $oW[2]);
                }

                return $query;
            });
            
        }

        $establecimientos = $establecimientos->skip($desde)->take($hasta)->get();

        return $establecimientos;
    }
    
    public function FuncionParaSaberSiEsResponsableEmpresa($idUsuario)
    {
        $esResponsableEmpresa = $this->empresa->where('usuario_id','=',$idUsuario)->first();

        return $esResponsableEmpresa;
    }

    public function FuncionParaSaberSiEsResponsableEstablecimiento($idUsuario)
    {
        $esResponsableEstablecimiento = $this->establecimiento->where('usuario_id','=',$idUsuario)->first();

        return $esResponsableEstablecimiento;
    }

    public function consultaColaboradores(Request $request){
        $idEstablecimiento = $request->idEstablecimiento;
        
        $colaboradores = $this->usuario->select(
            'usuario.nombre_completo as nombre',
            'perfil.nombre as perfil',
            \DB::raw('IF(cargo.nombre IS NULL, "Sin cargo", cargo.nombre) as cargo')
        )->join('perfil', 'perfil.id', 'usuario.perfil_id')
        ->leftJoin('cargo', 'cargo.id', 'usuario.cargo_id')
        ->where('usuario.establecimiento_id', '=', $idEstablecimiento)->paginate(5);



        return response()->json([
            'datos'=> $colaboradores, 
            'status'=>200
            ]);
    }
}