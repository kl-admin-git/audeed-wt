@extends('template.baseVertical')
{{-- @php
    dd($DatosDevueltos['tipoDePlanAccion']);
@endphp --}}
@section('css')
    <!-- Dropzone css -->
    <link href="{{ assets_version('/vertical/assets/plugins/dropzone/dist/dropzone.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/planaccion/seguimiento.css') }}">
@endsection

@section('breadcrumb')
    <h3 class="page-title">Seguimiento a plan de acción</h1>
    @endsection

    @section('section')
        <div class="d-flex justify-content-end m-b-10 col-12">
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-primary waves-effect waves-light">Regresar</a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        Lista de chequeo
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="p-2"><b>Fecha</b></div>
                                            <div class="p-2">{{$DatosDevueltos['informacionListaChequeoSeccion']->FECHA_REALIZACION}}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="p-2"><b>Empresa</b></div>
                                            <div class="p-2">{{$DatosDevueltos['informacionListaChequeoSeccion']->EMPRESA}}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="p-2"><b>Establecimiento</b></div>
                                            <div class="p-2">{{$DatosDevueltos['informacionListaChequeoSeccion']->ESTABLECIMIENTO}}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="p-2"><b>Evaluador</b></div>
                                            <div class="p-2">{{$DatosDevueltos['informacionListaChequeoSeccion']->EVALUADOR}}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="p-2"><b>Item</b></div>
                                            <div class="p-2">{{$DatosDevueltos['informacionListaChequeoSeccion']->ITEM}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        Plan de acción
                                    </div>
                                    <div class="card-body">
                                        @if ($DatosDevueltos['tipoDePlanAccion'] == 1) {{-- PLAN ACCION AUTOMATICO --}}
                                            
                                            @foreach ($DatosDevueltos['trarInformacionPlanAccionSegundaSeccion'] as $itemPlanAccion)
                                            <div class="d-flex justify-content-between">                                            
                                                    <div class="p-2 text-audeed-crop" title="{{ $itemPlanAccion->RESPUESTA_PLAN_ACCION }}">{{ $itemPlanAccion->RESPUESTA_PLAN_ACCION }}</div>
                                            </div>
                                            @endforeach

                                        @else {{-- PLAN ACCION MANUAL --}}

                                            @foreach ($DatosDevueltos['trarInformacionPlanAccionSegundaSeccion'] as $itemPlanAccion)
                                            <div class="d-flex justify-content-between">                                            
                                                    <div class="p-2"><b>{{ $itemPlanAccion->OPCION }}</b></div>
                                                    <div class="p-2 text-audeed-crop" title="{{ $itemPlanAccion->RESPUESTA_OPCION }}">{{ $itemPlanAccion->RESPUESTA_OPCION }}</div>
                                            </div>
                                            @endforeach
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        Evidencia fotográfica
                                    </div>
                                    <div class="card-body">
                                        <div class="container col-lg-12">
                                            <div class="row">
                                                    @foreach ($DatosDevueltos['trarInformacionFotosPlanAccionTerceraSeccion'] as $itemFotografias)
                                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                                        <a class="thumbnail" href="#" data-image-id="" data-toggle="modal"
                                                            data-title=""
                                                            data-image="{{ Request::root().'/'.$itemFotografias->FOTO }}"
                                                            data-target="#image-gallery">
                                                            <img class="img-thumbnail"
                                                                src="{{ Request::root().'/'.$itemFotografias->FOTO }}"
                                                                alt="Imagen">
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                        
                                                    @if (COUNT($DatosDevueltos['trarInformacionFotosPlanAccionTerceraSeccion']) == 0)
                                                        <b class="text-center col-lg-12">No tienes evidencias fotográficas</b>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        Adjuntos
                                    </div>
                                    <div class="card-body row">

                                        @foreach ($DatosDevueltos['trarInformacionAdjuntosPlanAccionCuartaSeccion'] as $adjunto)
                                        <div class="col-lg-3 text-center componenteAdjunto" title="{{ $adjunto->ALIAS }}">
                                            <a href="/listachequeo/detalle/descargarAdjunto/{{ $adjunto->ID_ADJUNTO }}">
                                                    <i class="mdi {{ $adjunto->ICONO }} font-50 text-icon-audeed"></i>
                                                    <p class="m-0 p-2 text-center text-audeed-crop" >{{ $adjunto->ALIAS }}</p>
                                            </a>
                                        </div>
                                        @endforeach

                                        @if (COUNT($DatosDevueltos['trarInformacionAdjuntosPlanAccionCuartaSeccion']) == 0)
                                            <b class="text-center col-lg-12">No tienes ningún adjunto</b>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-5">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-lg-between">
                                        <button class="card-title font-16 mt-0 text-center m-0 text-white tituloSeguimiento">Seguimiento</button>
                                        <button class="btn btn-primary botonAgregarSeguimiento">Agregar seguimiento</button>
                                    </div>
                                </div>
                    
                                <div class="cuerpoSeguimientoGeneral">

                                </div>

                            </div><!-- end col -->

                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

<!-- MODAL VER IMAGENES -->
<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="image-gallery-title"></h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="image-gallery-image" class="img-responsive col-md-12" src="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary float-left" id="show-previous-image"><i
                        class="fa fa-arrow-left"></i>
                </button>

                <button type="button" id="show-next-image" class="btn btn-secondary float-right"><i
                        class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- FIN - MODAL VER IMAGENES -->

 <!--  MODAL AGREGAR SEGUIMIENTO  -->
 <div class="modal fade" id="agregarSeguimiento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar seguimiento</h5>
                <button type="button" class="close cancelarSeguimiento" aria-hidden="true">×</button>
            </div>
            
                <div class="modal-body cuerpo-modal-seguimiento">
                    <div class="row">
                            <div class="form-group col-sm-12">
                                <label>Estado</label>
                                <select class="form-control select2 selectEstadoPopUp">
                                         <option value="0">Selecciona el estado</option>
                                         <option value="1">Abierto</option>
                                         <option value="2">En proceso</option>
                                         <option value="3">Cerrado</option>
                                </select>
                            </div>
            
                            <div class="form-group col-sm-12">
                                <label>Adjuntos</label>
                                <div class="card m-b-20">
                                    <div class="card-body">
                                        <div class="m-b-30">
                                            <form action="{{ route('guardar_seguimiento_detalle') }}" class="dropzone" >
                                            {{ csrf_field() }}
                                            <input name="idListaEject" class="idListaEject" type="hidden"  value="{{ \Request::segment(4) }}">
                                            <input name="idPlanAccion" class="idPlanAccion" type="hidden"  value="{{ \Request::segment(5) }}">
                                            <input name="tipoPlanAccion" class="tipoPlanAccion" type="hidden"  value="{{ \Request::segment(6) }}">
                                            <input name="estado" class="estado" id="estadoHidden" type="hidden"  value="">
                                            <input name="descripcion" class="descripcion" id="descripcionHidden" type="hidden"  value="">
                                            <input name="idSeguimiento" class="idSeguimiento" type="hidden"  value="">
                                            </form>  
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label>Observación</label>
                                <textarea  class="form-control" rows="4" id="descripcion" required placeholder="Observación"></textarea>
                            </div>
                            
                            <div class="form-group col-sm-12" style="text-align: end;">
                                <button type="button" class="btn btn-primary waves-effect m-l-5 guardarSegumiento" >Guardar</button>
                                <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarSeguimiento">Cancelar</button>
                            </div>
                        
                    </div>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL AGREGAR SEGUIMIENTO  - FIN --}}


@endsection
@section('script')

<!-- Dropzone js -->
<script src="{{ assets_version('/vertical/assets/plugins/dropzone/dist/dropzone.js') }}"></script>
<script type="text/javascript"src="{{ assets_version('/vertical/assets/js/listachequeo/planaccion/seguimiento.js') }}"></script>
<script type="text/javascript"src="{{ assets_version('/vertical/assets/js/listachequeo/planaccion/modalImagenes.js') }}"></script>

@endsection
