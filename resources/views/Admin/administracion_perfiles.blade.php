@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/perfiles/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Perfiles</h1>
@endsection

@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorTablaPerfiles">
                    <div class="form-group row has-success">
                        <div class="col-sm-4">
                            <div class="input-group col-md-0">
                                <span class="input-group-append">
                                    <button class="btn btn-outline-secondary border-rigth-0 border" disabled type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                                <input class="form-control py-2 border-left-0 border" type="search" value="" placeholder="Buscar en registros" id="example-search-input">
                            </div>
                        </div>
                    </div>
                    <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ trans('perfilesmessages.tabperfil') }}</th>
                                <th>{{ trans('perfilesmessages.tabacciones') }}</th>
                            </tr>
                        </thead>
    
    
                        <tbody>
                                <tr>
                                    <td>Administrador</td>
                                    <td>
                                        <div class="contenedorBotonesAcciones">
                                            <li class="mdi mdi-account-multiple" onclick="OnClickVerUsuarioAsignados();" data-toggle="tooltip" data-placement="top" title="Usuarios asignados"></li>
                                        </div>
                                        
                                    </td>
                                </tr>   
                                
                                <tr>
                                    <td>Analista</td>
                                    <td>
                                        <div class="contenedorBotonesAcciones">
                                            <li class="mdi mdi-account-multiple" onclick="OnClickVerUsuarioAsignados();" data-toggle="tooltip" data-placement="top" title="Usuarios asignados"></li>
                                        </div>
                                        
                                    </td>
                                </tr>   
                            
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


 <!--  MODAL VER USUARIOS ASIGNADOS  -->
 <div class="modal fade bs-example-modal-lg" id="visualizarUsuarioAsignado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">{{ trans('perfilesmessages.modalvisualizarusuarios') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p class="text-muted m-b-30 font-14">{{ trans('perfilesmessages.modalsubtitulomensaje') }}</p>
                <ul>
                    <li>Dayana Arango</li>
                    <li>Diego Meneses</li>
                    <li>Roberto Arenas</li>
                    <li>Roney Rodriguez</li>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL VER USUARIOS ASIGNADOS  - FIN --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/administracion/perfiles/main.js') }}"></script>
@endsection