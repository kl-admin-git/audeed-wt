@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/planaccion/hallazgos/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Hallazgos (Plan Acción)</h1>
@endsection

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
                                    <form action="{{url('/plan_accion/hallazgos/descargar-excel')}}" method="POST" style="display: none;" id="descargar-excel-planAccion">
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
                                                            <select class="form-control select2 selectSearch listaSearch">
                                                                <option value="">Buscar por lista de chequeo</option>
                                                                @foreach ($listaPlanAccionHallazgos as $itemListaChequeo)
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

@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/planaccion/hallazgos/main.js') }}"></script>
@endsection