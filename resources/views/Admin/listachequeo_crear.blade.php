@extends('template.baseVertical')

@section('css')
<!--Morris Chart CSS -->
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/plugins/jquery-steps/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/mislistas/crearlistas.css') }}">
@endsection

@php
    if (is_null($datosListaChequeo->ID_EVALUADO))
    {
        $idEntidad = $datosListaChequeo->entidad_evaluada;
        $idEvaluado = 0;
    }
    else
    {
        $idEntidad = $datosListaChequeo->entidad_evaluada;
        $idEvaluado = $datosListaChequeo->ID_EVALUADO;
    }

    $idTipoPonderado = $datosListaChequeo->tipo_ponderados;

@endphp

@section('breadcrumb')
@if (is_null($datosListaChequeo->ID_EVALUADO))
    <h3 class="page-title">Creación lista</h1>
@else
    <h3 class="page-title">Edición lista</h1>
@endif

@endsection

@section('section')
<div class="contenedorAtras m-b-10 col-12">
    <a href="{{ route('List_MyList') }}" class="btn btn-primary waves-effect waves-light">Regresar</a>
</div>
<div class="row" style="overflow: auto;">
    <div class="row m-b-10 datosListas col-lg-12" idListaChequeo="{{ Request::segment(3) }}" idEntidad="{{ $idEntidad }}" idEvaluado="{{ $idEvaluado }}">
        <div class="col-12">
            <div class="col-lg-12">
                <div class="card m-b-20" style="overflow: auto">
                    <div class="card-body m-b-10">
    
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                                <table class="table table-dark" id="tablaListaEncabezado">
                                    <thead>
                                        <tr>
                                            <th colspan="4">ENCABEZADO DE LISTA DE CHEQUEO</th>
                                        </tr>
                                   
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <span class="tituloTabla">NOMBRE</span> </td>
                                            <td colspan="3"><span class="nombreLista">{{ $datosListaChequeo->nombre }}</span> <span class="badge badge-warning badge-custom clickEditar">Editar</span></td>
                                        </tr>
            
                                        <tr>
                                            <td><span class="tituloTabla">FECHA</span></td>
                                            <td>
                                                <select class="form-control select2 fecha">
                                                    <option value="1">Tomada del día de ejecución</option>
                                                    <option value="2">Ingresar manualmente</option>
                                                </select>
                                            </td>
                                            <td><span class="tituloTabla">PUBLICADO PARA</span></td>
                                            <td><span for="">Mi organización</span></td>
                                        </tr>
            
                                        <tr>
                                            <td><span class="tituloTabla">EVALUADO</span></td>
                                            <td>
                                                <select class="form-control select2 empresaSelect">
                                                    <option value="1">Empresa</option>
                                                    <option value="2">Establecimiento</option>
                                                    <option value="3">Usuario</option>
                                                    <option value="4">Áreas</option>
                                                    <option value="5">Equipos</option>
                                                </select>
                                            </td>
                                            <td><span class="tituloTabla tituloDinamicoSelect">EMPRESA EVALUADA</span></td>
                                            <td>
                                                <select class="form-control select2 empresaEvaluadaSelect">
                                                    <option value="1">Asociada al evaluador</option>
                                                    <option value="2"></option>
                                                </select>
                                            </td>
                                        </tr>
                                    
                                    </tbody>
                                </table>
            
                            </div>
                        </div>
            
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                               <div class="contenedorBotones">
                                    <button class="btn btn-primary agregarCategoria">AGREGAR CATEGORÍA</button>
                                    {{-- <button class="btn btn-success agregarPregunta" accion="0">AGREGAR PREGUNTA</button> --}}
                               </div>
                            </div>
            
                            <div class="col-lg-12 m-t-10 ContenedorCategoriaYPreguntas" >
                               
                                {{-- <div class="contenedorTodaLaCategoria">
                                        
                                </div> --}}
    
                             </div>
    
                             <div id="main_no_data" class="hidden">
                                <div class="fof">
                                        <h1>No hay información para mostrar</h1>
                                </div>
                            </div>
    
                             <div class="col-lg-12 m-t-10">
                                <div class="contenedorBotones">
                                     {{-- <button class="btn btn-dark agregarCategoria">AGREGAR CATEGORÍA</button> --}}
                                     {{-- <button class="btn btn-success agregarPregunta" accion="0">AGREGAR PREGUNTA</button> --}}
                                </div>
                             </div>
    
                             <div class="col-lg-12 m-t-10 m-b-10">
                                     <button class="col-lg-12 btn btn-primary Terminar">TERMINAR LISTA DE CHEQUEO</button>
                             </div>
                                         
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</div>


 <!--  MODAL EDITAR NOMBRE DE LISTA DE CHEQUEO -->
 <div class="modal fade bs-example-modal-lg" id="editarNombreLista" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edición nombre lista chequeo</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <form class="" id="formularioEditarListaChequeo" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Nombre: </label>
                                            <input type="text" class="form-control nombreMiListaPopUp" required placeholder="Nombre de la lista de chequeo"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light actualizarPopUp">Actualizar</button>
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarPopUp">Cancelar</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL EDITAR NOMBRE DE LISTA DE CHEQUEO - FIN --}}

<!--  MODAL CREAR CATEGORIA -->
<div class="modal fade bs-example-modal-lg" id="crearCategoriaPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 tituloCategoriaPopUp">AGREGAR CATEGORÍA</h5>
                <button type="button" class="close cancelarCategoriaPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <form class="" id="formularioCrearCategoria" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Nombre: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nombreCategoriaPopUp" required placeholder="Ingrese la categoría"/>
                                        </div>

                                        <div class="col-lg-8" style="padding-left:0px;">
                                            <div class="form-group">
                                                <label>Ponderado: <span class="requerido">*</span></label>                                            
                                                <div class="input-group col-md-0">
                                                    <input type="text" {{ $disabled }} class="form-control ponderadoPopUp input-number-limit" required placeholder="Ingrese el ponderado"/>
                                                    <span class="input-group-append">
                                                        <button class="btn btn-outline-secondary border-rigth-0 border" disabled type="button">
                                                            <i class="mdi mdi-percent" style="color: green"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label >Etiqueta: </label>
                                            <div class="col-lg-12 row" style="padding: 0;">
                                                <div class="col-lg-6">
                                                    <select class="form-control select2 selectPopUp etiquetaSelector">
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button type="button" class="btn btn-primary waves-effect m-l-5 crearEtiqueta" >Crear</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            

                                        <div class="form-group">
                                            <label>Orden: </label>
                                            <select class="form-control select2 selectPopUp ordenCategorias">
                                                <option value="final">Al final de las categorías</option>
                                                
                                                @foreach ($datosCategoria as $itemCategoria)
                                                    <option value="{{ $itemCategoria->orden_lista }}">Después de la categoría "{{ $itemCategoria->nombre }}"</option>    
                                                @endforeach
                                                
                                                <option value="principio">Al inicio de las categorías</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light guardarPopUp" accion="0">Guardar</button>
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarCategoriaPopUp">Cancelar</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREAR CATEGORIA - FIN --}}

<!--  MODAL CREAR PREGUNTA -->
<div class="modal fade bs-example-modal-xl" id="crearPreguntaPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 tituloPreguntaPopUp">AGREGAR PREGUNTA</h5>
                <button type="button" class="close cancelarPreguntaPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body cuerpoStepByStepPopUpPM">
                            
                            <form id="fomularioSteps" class="fomularioSteps">
                                <h3>Pregunta</h3>
                                <fieldset>
                                  
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <label for="pregunta" class="col-lg-5 col-form-label" style="text-align:end;">Pregunta </label>
                                                <div class="col-lg-6">
                                                    <textarea id="preguntaTextArea" placeholder="Escribe acá tú pregunta..." name="pregunta" rows="4" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-form-label" style="text-align:end;">Ponderado (%): </label>
                                                <div class="col-lg-2">
                                                    <input type="number" step="any" {{ $disabled }} class="form-control ponderadoPopUpPregunta "  required placeholder="Ponderado"/>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-form-label" style="text-align:end;">Categoría: </label>
                                                <div class="col-lg-5">
                                                    <select class="form-control select2 selectStepPopUp categoríaPopUpStep">
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row hidden">
                                                <label class="col-lg-5 col-form-label" style="text-align:end;">Ordén: </label>
                                                <div class="col-lg-5">
                                                    <select class="form-control select2 selectStepPopUp ordenPopUpStep">
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                <h3>Tipo de respuesta</h3>
                                <fieldset class="tipoRespuestaField">                   

                                </fieldset>
                                <h3>Configuración de respuesta</h3>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <p class="col-lg-12 font-13 textoEncabezadoConfiguracionRespuesta">
                                                    Puede cambiar el texto de cada respuesta (Si ó No) por el de 
                                                    su preferencia (Ejemplo: Aplica ó No aplica), de lo contrario
                                                    puede continuar
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">

                                            <div class="contenedorPersonalizacionTipoRespuesta">
                                                
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-form-label" style="text-align:end;"> </label>
                                                <div class="col-lg-7">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="checkBoxPermitirNA" checked>
                                                            <label class="custom-control-label font-13 labelCheck" for="checkBoxPermitirNA">Permitir <b>NO APLICA</b> (Desmarcar para convertirla en <b>OBLIGATORIA</b> )</label>
                                                        </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </fieldset>
                                <h3>Opciones de respuestas</h3>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <p class="col-lg-12 font-13">
                                                    Seleccione una o varias opciones que tendrá el evaluador para esta página.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row contenedorOpcRespuestaStepFinal">
                                        <div class="col-md-6">
                                            <div class="form-group row contenedorOpcionesRta">
                                                                                                
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="form-group row contenidoPlanDeAccion collapse">
                                        <label class="col-lg-3 col-form-label" style="text-align:end;"> </label>
                                       {{-- OPC AUTOMATICO --}}
                                        <div class="col-lg-7 planAccionAutomatico collapse">
                                            <b class="col-lg-12 font-16">
                                                Plan de acción
                                            </b>
                                            <p class="col-lg-10 font-14">
                                                Seleccione una respuesta que desencadenará automaticamente el plan de acción cuando el evaluador
                                                la seleccione.
                                            </p>

                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <select class="form-control select2 selectStepPopUp contestarPopUpStep" id="SelectPopUpContestar">
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <textarea id="planDeAccionArea" placeholder="Escriba aquí su plan de acción..." rows="4" class="form-control font-13"></textarea>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- OPC MANUAL--}}
                                        <div class="col-lg-12 planAccionManual collapse">
                                            <b class="col-lg-12 font-16 ">
                                                Plan de acción <b>manual</b>
                                            </b>
                                            <p class="col-lg-10 font-14">
                                                Seleccione las opciones que va a llevar su plan de acción.
                                            </p>

                                            <div class="form-group">
                                               
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Descripcion</th>
                                                                <th scope="col">Habilitar</th>
                                                                <th scope="col">Requerido</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="tbody-detalle-pm">
                                                            
                                                            <tr code-puntaje="1">
                                                                <td>
                                                                    ¿Qué será realizado?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="1" id="pruebaCheck" >
                                                                      </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="1" >
                                                                      </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="2">
                                                                <td>
                                                                    ¿Por qué será hecho?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="2" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="2"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="3">
                                                                <td>
                                                                    ¿Dónde será hecho?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="3" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="3"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="4">
                                                                <td>
                                                                    ¿Cuándo será hecho?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="4" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="4"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="5">
                                                                <td>
                                                                    ¿Quién lo hará?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="5" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="5"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="6">
                                                                <td>
                                                                    ¿Cómo será hecho?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="6" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="6" disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="7">
                                                                <td>
                                                                    ¿Cuánto costará?
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="7" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="7"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="8">
                                                                <td>
                                                                    Responsable
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="8" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="8"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                            <tr code-puntaje="9">
                                                                <td>
                                                                    Observaciones 
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion checkHabilitar" type="checkbox" value="9" >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input checkBoxPlanAccion requerido" type="checkbox" value="9"  disabled>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        
                                                        </tbody>
                                                    </table>
                                            
                                            </div>

                                        </div>
                                    </div>
                                    
                                </fieldset>
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREAR PREGUNTA - FIN --}}

 <!--  MODAL LINK-->
 <div class="modal fade bs-example-modal-lg" id="linkPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title mt-0">Listo, has terminado de crear la lista de chequeo !</p>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <form class="" id="formularioLink" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-12">
                                        <p for="">Comparte este link a los colaboradores para que la ejecuten. </p>
                                        <div class="input-group col-md-0">
                                            <input class="form-control disabled py-2 border-rigth-0 border " readonly="readonly" type="text" value="{{ $url }}" placeholder="Link generado" id="link">
                                            <span class="input-group-append">
                                                <button class="btn border-rigth-0 border copiarLink" type="button">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="form-group m-t-10">
                                            <label>Frecuencia de ejecución:</label>
                                            <select class="form-control   linkSelect">
                                                <option value="0">Indefinida</option>
                                                <option value="1">Diario</option>
                                                <option value="2">Mensual</option>
                                                <option value="3">Anual</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Cantidad por frecuencia:</label>
                                            <input type="text" disabled class="form-control input-number cantidadFrecuencia" placeholder="Indefinida"/>
                                        </div>

                                        <div class="form-group noselect">
                                            <i data-toggle="tooltip" data-placement="left" title="" class="noselect mdi mdi-star startRange" idfavorito="1" onclick="OnClickFavoritoCrearLista(this);" style="font-size: 25px;" data-original-title="Lista favorita"></i>
                                            <label class="noselect">Convertir en favorito (Selección por defecto en tu centro de control)</label>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <button type="button" class="btn btn-primary col-lg-12 waves-effect waves-light confirmar" >CONFIRMAR</button>
                                        </div>

                                        
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL LINK - FIN --}}

 <!--  MODAL CREAR ETIQUETA  -->
 <div class="modal fade bs-example-modal-lg" id="creacionEtiqueta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Creación de etiqueta</h5>
                <button type="button" class="close cancelarPopUpCreacionEtiqueta" aria-hidden="true">×</button>
            </div>
            
                <div class="modal-body">
                    <div class="row">
                            <div class="form-group col-sm-5">
                                <label>Nombre: <span class="requerido">*</span></label>
                                <input type="text" class="form-control" id="nombreEtiqueta" required placeholder="Ingrese el nombre de la etiqueta"/>
                            </div>
            
                            <div class="form-group col-sm-7">
                                <label>Descripción:</label>
                                <textarea  class="form-control" rows="3" id="descripcion" placeholder="Ingrese la descripción de la etiqueta"></textarea>
                            </div>
    
                            <div class="form-group col-sm-12" style="text-align: end;">
                                <button type="button" class="btn btn-primary waves-effect m-l-5 crearAccionEtiqueta" >Agregar</button>
                                <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarPopUpCreacionEtiqueta">Cancelar</button>
                            </div>
                        
                    </div>
                    
                </div>
            

            <div class="modal-footer">
                <div class="col-lg-12 conenedorTablaEtiquetas">
                    <table id="tablaCorrectivos" class="table table-striped m-b-0">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                       
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREAR ETIQUETA  - FIN --}}

@endsection

@section('script')
<script text="text/javascript">
    let tiposPonderado = @json($idTipoPonderado);
</script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/mislistas/crearlistas.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/jquery-steps/jquery.steps.min.js') }}"></script>

@endsection