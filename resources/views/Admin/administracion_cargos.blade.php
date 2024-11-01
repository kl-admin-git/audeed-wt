@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/cargos/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Cargos</h1>
@endsection

@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorTablaCargos">
                    <div class="col-lg-12">
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                                <div class="contenedorBuscador">
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 

                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="crearCargo">Nuevo</button>
                                </div>
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse" id="collapseExample">
                                        
                                            <div class="card card-body">
                                                <div class="row">
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch cargosSearch">
                                                                <option value="">Buscar por cargo</option>
                                                                @foreach ($cargos as $itemCargo)
                                                                    <option value="{{ $itemCargo->id }}">{{ $itemCargo->nombre }}</option>
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

                    <table id="tablaCargos" class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
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


 <!--  MODAL CREACION EDICION CARGO  -->
 <div class="modal fade bs-example-modal-lg" id="crearEditarCargo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Creación de cargo</h5>
                <button type="button" class="close cerrarCreacionCargo" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-3 col-form-label">Nombre:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control cargoPopUp" required placeholder="Ingresa el cargo"/>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect m-l-5 guardarCargo" accion="0" >Guardar</button>
                <button type="button" class="btn btn-secondary waves-effect m-l-5 cerrarCreacionCargo">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREACION EDICION CARGO  - FIN --}}

@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/administracion/cargos/main.js') }}"></script>
@endsection