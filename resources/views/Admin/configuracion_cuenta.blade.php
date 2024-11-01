@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/configuracion/cuenta/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Cuenta</h1>
@endsection

@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                
                <div class="col-lg-12 m-b-30">
                    <div class="form-group row">
                        <label class="col-lg-2">Información de la cuenta</label>
                        <div class="col-lg-10">
                            <span class="badge badge-success badge-custom botonCambiarInformacion">Cambiar</span>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-lg-2">Titular</span>
                        <div class="col-lg-10">
                            <span class="nombreEstatico">{{ auth()->user()->nombre_completo }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-lg-2">Correo principal</span>
                        <div class="col-lg-10">
                            <span class="correoEstatico">{{ auth()->user()->correo }} </span>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-lg-2">Contraseña</span>
                        <div class="col-lg-10">
                            <span>***********</span>
                        </div>
                    </div>
                    <div class="row">
                        <span class="col-lg-2">Tutorial</span>
                        <div class="col-lg-10">
                            <span class="badge badge-success badge-custom reiniciar-tour">Reiniciar</span>
                        </div>
                    </div>
                  
                </div>

                {{-- <div class="col-lg-12 m-b-30">
                    <div class="form-group row">
                        <label class="col-lg-2">Información plan actual</label>
                        <div class="col-lg-10">
                            <span class="badge badge-success badge-custom cambiaPlanCuenta">Cambiar</span>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-lg-12 plan" idPlan="{{ ISSET($cuentaPrincipal->ID_PLAN) ? $cuentaPrincipal->ID_PLAN : '' }} ">{{ ISSET($cuentaPrincipal->PLAN) ? $cuentaPrincipal->PLAN : '' }}</span>
                    </div>
                  
                </div> --}}

                <div class="col-lg-12 m-b-30 hidden">
                    <div class="form-group row">
                        <label class="col-lg-2">Información de facturación</label>
                        <div class="col-lg-10">
                            <span class="badge badge-success badge-custom">Cambiar</span>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-lg-12">Banco Davivienda</span>
                    </div>

                    <div class="row">
                        <span class="col-lg-12">Tarjeta de crédito <span class="numeroTarjeta">**** **** **** **25</span> </span>
                    </div>

                    <div class="row">
                        <span class="col-lg-12">Próxima factura: <span class="fechaFatura">20 sep 2020</span></span>
                    </div>
                  
                </div>

                <div class="col-lg-12 m-b-30 contenedorTabla">
                    <table id="tablaPagosRecurrentes" class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Plan</th>
                                <th>Periodo</th>
                                <th>Forma de pago</th>
                                <th>Total</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            
                        </tbody>
                    </table>
                  
                    <div class="contenedorPaginacion">
                        <nav class="pagination">
                            <div class="nav-btn prev"></div>
                            <ul class="nav-pages"></ul>
                            <div class="nav-btn next"></div>
                        </nav>
                    </div>
                    
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

 <!--  MODAL CREACION EDICION CARGO  -->
 <div class="modal fade bs-example-modal-lg" id="actualizarInformacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Actualizar información</h5>
                <button type="button" class="close cerrarActualizacionInformacion" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="formularioActualizarInformacion">

                    <div class="form-group">
                        <label for="inputNombre">Nombre:</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->nombre_completo }}" id="inputNombrePopUp" required placeholder="Ingrese su nombre">
                    </div>

                    <div class="form-group">
                        <label for="inputCorreoPopUp">Correo electrónico:</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->correo }}" id="inputCorreoPopUp" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="Ingrese su correo electrónico">
                    </div>

                    <div class="form-group">
                        <label>Contraseña:</label>
                        <div>
                            <input type="password" id="passwordPopCtaPrincipal" class="form-control"
                            data-parsley-minlength="8" data-parsley-minlength-message = "El valor es muy corto. Debería tener 8 caracteres o más." placeholder="Contraseña"/>
                        </div>
                        <div class="m-t-10">
                            <input type="password" class="form-control"
                                   data-parsley-equalto="#passwordPopCtaPrincipal" data-parsley-minlength="8" data-parsley-minlength-message = "El valor es muy corto. Debería tener 8 caracteres o más."
                                   placeholder="Vuelva a escribir la contraseña"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary waves-effect m-l-5 guardarInformacion" accion="0" >Guardar</button>
                            <button type="button" class="btn btn-secondary waves-effect m-l-5 cerrarActualizacionInformacion">Cerrar</button>
                        </div>
                    </div>    
                    
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREACION EDICION CARGO  - FIN --}}

@endsection
<script text="text/javascript">
        
    let usuarioId = @json(auth()->user()->id);
</script>

@section('script')
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/configuracion/cuentas/main.js') }}"></script>
@endsection