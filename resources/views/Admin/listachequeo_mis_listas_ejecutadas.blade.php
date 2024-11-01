@extends('template.baseVertical')


@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/ejecutadas/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Listas ejecutadas</h1>
@endsection

@section('section')

<div class="row" style="margin-bottom: 50px">
        {{-- BUSCAR SECCIÓN --}}
        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    <div class="contenedorBuscador">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="collapse" id="collapseExample">
                            
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch nombreAuditoriaSearch">
                                                    <option value="">Buscar por nombre auditoría</option>
                                                    @foreach ($listasEjecutadas as $itemListaChequeo)
                                                        <option value="{{ $itemListaChequeo->id }}">{{ $itemListaChequeo->nombre }}</option>
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

        <div class="col-lg-12">
            <div class="m-b-10">
                <div class="container-fluid ">
                    <div class="row contenedorTarjetaListasEjecutadas">
                        
                        
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
            </div> <!-- Page content Wrapper -->
        </div>
        
    </div>
</div>

<div id="main_no_data" class="hidden">
    <div class="fof">
            <h1>No hay información para mostrar</h1>
    </div>
</div>

 <!--  MODAL CREAR LISTA DE CHEQUEO -->
 <div class="modal fade bs-example-modal-lg" id="crearMiListaPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Nueva lista desde cero</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <form class="" id="formularioCrearMiLista" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nombre: </label>
                                            <input type="text" class="form-control nombreMiListaPopUp" required placeholder="Nombre de la lista de chequeo"/>
                                        </div>

                                        <div class="form-group">
                                            <label>A quien será aplicada la lista de chequeo:</label>
                                            <select class="form-control select2 aQuienPopUp">
                                                <option value="1">Empresa</option>
                                                <option value="2">Establecimiento</option>
                                                <option value="3">Usuario</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Estado inicial:</label>
                                            <select class="form-control select2 estadoInicialPopUp">
                                                <option value="1">Publicada</option>
                                                <option value="2">Despublicada</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light continuar">Continuar</button>
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
{{-- MODAL CREAR LISTA DE CHEQUEO - FIN --}}

 <!--  MODAL LINK-->
 <div class="modal fade bs-example-modal-lg" id="linkPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title mt-0">Listo, has terminado de crear la lista de chequeo !</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                                            <input class="form-control py-2 border-rigth-0 border " readonly="readonly" type="text" value="" placeholder="Link generado" id="link">
                                            <span class="input-group-append">
                                                <button onclick="CopiarClipBoard(this);" class="btn btn-outline-secondary border-rigth-0 border copiarLink" type="button">
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

                                        {{-- <div class="form-group col-lg-12">
                                            <button type="button" class="btn btn-primary col-lg-12 waves-effect waves-light confirmar" >CONFIRMAR</button>
                                        </div> --}}

                                        
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

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/ejecutadas/main.js') }}"></script>
@endsection