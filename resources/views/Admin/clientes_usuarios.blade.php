@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/clientes/usuarios/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Usuarios</h1>
@endsection

@section('section')


<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorBotonCrear">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="crearUsuario">{{ trans('usuariosmessages.buttoncrearusuario') }}</button>
                </div>
                <div class="col-lg-12 m-b-30 contenedorTablaUsuarios">
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
                                <th>{{ trans('usuariosmessages.tabnombre') }}</th>
                                <th>{{ trans('usuariosmessages.tabapellidos') }}</th>
                                <th>{{ trans('usuariosmessages.tabcorreo') }}</th>
                                <th>{{ trans('usuariosmessages.tabnombreusuario') }}</th>
                                <th>{{ trans('usuariosmessages.tabtelefono') }}</th>
                                <th>{{ trans('usuariosmessages.tabcargo') }}</th>
                                <th>{{ trans('usuariosmessages.tabperfil') }}</th>
                                <th>{{ trans('usuariosmessages.tabacciones') }}</th>
                            </tr>
                        </thead>
    
    
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td>Roney Sebastian</td>
                                    <td>Rodriguez Huertas</td>
                                    <td>roney.rodriguez@hotmail.com</td>
                                    <td>ronrodro</td>
                                    <td>3127379236</td>
                                    <td>Desarrollador Web</td>
                                    <td>Full Stack Developer</td>
                                    <td>
                                        <div class="contenedorBotonesAcciones">
                                            <li class="mdi mdi-border-color editarIcon" onclick="OnClickEditarUsuario();" data-toggle="tooltip" data-placement="top" title="Editar"></li>
                                            <li class="mdi mdi-file-image verImagenIcon" data-toggle="tooltip" data-placement="top" title="Ver imagen"></li>
                                            <li class="mdi mdi-delete eliminarIcon" onclick="OnClickEliminarUsuario();" data-toggle="tooltip" data-placement="top" title="Eliminar"></li>
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


 <!--  MODAL CREAR USUARIO -->
 <div class="modal fade bs-example-modal-lg" id="crearUsuarioPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">{{ trans('usuariosmessages.modalcrearusuario') }}</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <p class="text-muted m-b-30 font-14">{{ trans('usuariosmessages.modalsubtitulomensaje') }}</p>

                            <form class="" id="formularioCreacion" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajanombres') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nombrePopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholdernombres') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaapellidos') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control apellidosPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderapellidos') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajacorreo') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control corrreoPopUp" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="{{ trans('usuariosmessages.modalcajaplaceholdercorreo') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajausuario') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control usuarioPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderusuario') }}"/>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajapassword') }}: <span class="requerido">*</span></label>
                                            <input type="password" class="form-control passwordPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderpassword') }}"/>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaconfirmacionpassword') }}: <span class="requerido">*</span></label>
                                            <input type="password" class="form-control confirmacionPasswordPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderconfirmacionpassword') }}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaperfil') }}: <span class="requerido">*</span></label>
                                            <select class="form-control perfilPopUp">
                                                <option>Selecciona el perfil</option>
                                                <option value="1">Administrador</option>
                                                <option value="2">Auditor</option>
                                                <option value="3">Cliente</option>
                                                <option value="4">Socio</option>
                                            </select>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajatelefono') }}:</label>
                                            <input type="text" class="form-control telefonoPopUp" data-parsley-type="integer" data-parsley-error-message="Debes digitar un número valido" placeholder="{{ trans('usuariosmessages.modalcajaplaceholdertelefono') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajacargo') }}:</label>
                                            <select class="form-control cargoPopUp">
                                                <option>Selecciona el cargo</option>
                                                <option value="1">Administrador</option>
                                                <option value="2">Auditor</option>
                                                <option value="3">Cliente</option>
                                                <option value="4">Socio</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajapais') }}:</label>
                                            <select class="form-control paisPopUp">
                                                <option>Selecciona el Pais</option>
                                                <option value="1">Colombia</option>
                                                <option value="2">Alemania</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajadepartamento') }}:</label>
                                            <select class="form-control departamentoPopUp">
                                                <option>Selecciona el departamento</option>
                                                <option value="1">Valle del Cauca</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaciudad') }}:</label>
                                            <select class="form-control ciudadPopUp">
                                                <option>Selecciona el ciudad</option>
                                                <option value="1">Cali</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajafotoperfil') }}:</label>
                                            <input type="file" data-buttonText="Seleccionar" accept="image/*" class="filestyle" data-buttonname="btn-dark col-lg-12" value="ja">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light crearUsuario">Crear usuario</button>
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
{{-- MODAL CREAR USUARIO - FIN --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/clientes/usuarios/main.js') }}"></script>
@endsection