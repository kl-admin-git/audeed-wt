@extends('template.baseVertical')


@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/mislistas/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Mis listas</h1>
@endsection

@section('section')

<div class="row">
    <div class="col-12">

        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    <div class="contenedorEncabezado">

                        <div class="contenedorBotonesTabs">
                            <button type="button" class="btn colorTabs" id="tour-creadas">
                                <span class="textoCirculos">Creadas</span> 
                                <span class="ciculoNumero">{{ $cantidad }}</span>
                            </button>
                            <a href="{{ route('List_MyList_Excecuted') }}" class="btn colorTabs" id="tour-ejecutadas">
                                <span class="textoCirculos">Ejecutadas</span> 
                                <span class="ciculoNumero">{{ $cantidadEjecutadas }}</span>
                            </a>
                            
                        </div>
                        @if (auth()->user()->perfil_id == 1) {{-- ADMINISTRADOR --}}
                            <div class="contenedorBotonesCrear">
                                <a href="{{ route('List_Model') }}" class="btn btn-primary waves-effect waves-light" id="tour-modelo">Nuevo desde modelo</a>
                                <button type="button" class="btn btn-primary waves-effect waves-light crearDesdeCero" id="tour-nuevo">Nuevo desde cero</button>
                            </div>
                        @endif
                        
                        
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="collapse" id="collapseExample">
                            
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" required="" placeholder="Buscar por titulo lista de chequeo">
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content-wrapper mb-5">
            <div class="container-fluid ">
                <div class="row contenedorTarjetaListas">

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
                                    
                                    <div class="col-lg-8">
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
                                                <option value="4">Áreas</option>
                                                <option value="5">Equipos</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Estado inicial:</label>
                                            <select class="form-control select2 estadoInicialPopUp">
                                                <option value="1">Publicada</option>
                                                <option value="0">Despublicada</option>
                                            </select>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="checkBoxAutomatico" checked>
                                                        <label class="custom-control-label font-13 labelCheck" for="checkBoxAutomatico">Ponderados automáticos (Desmarcar para asignarlos manualmente)</label>
                                                    </div>
                                            </div>
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
                                                <button onclick="CopiarClipBoard(this);" class="btn border-rigth-0 border copiarLink" type="button">
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

 <!--  MODAL VISUALIZACION-->
 <div class="modal fade bs-example-modal-lg" id="visualizacionPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title mt-0">Previsualización lista de chequeo</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
             
                <div class="col-lg-12">
                    <div class="card-body">
                        <div id="accordion" class="contenedorCategorias">
                            
                            
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL VISUALIZACION - FIN --}}

@endsection

@section('script')
<script src="{{ assets_version('/vertical/assets/plugins/sparklines-chart/jquery.sparkline.min.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/pages/directory.init.js') }}"></script>
<script type="text/javascript">
    let perfilIdUsuarioActual = {!! json_encode(auth()->user()->perfil_id) !!};
</script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/mislistas/main.js') }}"></script>
@endsection