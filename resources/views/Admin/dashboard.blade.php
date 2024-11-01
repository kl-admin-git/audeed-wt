@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/dashboard/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Centro de control</h1>
@endsection

@section('section')

{{-- SECCIÓN 1 --}}
<div class="row" style="border-top: 1px solid #dddddd;padding-top: 10px;">
    <div class="col-lg-12">
        <div class="row">

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
                        <input type="text" class="form-control" value="" placeholder="Fecha de inicio" id="pickerDesde">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="mdi mdi-calendar" style="color: green"></i></span>
                        </div>
                    </div><!-- input-group -->
                </div>
            </div>

            <div class="col-lg-2 contenedorFechaFin hidden">
                <div class="form-group">
                    <label>Fecha fin:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="" placeholder="Fecha de fin" id="pickerHasta">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="mdi mdi-calendar" style="color: green"></i></span>
                        </div>
                    </div><!-- input-group -->
                </div>
            </div>

            <div class="col-lg-2">
                <label style="opacity: 0">a</label>
                <div class="form-group">
                    <button class="btn btn-primary buscarBoton">Buscar</button>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- FIN SECCIÓN 1 --}}

{{-- SECCIÓN 2 --}}
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon mr-0 float-right" style="background:#13e600"><i class="mdi mdi-check-circle"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-purple texto_terminadas">0</span>
                Listas terminadas
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon mr-0 float-right" style="background:#ff855c"><i class="mdi mdi-check-circle"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-blue-grey texto_proceso">0</span>
                Listas en proceso
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon mr-0 float-right" style="background:#ff0000"><i class="mdi mdi-check-circle"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-brown texto_canceladas">0</span>
                Listas canceladas
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-teal mr-0 float-right"><i class="mdi mdi-file-check"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-teal texto_plan_accion">0</span>
                Hallazgos
            </div>
        </div>
    </div>
</div>
{{-- FIN SECCIÓN 2 --}}

{{-- SECCIÓN 3 --}}
    {{-- TABLA GENERAL --}}
    <div class="row m-b-30 contenedorGeneralTabla">
        <div class="col-xl-12">
            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="mt-0 m-b-30 header-title">TABLA GENERAL</h4>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table id="table" class="table tablaGeneral">
                                <thead class="table-inverse mb-0">
                                    <tr>
                                        <th>Lista</th>
                                        <th>Empresa</th>
                                        <th>Fecha</th>
                                        <th>Entidad evaluada</th>
                                        <th>Nombre</th>
                                        <th>Hallazgos</th>
                                        <th>Resultado final</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                   
                                </tbody>
                                <tfoot style="text-align: center;">
                                    <tr>
                                        <th class="verMas" colspan="7" style="cursor: pointer;font-weight: bold;">Ver más</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    {{-- FIN - TABLA GENERAL--}}
{{-- FIN SECCIÓN 3 --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/dashboard/main.js') }}"></script>
@endsection