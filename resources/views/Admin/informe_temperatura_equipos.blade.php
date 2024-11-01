@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/informes/temperatura_equipos/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Temperatura de equipos frios</h1>
@endsection
@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorTablaEjecutadasInformes">
                    <div class="col-lg-12">
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                                <div class="contenedorBuscador contenedorBuscador--espacio">
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar <i class="fa" aria-hidden="true"></i></button> 
                                    <i class="mdi mdi-file-excel download_excel"></i>
                                </div>
                                
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse" id="collapseExample">
                                        
                                            <div class="card card-body">
                                                <div class="row">
                
                                                    {{-- <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch listaSearch">
                                                                <option value="">Buscar por lista chequeo</option>
                                                                @foreach ($listaChequeo as $itemLista)
                                                                    <option value="{{ $itemLista->id }}">{{ $itemLista->lista_chequeo }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="" placeholder="fecha de realización" id="datepicker-autoclose">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar" style="color: green"></i></span>
                                                                    </div>
                                                                </div><!-- input-group -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch estadoSearch">
                                                                <option value="">Buscar por estado</option>
                                                                @foreach ($estados as $itemEstado)
                                                                    <option value="{{ $itemEstado->ID_ESTADO }}">{{ $itemEstado->ESTADO_NOMBRE }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}

                                                    {{-- <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch entidadSearch">
                                                                <option value="">Buscar por entidad evaluada</option>
                                                                @foreach ($entidadEvaluada as $itemEntidadEvaluada)
                                                                    <option value="{{ $itemEntidadEvaluada->ID_ENTIDAD_EVALUADA }}">{{ $itemEntidadEvaluada->entidad_evaluada }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}

                                                    {{-- <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadoSearch">
                                                                <option value="">Buscar por evaluado</option>
                                                                @foreach ($evaluados as $itemEvaluado)
                                                                    @if (ISSET($itemEvaluado->id))
                                                                        <option value="{{ $itemEvaluado->id }}">{{ $itemEvaluado->evaluado }}</option>    
                                                                    @endif
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}

                                                    {{-- <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadorSearch">
                                                                <option value="">Buscar por evaluador</option>
                                                                @foreach ($evaluadores as $itemEvaluador)
                                                                    <option value="{{ $itemEvaluador->id }}">{{ $itemEvaluador->evaluador }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary waves-effect waves-light buscarBoton"><i class="fa fa-search"></i> Buscar</button>
                                                            <button type="button" class="btn btn-primary waves-effect waves-light restablecerBoton"><i class="mdi mdi-autorenew"></i> Restablecer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-lg-12 dev-overflow-100">
                        <table class="table table-striped m-b-0 tableTemperatura">
                            <thead>
                              {{-- CARGADO POR JS --}}
                            </thead>
                        </table>
                    </div>

                </div>

               

                {{-- <div class="contenedorPaginacion">
                    <nav class="pagination">
                        <div class="nav-btn prev"></div>
                        <ul class="nav-pages"></ul>
                        <div class="nav-btn next"></div>
                    </nav>
                </div> --}}
                

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

 <!--  MODAL VER OBSERVACIÓN RESPUESTA  -->
 <div class="modal fade bs-example-modal-lg" id="view-rta-obs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Observación Respuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <p class="comment text-center"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
 <!--  FIN - MODAL VER OBSERVACIÓN RESPUESTA  -->
@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/informes/temperatura_equipos/main.js') }}"></script>
@endsection