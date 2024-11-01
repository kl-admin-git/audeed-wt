@extends('template.baseVertical')

@section('css')
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/areas/main.css') }}">
@endsection

@section('breadcrumb')
    <h3 class="page-title">Áreas</h1>
    @endsection

    @section('section')
        <div class="row datosUsuario" idCuentaPrincipal="{{ auth()->user()->cuenta_principal_id }}">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="row m-b-10">
                        <div class="col-lg-12">

                            <div class="contenedorBuscador">
                                <button type="button" class="btn btn-primary" data-toggle="collapse"
                                    data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
                                    id="buscar-tour">Buscar <i class="fa" aria-hidden="true"></i></button>
                                    @if ($perfilExacto == 1 || $perfilExacto == 2)
                                        <button type="button" class="btn btn-primary waves-effect waves-light"
                                            id="button_to_create">Nuevo</button>
                                    @endif
                            </div>

                            <div class="col-lg-12 m-t-10">
                                <div class="collapse" id="collapseExample">

                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch areasSearch">
                                                        <option value="null" disabled selected>Buscar por nombre de área</option>
                                                        @foreach ($areasFiltro as $area)
                                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light buscarBoton"><i
                                                            class="fa fa-search"></i> Buscar</button>
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light restablecerBoton"><i
                                                            class="mdi mdi-autorenew"></i> Restablecer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="page-content-wrapper mb-5 p-0">
                        <div class="container-fluid">
                            <div class="row contenedorAreas">
                                {{-- CARGADA POR JS --}}
                            </div> <!-- end row -->
                            <div class="contenedorFooterLoading hidden">
                                <div id="activity">
                                    <div class=indicator>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                        <div class="segment"></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- container -->
                    </div>
                </div>

            </div> 
        </div> <!-- end row -->

        <div id="main_no_data" class="hidden">
            <div class="fof">
                <h1>No hay información para mostrar</h1>
            </div>
        </div>

        <!--  MODAL CREAR ÁREA -->
        <div class="modal fade bs-example-modal-lg" id="crearAreaPopUp" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0">Creación de Áreas</h5>
                        <button type="button" class="close cancelarPopUpArea" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">

                                    <p class="text-muted m-b-30 font-14">Recuerda que para nosotros es muy importante que
                                        puedas diligenciar toda la información presente aunque algunos campos sean
                                        opcionales</p>

                                    <form class="" id="formularioCreacionAreas" action="#">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Nombre: <span class="requerido">*</span></label>
                                                    <input type="text" class="form-control nombreAreaPopUp" required
                                                        placeholder="Ingrese el nombre del área" />
                                                </div>

                                            </div>

                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    <label>Descripción: </label>
                                                    <input type="text" class="form-control descripcionPopUp"
                                                        placeholder="Ingrese la descripción del área" />
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Empresa: </label>
                                                    <select class="form-control select2 selectSearch empresaPopUp">
                                                        <option value="">Selecciona una empresa</option>
                                                        @foreach ($empresas as $itemEmpresa)
                                                            <option value="{{ $itemEmpresa->id }}">{{ $itemEmpresa->nombre }}</option>
                                                         @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Establecimiento: </label>
                                                    <select class="form-control select2 selectSearch establecimientoPopUp">
                                                        <option value="">Selecciona un establecimiento</option>
                                                        @foreach ($establecimientos as $itemEstablecimiento)
                                                            <option value="{{ $itemEstablecimiento->id }}">{{ $itemEstablecimiento->nombre }}</option>
                                                         @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group m-b-0">
                                            <div class="contenedorBotonesCreacion">
                                                <button type="button"
                                                    class="btn btn-primary waves-effect waves-light crearArea"
                                                    accion="0">Crear</button>
                                                <button type="button"
                                                    class="btn btn-secondary waves-effect m-l-5 cancelarPopUpArea">Cancelar</button>
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
        {{-- MODAL CREAR ÁREA - FIN --}}

        {{-- MODAL DE DETALLES --}}
        <div class="modal fade" id="modal-detalle" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalle del área</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       <ul class="detalle">
                           {{-- CARGADO POS JS --}}
                       </ul>
                    </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- FIN MODAL DE DETALLES --}}

    @endsection

    @section('script')
        <script type="text/javascript" src="{{ assets_version('/vertical/assets/js/administracion/areas/main.js') }}"></script>
        {{-- <script src="https://pagination.js.org/dist/2.1.5/pagination.min.js"></script> --}}
    @endsection
