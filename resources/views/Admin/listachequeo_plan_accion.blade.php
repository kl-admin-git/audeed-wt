@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/planaccion/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Plan de acción</h1>
@endsection
@php
    $clase="";
    if(!is_null($filtrar))
        $clase = "show";
@endphp
@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorTablaPerfiles">
                    <div class="col-lg-12">
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                                <div class="contenedorBuscador">
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 
                                    <i class="mdi mdi-file-excel" style="font-size: 2.5rem;color: #4FB648;"></i>
                                    <form action="{{url('/listachequeo/planaccion/descargar-excel')}}" method="POST" style="display: none;" id="descargar-excel-planAccion">
                                        @csrf
                                        <input type="hidden"  name="filtros_busqueda" id="filtros_busqueda"/>
                                    </form>
                                </div>
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse {{$clase}}" id="collapseExample">
                                        
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
                                                            <select class="form-control select2 selectSearch empresaSearch">
                                                                <option value="">Buscar por la empresa</option>
                                                                @foreach ($empresas as $itemEmpresa)
                                                                    <option value="{{ $itemEmpresa->id }}">{{ $itemEmpresa->nombre }}</option>
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
                        </div>
                    </div>

                    <table id="tablaPlanAccion" class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha de realización</th>
                                <th>Lista de chequeo</th>
                                <th>Empresa</th>
                                <th>Evaluado</th>
                                <th>Evaluador</th>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                                <th>Observación del evaluador</th>
                                <th>Estado</th>
                                <th>Tipo de plan de acción </th>
                                <th>Acciones</th>
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


 <!--  MODAL ASIGNAR PLAN ACCION  -->
 <div class="modal fade bs-example-modal-lg" id="asignacionPlanAccion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Asignar</h5>
                <button type="button" class="close cerrarPopUpAsignacion" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-3 col-form-label">Pregunta:</label>
                    <div class="col-sm-9">
                        <label class="col-form-label textoNoTitulo preguntaTexto">Pregunta acá va</label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-3 col-form-label">Respuesta:</label>
                    <div class="col-sm-9">
                        <label class="col-form-label textoNoTitulo respuestaTexto">Pregunta acá va</label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-3 col-form-label">Evaluado</label>
                    <div class="col-sm-9">
                        <label class="col-form-label textoNoTitulo evaluadoTexto">Pregunta acá va</label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-3 col-form-label">Acción correctiva</label>
                    <div class="col-sm-7">
                        <select class="form-control select2 correctivo">
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary waves-effect m-l-5 crearPlan" >Crear</button>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect m-l-5 asignarPlanAccionBoton">Guardar</button>
                <button type="button" class="btn btn-secondary waves-effect m-l-5 cerrarPopUpAsignacion">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL ASIGNAR PLAN ACCION  - FIN --}}

 <!--  MODAL CREAR PLAN DE ACCION  -->
 <div class="modal fade bs-example-modal-lg" id="creacionPlanAccion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Creación de acción correctiva</h5>
                <button type="button" class="close cancelarPopUpCreacion" aria-hidden="true">×</button>
            </div>
            
                <div class="modal-body">
                    <div class="row">
                            <div class="form-group col-sm-5">
                                <label>Titulo</label>
                                <input type="text" class="form-control" id="tituloCorrectivo" required placeholder="Ingrese el titulo del plan de acción"/>
                            </div>
            
                            <div class="form-group col-sm-7">
                                <label>Descripción</label>
                                <textarea  class="form-control" rows="3" id="descripcion" required placeholder="Ingrese la descripción del plan de acción"></textarea>
                            </div>
    
                            <div class="form-group row col-sm-12 contenedorAccionCorrectiva">
                                <label for="example-text-input" class="col-sm-3 col-form-label">Acción correctiva</label>
                                <div class="col-sm-6 row">
                                    <div class="col-lg-3 col-md-0 text-center ">
                                        <button class="btn btn-primary bootstrap-touchspin-down selectorColores" color="primary" type="button"></button>
                                    </div>
    
                                    <div class="col-lg-3 col-md-0 text-center">
                                        <button class="btn btn-warning bootstrap-touchspin-down selectorColores" color="warning" type="button"></button>
                                    </div>
    
                                    <div class="col-lg-3 col-md-0 text-center">
                                        <button class="btn btn-danger bootstrap-touchspin-down selectorColores" color="danger" type="button"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-12" style="text-align: end;">
                                <button type="button" class="btn btn-primary waves-effect m-l-5 crearAccionCorrectiva" >Agregar</button>
                                <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarPopUpCreacion">Cancelar</button>
                            </div>
                        
                    </div>
                    
                </div>
            

            <div class="modal-footer">
                <div class="col-lg-12 contenedorTablaCorrectivos">
                    <table id="tablaCorrectivos" class="table table-striped m-b-0">
                        <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Descripción</th>
                            <th>Color</th>
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
{{-- MODAL CREAR PLAN DE ACCION  - FIN --}}

@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/planaccion/main.js') }}"></script>
@endsection