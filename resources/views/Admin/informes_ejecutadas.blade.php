@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/informes/ejecutadas/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Informes ejecutadas</h1>
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
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 
                                    <i class="mdi mdi-file-excel"></i>
                                    <form action="{{url('/informes/descargar-excel')}}" method="GET" style="display: none;" id="descargar-excel">
                                        @csrf
                                        <input type="hidden"  name="filtros_busqueda" id="filtros_busqueda"/>
                                    </form>
                                </div>
                                
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse" id="collapseExample">
                                        
                                            <div class="card card-body">
                                                <div class="row">
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch listaSearch">
                                                                <option value="">Buscar por lista chequeo</option>
                                                                @foreach ($listaChequeo as $itemLista)
                                                                    <option value="{{ $itemLista->id }}">{{ $itemLista->lista_chequeo }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

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

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch estadoSearch">
                                                                <option value="">Buscar por estado</option>
                                                                @foreach ($estados as $itemEstado)
                                                                    <option value="{{ $itemEstado->ID_ESTADO }}">{{ $itemEstado->ESTADO_NOMBRE }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch entidadSearch">
                                                                <option value="">Buscar por entidad evaluada</option>
                                                                @foreach ($entidadEvaluada as $itemEntidadEvaluada)
                                                                    <option value="{{ $itemEntidadEvaluada->ID_ENTIDAD_EVALUADA }}">{{ $itemEntidadEvaluada->entidad_evaluada }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
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
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadorSearch">
                                                                <option value="">Buscar por evaluador</option>
                                                                @foreach ($evaluadores as $itemEvaluador)
                                                                    <option value="{{ $itemEvaluador->id }}">{{ $itemEvaluador->evaluador }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                
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
                            <div class="col-lg-12">
                                {{-- <div class="contenedorBuscador">
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Buscar  <i class="fa" aria-hidden="true"></i></button> 
                                </div>
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse" id="collapseExample">
                                        
                                            <div class="card card-body">
                                                <div class="row">
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

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch codigoSearch">
                                                                <option value="">Buscar por código</option>
                                                                @foreach ($planAccionEnListar as $itemPlanAccion)
                                                                    @if (ISSET($itemPlanAccion->CODIGO_PLAN_ACCION))
                                                                        @if ($filtrar == $itemPlanAccion->CODIGO_PLAN_ACCION)
                                                                            <option value="{{ $itemPlanAccion->CODIGO_PLAN_ACCION }}" selected>{{ $itemPlanAccion->CODIGO_PLAN_ACCION }}</option>
                                                                        @else
                                                                            <option value="{{ $itemPlanAccion->CODIGO_PLAN_ACCION }}">{{ $itemPlanAccion->CODIGO_PLAN_ACCION }}</option>
                                                                        @endif
                                                                        
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch listaSearch">
                                                                <option value="">Buscar por lista de chequeo</option>
                                                                @foreach ($listaChequeo as $itemListaChequeo)
                                                                    <option value="{{ $itemListaChequeo->id }}">{{ $itemListaChequeo->nombre }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadoSearch">
                                                                <option value="">Buscar por el evaluado</option>
                                                                 @foreach ($evaluados as $itemEvaluado)
                                                                    <option value="{{ $itemEvaluado->entidad_evaluada }}">{{ $itemEvaluado->evaluado }}</option>
                                                                 @endforeach
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
            
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadorSearch">
                                                                <option value="">Buscar por el evaluador</option>
                                                                @foreach ($evaluadores as $itemEvaluador)
                                                                    <option value="{{ $itemEvaluador->id }}">{{ $itemEvaluador->evaluador }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
            
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary waves-effect waves-light buscarBoton"><i class="fa fa-search"></i> Buscar</button>
                                                            <button type="button" class="btn btn-primary waves-effect waves-light restablecerBoton"><i class="mdi mdi-autorenew"></i> Restablecer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <table id="tablaInformesEjecutadas" class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Lista de chequeo</th>
                                <th>Fecha de realización</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                                <th>Empresa</th>
                                <th>Entidad evaluada</th>
                                <th>Evaluado</th>
                                <th>Evaluador</th>
                                <th>Resultado final</th>
                            </tr>
                        </thead>
                            
    
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

               

                <div class="contenedorPaginacion">
                    <nav class="pagination">
                        <div class="nav-btn prev"></div>
                        <ul class="nav-pages"></ul>
                        <div class="nav-btn next"></div>
                    </nav>
                </div>
                

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->




 <!--  MODAL LATITUD Y LONGITUD  -->
 <div class="modal fade" id="modalViewMap" tabindex="-1" style="z-index: 99999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Mapa</h5>
            </div>
            <div class="modal-body">
                <div id="map" style="height:300px;"></div>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{{-- MODAL LATITUD Y LONGITUD  - FIN --}}

@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/informes/ejecutadas/main.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClW1wVkJdrfHUH_i0hMhDmPfwVq0xTrv8&callback=initMap"
async defer></script>
@endsection