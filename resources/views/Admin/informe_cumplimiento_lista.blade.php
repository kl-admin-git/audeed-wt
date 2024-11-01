@extends('template.baseVertical')

@section('css')
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/informes/cumplimiento_lista/main.css') }}">
@endsection

@section('breadcrumb')
    <h3 class="page-title">Centro de control</h1>
    @endsection

    @section('section')


        {{-- SECCIÓN (FILTROS) --}}
        <div class="row" style="border-top: 1px solid #dddddd;padding-top: 10px;">
            <div class="col-lg-12">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Empresa:</label>
                            <select class="form-control select2 selectSearch selectListdoEmpresas">
                                <option value="0">Todas</option>
                                @foreach ($empresas as $itemEmpresa)
                                    <option value="{{ $itemEmpresa->id }}">{{ $itemEmpresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Lista de chequeo favorita:</label>
                            <select class="form-control select2 selectSearch selectListaChequeo">
                                @foreach ($listasDeChequeo as $itemListaChequeo)
                                    <option value="{{ $itemListaChequeo->id }}">{{ $itemListaChequeo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Realizadas en fecha:</label>
                            <select class="form-control select2 selectSearch realizadasSearch">
                                <option value="1">Este mes</option>
                                <option value="2">Hoy</option>
                                <option value="3">Selecciona un periodo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 contenedorFechaInicio hidden">
                        <div class="form-group">
                            <label>Fecha inicio:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="" placeholder="Fecha de inicio"
                                    id="pickerDesde">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"
                                            style="color: green"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                    </div>

                    <div class="col-lg-2 contenedorFechaFin hidden">
                        <div class="form-group">
                            <label>Fecha fin:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="" placeholder="Fecha de fin"
                                    id="pickerHasta">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"
                                            style="color: green"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <label style="opacity: 0">a</label>
                        <div class="form-group">
                            <button class="btn btn-primary buscarBotonInforme">Buscar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- FIN SECCIÓN FILTROS --}}

        {{-- SECCIÓN 1 --}}
        <div class="row">

            <div class="col-xl-9">
                <div class="card m-b-20">
                    <div class="card-body" style="min-height: 350px;">
                        <h4 class="mt-0 m-b-30 header-title">PROMEDIO DE RESULTADO FINAL</h4>
                        <div class="card-block contenedorGraficaResultadoFinal">
                            <canvas id="canvasPromedioResultadoFinal" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card m-b-20">
                    <div class="card-body" style="min-height: 350px;">
                        <h4 class="mt-0 m-b-30 header-title">PROMEDIO GENERAL: <span class="nombreEmpresa"></span> </h4>
                        <div class="card-block">
                            <h1 class="porcentajeTexto">80%</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- FIN SECCIÓN 1 --}}

        {{-- SECCIÓN 2 --}}
        <div class="row">

            <div class="col-xl-9">
                <div class="card m-b-20">
                    <div class="card-body" style="min-height: 350px;">
                        <h4 class="mt-0 m-b-30 header-title">PROMEDIO DE RESULTADO POR CATEGORÍA</h4>
                        <div class="card-block contenedorGraficaCategorias">
                            <canvas id="canvasPromedioCategoria" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card m-b-20">
                    <div class="card-body" style="min-height: 350px;">
                        <h4 class="mt-0 m-b-30 header-title">EMPRESAS: <span class="subtituloEmpresas">Todas</span> <span class="nombreEmpresa"></span> </h4>
                        <div class="card-block" style="overflow: auto;height: 250px;">
                            <ul class="listaInforme"> 
                                <li>Gestión de Salud <span class="porce">40%</span></li>
                                <li>Gestión de Peligros <span class="porce">90%</span></li>
                                <li>Gestión de Amenazas <span class="porce">100%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- FIN SECCIÓN 2 --}}

    {{-- TABLA REINCIDENCIAS --}}
        <div class="row m-b-30">
            <div class="col-xl-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="mt-0 m-b-30 header-title">REINCIDENCIAS DE INCUMPLIMIENTO</h4>
                        <div class="card-block">
                            <div class="table-responsive">
                                <table id="table" class="table tablaReincidencias">
                                    <thead class="table-inverse mb-0">
                                        <tr>
                                            <th>Pregunta</th>
                                            <th>Entidad/Área/Equipo</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->


            {{-- <div class="col-xl-3">
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">{{ trans('dashboardmessages.titlesschart') }}</h4>

                        <div id="b"></div>

                    </div>
                </div>
            </div> --}}


        </div>
    {{-- FIN - TABLA REINCIDENCIAS --}}

    {{-- TABLA POR CICLO --}}
    <div class="row m-b-30 contenedorCiclo">
        <div class="col-xl-12">
            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="mt-0 m-b-30 header-title">PORCENTAJE DE CALIFICACION POR CICLO</h4>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table id="table" class="table tablaPorcentaje">
                                <thead class="table-inverse mb-0">
                                    <tr>
                                        <th>Ciclo</th>
                                        <th>Resultado</th>
                                        <th>% de calificación</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    
                                </tbody>
                                <tfoot style="text-align: center;">
                                   
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
{{-- FIN - TABLA POR CICLO --}}
    @endsection

    @section('script')
    <script type="text/javascript" src="{{ assets_version('/vertical/assets/js/dashboard/main.js') }}"></script>
        <script type="text/javascript"
            src="{{ assets_version('/vertical/assets/js/informes/cumplimiento_lista/main.js') }}"></script>
    @endsection
