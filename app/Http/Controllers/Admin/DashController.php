<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Empresa;
use App\Http\Models\Establecimiento;
use App\Http\Models\ListaChequeo;
use App\Http\Models\ListaChequeoEjecutadas;
use App\Http\Models\ListaChequeoEjecutadasRespuestas;

use Carbon\Carbon;

class DashController extends Controller
{
    protected  $listaEjecutada,$empresa,$establecimiento,$ejecutadasRespuesta,$estadosDisponibles=array('Proceso' => 0, 'Terminadas' => 0, 'Canceladas' => 0);
    public function __construct(
        Empresa $empresa,
        Establecimiento $establecimiento,
        ListaChequeo $listaChequeo,
        ListaChequeoEjecutadas $listaEjecutada,
        ListaChequeoEjecutadasRespuestas $ejecutadasRespuesta
    )
    {
        $this->empresa = $empresa;
        $this->establecimiento = $establecimiento;
        $this->listaChequeo = $listaChequeo;
        $this->listaEjecutada = $listaEjecutada;
        $this->ejecutadasRespuesta = $ejecutadasRespuesta;

        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function Index()
    {
        return view('Admin.dashboard');
    }

    public function DatosSecciones(Request $request)
    {
        $objetoRecibido = $request->get('objetoEnviar');
        $paginacion = $objetoRecibido['pagina'];
        $desde = '';
        $hasta = '';
        
        switch ($objetoRecibido['serachRealizada']) 
        {
            case 1: // ESTE MES
                $desde = '';
                $hasta = '';
                break;

            case 2: // HOY
                $con = Carbon::now();
                $desde = $con->format('Y-m-d').' 00:00:00';
                $hasta = $con->format('Y-m-d').' 23:59:59';
                break;

            case 3: // RANGO DE MESES
                $desde = Carbon::createFromFormat('d/m/Y', $objetoRecibido['desde']);
                $hasta = Carbon::createFromFormat('d/m/Y', $objetoRecibido['hasta']);
                $desde = $desde->format('Y-m-d').' 00:00:00';
                $hasta = $hasta->format('Y-m-d').' 23:59:59';
                break;
            
            default:
                break;
        }

        if($desde == '' || $hasta == '')
        {
            $con = Carbon::now();
            $monthStart = $con->startOfMonth()->format('Y-m-d');
            $monthEnd = $con->endOfMonth()->format('Y-m-d');

            $desde = $monthStart . ' 00:00:00';
            $hasta = $monthEnd . ' 23:59:59';
        }
        
        $arrayPrimeraSeccion = $this->FuncionParaLaPrimeraSeccion($desde,$hasta);
        $arraySegundaSeccion = $this->FuncionParaLaSegundaSeccion($desde,$hasta,$paginacion);
        // FIN - SECCIÓN 1
        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos ',202),
            array(
                'datosSeccionUno' => $arrayPrimeraSeccion,
                'datosSeccionDos' => $arraySegundaSeccion
             )
        );
    }

    public function FuncionParaLaPrimeraSeccion($desde,$hasta)
    {
        // ESTADOS PARA MOSTRAR PRIMERA SESIÓN
        $listadoEstados = $this->listaEjecutada
        ->select(
            'lista_chequeo_ejecutadas.estado AS ID_ESTADO',
            \DB::raw('(
                CASE
                    WHEN lista_chequeo_ejecutadas.estado = 0 THEN "Canceladas"
                    WHEN lista_chequeo_ejecutadas.estado = 1 THEN "Proceso"
                    WHEN lista_chequeo_ejecutadas.estado = 2 THEN "Terminadas"
                    ELSE "Sin estado"
                END
            ) AS ESTADO'),
            \DB::raw('COUNT(*) AS CANTIDAD')

        )
        ->Join('usuario AS usu','usu.id','=','lista_chequeo_ejecutadas.usuario_id')
        ->Join('establecimiento AS esta','esta.id','=','usu.establecimiento_id')
        ->Join('empresa AS empe','empe.id','=','esta.empresa_id')
        ->whereBetween('lista_chequeo_ejecutadas.fecha_realizacion', [$desde, $hasta])
        ->groupBy('lista_chequeo_ejecutadas.estado')
        ->orderBy('lista_chequeo_ejecutadas.estado','ASC')
        ->where('lista_chequeo_ejecutadas.estado', '=', 2);

        // PLANES DE ACCIÓN PARA SECCIÓN 1 

        $planDeAccionGenerados = $this->ejecutadasRespuesta
        ->select(
            \DB::raw('COUNT(*) AS plan_accion_generado')
        )
        ->leftJoin('lista_chequeo_ejecutadas AS lce','lce.id','=','lista_chequeo_ejec_respuestas.lista_chequeo_ejec_id')
        ->Join('lista_chequeo AS lc','lc.id','=','lce.lista_chequeo_id')
        ->Join('usuario AS usu','usu.id','=','lce.usuario_id')
        ->Join('establecimiento AS esta','esta.id','=','usu.establecimiento_id')
        ->Join('empresa AS empe','empe.id','=','esta.empresa_id')
        ->leftJoin('categoria AS cat','cat.id','=','lista_chequeo_ejec_respuestas.categoria_id')
        ->leftJoin('respuesta AS res','res.id','=','lista_chequeo_ejec_respuestas.respuesta_id')
        ->leftJoin('pregunta AS pre','pre.id','=','lista_chequeo_ejec_respuestas.pregunta_id')
        ->Join('plan_accion AS paa','paa.respuesta_id','=','res.id')
        ->Join('lista_chequeo_ejec_opciones AS lceo','lceo.lista_chequeo_ejec_respuestas_id','=','lista_chequeo_ejec_respuestas.id')
        ->leftJoin('lista_chequeo_ejec_planaccion AS lcep','lcep.lista_chequeo_ejec_opciones','=','lceo.id')
        ->whereBetween('lce.fecha_realizacion', [$desde, $hasta]);

        //SECCIÓN 1
        switch (auth()->user()->perfil_id) {
            case 1: // ADMINISTRADOR
                $listadoEstados = $listadoEstados->where('usu.id','=',auth()->user()->id);
                $planDeAccionGenerados = $planDeAccionGenerados->where('usu.id','=',auth()->user()->id);
                break;

            case 2: // COLABORADOR
                //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                $esResponsableEmpresa = $this->FuncionParaSaberSiEsResponsableEmpresa(auth()->user()->id);

                if(!is_null($esResponsableEmpresa))
                {
                    $listadoEstados = $listadoEstados->where('empe.id','=',$esResponsableEmpresa->id);
                    $planDeAccionGenerados = $planDeAccionGenerados->where('empe.id','=',$esResponsableEmpresa->id);
                }

                //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                $esResponsableEstablecimiento = $this->FuncionParaSaberSiEsResponsableEstablecimiento(auth()->user()->id);

                if(!is_null($esResponsableEstablecimiento))
                {
                    $listadoEstados = $listadoEstados->where('esta.id','=',$esResponsableEstablecimiento->id);
                    $planDeAccionGenerados = $planDeAccionGenerados->where('esta.id','=',$esResponsableEstablecimiento->id);
                }
                    

                if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                {
                    $listadoEstados = $listadoEstados->where('usu.id','=',auth()->user()->id);
                    $planDeAccionGenerados = $planDeAccionGenerados->where('usu.id','=',auth()->user()->id);
                }

                break;
            
            default:

                break;
        };
        
        $listadoEstados = $listadoEstados->get();
        $planDeAccionGenerados = $planDeAccionGenerados->first();

        foreach ($listadoEstados as $key => $itemEstados) 
        {
            $this->estadosDisponibles[$itemEstados->ESTADO] = $itemEstados->CANTIDAD;
        }

        $this->estadosDisponibles['planes_accion'] = $planDeAccionGenerados->plan_accion_generado;

        return $this->estadosDisponibles;

    }

        public function FuncionParaLaSegundaSeccion($desde,$hasta,$paginacion)
    {
        $cantidadRegistros = 10;
        $resultadoLimit = $this->CalculoPaginacion($paginacion,$cantidadRegistros);
    
        $desdePaginacion = $resultadoLimit['desde'];
        $hastaPaginacion = $resultadoLimit['hasta'];

        $completarQuery = '';
        $valores = ['desde' => $desde,'hasta' => $hasta,'desdePaginacion' => $desdePaginacion,'hastaPaginacion' => $hastaPaginacion];

        switch (auth()->user()->perfil_id) {
            case 1: // ADMINISTRADOR
                $completarQuery = ' AND usu.id = :idUsuario';
                $valores['idUsuario'] = auth()->user()->id;
                break;

            case 2: // COLABORADOR
                //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                $esResponsableEmpresa = $this->FuncionParaSaberSiEsResponsableEmpresa(auth()->user()->id);

                if(!is_null($esResponsableEmpresa))
                {
                    $completarQuery = ' AND empe.id = :idEmpresa';
                    $valores['idEmpresa'] = $esResponsableEmpresa->id;
                }

                //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                $esResponsableEstablecimiento = $this->FuncionParaSaberSiEsResponsableEstablecimiento(auth()->user()->id);

                if(!is_null($esResponsableEstablecimiento))
                {
                    $completarQuery = ' AND esta.id = :idEstablecimiento';
                    $valores['idEstablecimiento'] = $esResponsableEstablecimiento->id;
                }
                    

                if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                {
                    $completarQuery = ' AND usu.id = :idUsuario';
                    $valores['idUsuario'] = auth()->user()->id;
                }

                break;
            
            default:

                break;
        };
        \DB::statement("SET lc_time_names = 'es_ES'");
        $datos = \DB::select("SELECT 
        lc.nombre AS LISTA_DE_CHEQUEO,
        lce.id AS ID_LISTA_EJECUTADA,
        (CASE
            WHEN lc.entidad_evaluada=1 THEN (SELECT semp.nombre FROM empresa semp WHERE semp.id=lce.evaluado_id)
            
            
            WHEN lc.entidad_evaluada=2 THEN (SELECT semp.nombre FROM establecimiento sest 
                                            INNER JOIN empresa semp ON semp.id = sest.empresa_id WHERE sest.id=lce.evaluado_id)
                                            
            WHEN lc.entidad_evaluada=3 THEN (SELECT semp.nombre FROM usuario susu
                                            INNER JOIN establecimiento sesta ON sesta.id = susu.establecimiento_id
                                            INNER JOIN empresa semp ON semp.id = sesta.empresa_id
                                            WHERE susu.id=lce.evaluado_id)
                                            
            WHEN lc.entidad_evaluada=4 THEN (SELECT ars.nombre FROM areas ars WHERE ars.id=lce.evaluado_id)
            WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.nombre FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
            ELSE 'Error'
        END) AS EMPRESA,
        DATE_FORMAT(lce.fecha_realizacion,'%d de %M %Y') AS FECHA_REALIZACION,
        (CASE
            WHEN lc.entidad_evaluada = 1 THEN 'EMPRESA'
            WHEN lc.entidad_evaluada = 2 THEN 'ESTABLECIMIENTO'
            WHEN lc.entidad_evaluada = 3 THEN 'USUARIO'
            WHEN lc.entidad_evaluada = 4 THEN 'AREA'
            WHEN lc.entidad_evaluada = 5 THEN 'EQUIPO'
            ELSE 'Ninguno'
        END) AS ENTIDAD,
        (CASE
            WHEN lc.entidad_evaluada=1 THEN (SELECT semp.nombre FROM empresa semp WHERE semp.id=lce.evaluado_id)
            WHEN lc.entidad_evaluada=2 THEN (SELECT sest.nombre FROM establecimiento sest WHERE sest.id=lce.evaluado_id)
            WHEN lc.entidad_evaluada=3 THEN (SELECT susu.nombre_completo FROM usuario susu WHERE susu.id=lce.evaluado_id)
            WHEN lc.entidad_evaluada=4 THEN (SELECT ars.nombre FROM areas ars WHERE ars.id=lce.evaluado_id)
            WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.nombre FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
            ELSE 'Error'
        END) as EVALUADO,
        (
            SELECT
            COUNT(*)
            FROM lista_chequeo_ejec_respuestas lcers
            LEFT JOIN lista_chequeo_ejecutadas lces ON lces.id = lcers.lista_chequeo_ejec_id
            INNER JOIN lista_chequeo lcs ON lcs.id = lces.lista_chequeo_id
            INNER JOIN usuario usus ON usus.id = lces.usuario_id
            INNER JOIN establecimiento estas ON estas.id=usus.establecimiento_id
            INNER JOIN empresa empes on empes.id=estas.empresa_id
            INNER JOIN cuenta_principal cps ON cps.id = usus.cuenta_principal_id
            LEFT JOIN categoria cs ON cs.id = lcers.categoria_id
            LEFT JOIN respuesta rs ON rs.id = lcers.respuesta_id
            LEFT JOIN pregunta ps ON ps.id = lcers.pregunta_id
            INNER JOIN plan_accion paus ON paus.respuesta_id = rs.id
            INNER JOIN lista_chequeo_ejec_opciones lceos ON lceos.lista_chequeo_ejec_respuestas_id = lcers.id
            LEFT JOIN lista_chequeo_ejec_planaccion lceps ON lceps.lista_chequeo_ejec_opciones = lceos.id
            WHERE lces.id=lce.id
        ) AS HALLAZGOS
        FROM lista_chequeo_ejecutadas lce
        INNER JOIN lista_chequeo lc ON lc.id=lce.lista_chequeo_id
        INNER JOIN usuario usu ON usu.id=lce.usuario_id
        INNER JOIN establecimiento esta ON esta.id=usu.establecimiento_id
        INNER JOIN empresa empe on empe.id=esta.empresa_id
        WHERE lce.estado = 2
        AND lce.fecha_realizacion BETWEEN :desde AND :hasta
        $completarQuery
        ORDER BY lce.fecha_realizacion DESC LIMIT :desdePaginacion,:hastaPaginacion;",$valores);

        foreach ($datos as $key => $itemDatos) 
        {
            $datos[$key]->ResultadoFinal = $this->CalcularResultadoFinalPorIdListaEjecutada($itemDatos->ID_LISTA_EJECUTADA);
        }

        return $datos;
    }

    public function CalcularResultadoFinalPorIdListaEjecutada($idListaEjecutada)
    {
        $resultadoFinal = 0;

        $consulta = \DB::select("SELECT
        lcer.categoria_id,
        cat.nombre as categoria,
        lcer.no_aplica,
        pre.nombre as pregunta,
        cat.ponderado AS cat_ponderado,
        pre.ponderado AS pre_ponderado,
        res.valor_personalizado AS respuesta,
        res.ponderado AS res_ponderado,
        SUM(IF((TRUNCATE(((pre.ponderado*res.ponderado)/100),2)) IS NULL,pre.ponderado, (TRUNCATE(((pre.ponderado*res.ponderado)/100),2)))) AS porc_cat
        FROM lista_chequeo_ejec_respuestas lcer
        INNER JOIN lista_chequeo_ejecutadas lce ON lce.id=lcer.lista_chequeo_ejec_id
        LEFT JOIN respuesta res ON res.id=lcer.respuesta_id
        INNER JOIN pregunta pre ON pre.id=lcer.pregunta_id
        INNER JOIN categoria cat ON cat.id=pre.categoria_id
        WHERE  lcer.lista_chequeo_ejec_id=:idListaEjecutada
        ORDER BY cat.id",['idListaEjecutada' => $idListaEjecutada]);

        $resultadoFinal = (COUNT($consulta) == 0 ? 0 : $consulta[0]->porc_cat);

        return $resultadoFinal;
        // foreach ($consulta as $key => $itemCategoria) 
        // {
        //     $resultadoFinal += floatval($itemCategoria->porc_cat);
        // }

        // return number_format($resultadoFinal,2);
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
}
