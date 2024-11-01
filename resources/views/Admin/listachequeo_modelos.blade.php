@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/modelos/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Modelos</h1>
@endsection

@section('section')

<div class="row">
    <div class="col-12">

        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    <div class="contenedorBuscador contenedorBuscador--espacio">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 
                        @if ($administradorPlataforma)
                        <button type="button" class="btn btn-primary" id="asignar-modelos">Asignar Modelos</button> 
                            
                        @endif
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="collapse" id="collapseExample">
                            
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 nombreModeloSearch">
                                                    <option value="">Buscar por nombre modelo</option>
                                                    @foreach ($modelos as $itemModelo)
                                                        @if (ISSET($itemModelo->nombre))
                                                            <option value="{{ $itemModelo->id }}">{{ $itemModelo->nombre }}</option>    
                                                        @endif
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light buscarModelo"><i class="fa fa-search"></i> Buscar</button>
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


        <div class="page-content-wrapper mb-5">
            <div class="container-fluid">
                <div class="row contenedorModelos">

                </div>
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

@endsection


<!--  MODAL ASIGNACION MODELO -->
<div class="modal fade bs-example-modal-lg" id="modal-asignacion-modelo"  style="display: none"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 tituloCategoriaPopUp">ASIGNACION DE MODELOS AUN SECTOR</h5>
                
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <form class="" id="formularioCrearCategoria" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-12">

                                        <div class="form-group">
                                            <label>Modelos: </label>
                                            <select class="form-control select2" id="modeloId">
                                                <option value="">Seleccione un modelo</option>                             
                                                @foreach ($modelosAdmin as $modelo)
                                                    <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>    
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div  class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" class="sectorAll">
                                            <span>Seleccionar Todo</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="contenedor-ul">
                                            <ul class="list-group">
                                                @foreach ($sectorAdministrador as $item)
                                                    <li class="list-group-item"><input type="checkbox" class="sector-id" sectorId ="{{$item->id}}">
                                                        <span>{{$item->nombre}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light guardar-modelo-asignacion" accion="0">Guardar</button>
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5"  data-dismiss="modal">Cancelar</button>
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
{{-- MODAL ASIGNACION MODELO - FIN --}}

@section('script')
<script type="text/javascript">
    let perfilIdUsuarioActual = {!! json_encode(auth()->user()->perfil_id) !!};
</script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/listachequeo/modelos/main.js') }}"></script>
@endsection