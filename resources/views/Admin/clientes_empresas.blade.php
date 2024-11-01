@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/clientes/empresas/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Empresas</h1>
@endsection

@section('section')


<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorBotonCrear">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="crearEmpresa">{{ trans('empresasmessages.buttoncrearempresa') }}</button>
                </div>
                <div class="col-lg-12 m-b-30 contenedorTablaClienteEmpresas">
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
                                    <td>Audiid</td>
                                    <td>5248142554</td>
                                    <td>audiid@audiid.co</td>
                                    <td>Cll 23 # 1-360</td>
                                    <td>3127379236</td>
                                    <td>Tecnología</td>
                                    <td>Diego Meneses</td>
                                    <td>
                                        <div class="contenedorBotonesAcciones">
                                            <li class="mdi mdi-border-color editarIcon" onclick="OnClickEditarEmpresa();" data-toggle="tooltip" data-placement="top" title="Editar"></li>
                                            <li class="mdi mdi-file-image verImagenIcon" data-toggle="tooltip" data-placement="top" title="Logo"></li>
                                            <li class="mdi mdi-delete eliminarIcon" onclick="OnClickEliminarEmpresa();" data-toggle="tooltip" data-placement="top" title="Eliminar"></li>
                                        </div>
                                        
                                    </td>
                                </tr>    
                            @endfor
                            
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


 <!--  MODAL CREAR EMPRESA -->
 <div class="modal fade bs-example-modal-lg" id="crearEmpresaPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">{{ trans('empresasmessages.modalcrearempresa') }}</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <p class="text-muted m-b-30 font-14">{{ trans('empresasmessages.modalsubtitulomensaje') }}</p>

                            <form class="" id="formularioCreacionEmpresa" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajanombreempresa') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nombreEmpresaPopUp" required placeholder="{{ trans('empresasmessages.modalcajaplaceholderempresa') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajanit') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nitPopUp" required placeholder="{{ trans('empresasmessages.modalcajaplaceholdernit') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajacorreo') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control corrreoPopUp" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="{{ trans('empresasmessages.modalcajaplaceholdercorreo') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajadireccion') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control direccionPopUp" required placeholder="{{ trans('empresasmessages.modalcajaplaceholdireccion') }}"/>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajatelefono') }}: </label>
                                            <input type="text" class="form-control telefonoPopUp" placeholder="{{ trans('empresasmessages.modalcajaplaceholdertelefono') }}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajapais') }}:</label>
                                            <select class="form-control paisPopUp">
                                                <option>Selecciona el Pais</option>
                                                <option value="1">Colombia</option>
                                                <option value="2">Alemania</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajadepartamento') }}:</label>
                                            <select class="form-control departamentoPopUp">
                                                <option>Selecciona el departamento</option>
                                                <option value="1">Valle del Cauca</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajaciudad') }}:</label>
                                            <select class="form-control ciudadPopUp">
                                                <option>Selecciona el ciudad</option>
                                                <option value="1">Cali</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajalogoempresa') }}:</label>
                                            <input type="file" data-buttonText="Seleccionar" accept="image/*" class="filestyle" data-buttonname="btn-dark col-lg-12" value="ja">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light crearEmpresa">Crear empresa</button>
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
{{-- MODAL CREAR EMPRESA - FIN --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/clientes/empresas/main.js') }}"></script>
@endsection