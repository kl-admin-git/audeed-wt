@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/clientes/establecimientos/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Establecimientos</h1>
@endsection

@section('section')


<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                {{-- <div class="col-lg-12 m-b-30 contenedorBotonCrear">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="crearEmpresa">{{ trans('empresasmessages.buttoncrearempresa') }}</button>
                </div> --}}
                <div class="col-lg-12 m-b-30 contenedorTablaEstablecimientos">
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
                    {{-- <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ trans('empresasmessages.tabnombre') }}</th>
                                <th>{{ trans('empresasmessages.tabnit') }}</th>
                                <th>{{ trans('empresasmessages.tabcorreo') }}</th>
                                <th>{{ trans('empresasmessages.tabdireccion') }}</th>
                                <th>{{ trans('empresasmessages.tabtelefono') }}</th>
                                <th>{{ trans('empresasmessages.tabsector') }}</th>
                                <th>{{ trans('empresasmessages.tabusuarioresponsable') }}</th>
                                <th>{{ trans('empresasmessages.tabacciones') }}</th>
                            </tr>
                        </thead>
    
    
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td>Audeed</td>
                                    <td>5248142554</td>
                                    <td>audeed@audeed.co</td>
                                    <td>Cll 23 # 1-360</td>
                                    <td>3127379236</td>
                                    <td>Tecnología</td>
                                    <td>Diego Meneses</td>
                                    <td>
                                        <div class="contenedorBotonesAcciones">
                                            <li class="mdi mdi-border-color editarIcon" onclick="OnClickEditarEmpresa();" data-toggle="tooltip" data-placement="top" title="Editar"></li>
                                            <li class="mdi mdi-file-image verImagenIcon" data-toggle="tooltip" data-placement="top" title="Logo"></li>
                                            <li class="mdi mdi-delete eliminarIcon" onclick="OnClickEliminarEmpresa();" data-toggle="tooltip" data-placement="top" title="Eliminar"></li>
                                            <a href="{{ route('Admin_Company_Directory') }}"><li class="mdi mdi-book-open-page-variant directorioIcon" data-toggle="tooltip" data-placement="top" title="Directorio"></li></a>
                                        </div>
                                        
                                    </td>
                                </tr>    
                            @endfor
                            
                        </tbody>
                    </table> --}}
                </div>

               

                {{-- <div class="contenedorPaginacion">
                    <nav class="pagination">
                        <div class="nav-btn prev"></div>
                        <ul class="nav-pages"></ul>
                        <div class="nav-btn next"></div>
                      </nav>
                </div> --}}
                

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/clientes/establecimientos/main.js') }}"></script>
@endsection