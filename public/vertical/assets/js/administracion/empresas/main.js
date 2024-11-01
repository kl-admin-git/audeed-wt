//EL METODO CARGANDO ESTÁ DIRECTAMENTE EN EL TEMPLATE EN UN SCRIPT
// CargandoMostrar();
// CargandoNoMostrar();
let arrayEstatos = [];
arrayEstatos[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstatos[1] = { claseEstado: 'primary', nombreClase: 'Activo' };
let paginacion = 1;
let arrayFiltros = {};

$(document).ready(function() 
{
    let formulario = $('#formularioCreacionEmpresa').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $("#paisCode").select2({
        dropdownParent: $('#crearEmpresaPopUp')
    });

    $(".selects").select2({
        dropdownParent: $('#crearEmpresaPopUp'),
        dropdownAutoWidth : true
    });

    $(".selectSearch").select2({
        dropdownAutoWidth : true
    });

    $(".select2Simple").select2({dropdownAutoWidth : true});

    // InicializacionPaginacion($('.pagination'), 25, 5);
    IniciarVista(true);
});

function IniciarVista(activarCargando = false) 
{
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
    arrayFiltros['filtro_nit'] = $('.nitSearch').val();
    arrayFiltros['filtro_direccion']=  $('.direccionSearch').val();
    arrayFiltros['filtro_pais'] = $('.paisSearch').val();
    arrayFiltros['filtro_responsable'] = $('.responsableSearch').val();
    
    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/consultaEmpresas',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idCuentaPrincipal:idCuentaPrincipal,
            paginacion:paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            if(activarCargando)
                CargandoMostrar();
        },
        success: function(data) 
        {
            if(activarCargando)
                CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    let stringGeneralTarjetas = '';
                    $.each(data.datos, function (indexInArray, empresa) 
                    { 
                        stringGeneralTarjetas += ComponenteEmpresa(empresa.id,empresa.FOTO,empresa.nombre,empresa.identificacion,empresa.correo,empresa.direccion,empresa.TELEFONO,empresa.CIUDAD,empresa.SECTOR,empresa.RESPONSABLE,empresa.estado,arrayEstatos[empresa.estado].claseEstado,arrayEstatos[empresa.estado].nombreClase);
                    });

                    $('.contenedorEmpresas').html(stringGeneralTarjetas);
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorEmpresas'));
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            if(activarCargando)
                CargandoNoMostrar();
        }
    });
}

function CargarEmpresas() 
{
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
    arrayFiltros['filtro_nit'] = $('.nitSearch').val();
    arrayFiltros['filtro_direccion']=  $('.direccionSearch').val();
    arrayFiltros['filtro_pais'] = $('.paisSearch').val();
    arrayFiltros['filtro_responsable'] = $('.responsableSearch').val();

    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/consultaEmpresasScroll',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idCuentaPrincipal:idCuentaPrincipal,
            paginacion:paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrarFooter();
        },
        success: function(data) 
        {
            CargandoNoMostrarFooter();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    let stringGeneralTarjetas = '';
                    $.each(data.datos, function (indexInArray, empresa) 
                    { 
                        stringGeneralTarjetas += ComponenteEmpresa(empresa.id,empresa.FOTO,empresa.nombre,empresa.identificacion,empresa.correo,empresa.direccion,empresa.TELEFONO,empresa.CIUDAD,empresa.SECTOR,empresa.RESPONSABLE,empresa.estado,arrayEstatos[empresa.estado].claseEstado,arrayEstatos[empresa.estado].nombreClase,'none');
                    });
                                            
                    $(stringGeneralTarjetas).appendTo('.contenedorEmpresas').animate({
                        height: "toggle"
                    }, 500, function() {
                        scrollLoad= true;
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorEmpresas'));
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            CargandoNoMostrarFooter();
        }
    });    
}

function OnClickFormulario(e) 
{ 
    e.preventDefault();
   
    var form = $('#formularioCreacionEmpresa');

    form.parsley().validate();

    if (form.parsley().isValid())
    {
        if($('.sectorPopUp').val() == 0)
        {
            $('.errorSector').removeClass('hidden');
            return;
        }
        else
            $('.errorSector').addClass('hidden');

        if($('.paisPopUp').val() != 0)
        {
            if($('.departamentoPopUp').val() == 0 || $('.ciudadPopUp').val() == 0)
            {
                toastr.warning('Al seleccionar un pais, debes seleccionar el departamento y ciudad');
                return;
            }
        }

        let objetoEnviar = 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nombreEmpresa: $('.nombreEmpresaPopUp').val(),
            nit: $('.nitPopUp').val(),
            correo: $('.corrreoPopUp').val(),
            direccion: $('.direccionPopUp').val(),
            telefono: $('#telefono').val(),
            idPais: $('.paisPopUp').val(),
            idDepartamento: $('.departamentoPopUp').val(),
            idCiudad: $('.ciudadPopUp').val(),
            idSector: $('.sectorPopUp').val(),
            idResponsable: $('.usuarioPopUp').val()
        };

        var formData = new FormData();
        var files = $('#logoEmpresarial')[0].files[0];
        if(files != undefined)
        {
            let isValidImage = ValidarExtensionImagen(files.name);
            if(!isValidImage)
            {
                toastr.warning('La imagen no tiene una extensión valida (PNG,JPG,JPEG)');
                return;
            }
            formData.append('file',files);
        }

        if($(this).attr('accion') == 0) // CREAR
        {
            formData.append('objetoEnviar',JSON.stringify(objetoEnviar));

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/administracion/empresas/crearEmpresa',
                data: formData,
                cache: false,
                processData: false, 
                contentType: false, 
                enctype: "multipart/form-data",
                beforeSend: function() 
                {
                    CargandoMostrar();
                },
                success: function(data) 
                {
                    CargandoNoMostrar();
                    switch (data.codigoRespuesta) 
                    {
                        case 200:
                            toastr.success(data.mensaje);
                            location.reload();
                            // paginacion = 1;
                            // scrollLoad = true;
                            // IniciarVista();
                            // OnClickCerrarCrearEmpresaPopUp();
                            break;
    
                        case 400:
                        case 406:
                            toastr.error(data.mensaje);
                            break;

                        case 407:
                            if(data.datos != 1) // DIFERENTE ADMINISTRADOR
                            {
                                Swal.fire({
                                    title: 'No puedes continuar',
                                    text: data.mensaje,
                                    type: 'warning',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonText: 'Aceptar'
                                }).then(function () {
                                });
                            }else
                            {
                                Swal.fire({
                                    title: 'No puedes continuar',
                                    text: data.mensaje,
                                    type: 'warning',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonText: 'Cambiar plan',
                                    cancelButtonClass: 'btn btn-secondary',
                                    cancelButtonText: 'Cerrar',
                                    showCancelButton: true,
                                }).then(function (response) {
                                    if (response.dismiss == undefined) 
                                    {
                                        let idPlan = $.trim($('#popUpSuscripcion').attr('planActual'));
        
                                        switch (idPlan) {
                                            case '1':
                                                let control = $('.contenedoresPlanes').find('div[idTarjeta="'+idPlan+'"]');
                                                $(control).find('.btn-audeed').addClass('hidden');
                                                break;
    
                                            case '2':
                                            case '3':
                                            case '4':
    
                                                for (let index = idPlan; index > 0; index--) 
                                                {
                                                    let control = $('.contenedoresPlanes').find('div[idTarjeta="'+index+'"]');
                                                    $(control).find('.btn-audeed').addClass('hidden');
                                                }
    
                                                break;
                                                
                                            default:
                                                break;
                                        }
    
                                        $('.iconoCerrarPopUpPlanes').removeClass('hidden');
                                        $('#popUpSuscripcion').modal('show');
                                    }
                                });
                            }
                        break;
                    
                        default:
                            break;
                    }
                    
                },
                error: function(data) 
                {
                    CargandoNoMostrar()
                }
            });
        }
        else if($(this).attr('accion') == 1) // EDITAR
        {
            let idEmpresa = $(this).attr('idEmpresa');;
            objetoEnviar['idEmpresa'] = idEmpresa;
            formData.append('objetoEnviar',JSON.stringify(objetoEnviar));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/administracion/empresas/editarEmpresa',
                data: formData,
                cache: false,
                processData: false, 
                contentType: false, 
                enctype: "multipart/form-data",
                beforeSend: function() 
                {
                    CargandoMostrar();
                },
                success: function(data) 
                {
                    CargandoNoMostrar();
                    switch (data.codigoRespuesta) 
                    {
                        case 201:
                            toastr.success(data.mensaje);
                            let control = $('.contenedorEmpresas').find('div[idtarjeta="'+idEmpresa+'"]');

                            let stringGeneralTarjetas = '';
                            stringGeneralTarjetas += ComponenteEmpresa(data.datos.id,data.datos.FOTO,data.datos.nombre,data.datos.identificacion,data.datos.correo,data.datos.direccion,data.datos.TELEFONO,data.datos.CIUDAD,data.datos.SECTOR,data.datos.RESPONSABLE,1,'primary','Activo');
                            $(control).after(stringGeneralTarjetas);

                            $('[data-toggle="tooltip"]').tooltip();

                            $(control).remove();

                            OnClickCerrarCrearEmpresaPopUp();
                            break;
    
                        case 400:
                        case 406:
                            toastr.error(data.mensaje);
                            break;
                    
                        default:
                            break;
                    }
                    
                },
                error: function(data) 
                {
                    CargandoNoMostrar()
                }
            });
        }
        
    }
}

function OnClickCrearEmpresaPopUp() 
{
    $('#crearEmpresaPopUp').find('.modal-title').html('Creación de empresas');
    $('#crearEmpresaPopUp').find('.crearEmpresa').html('Crear empresa');
    $('#crearEmpresaPopUp').find('.crearEmpresa').attr('accion','0');
    $('#crearEmpresaPopUp').modal('show');
}

function OnClickCerrarCrearEmpresaPopUp() 
{
    $('#crearEmpresaPopUp').modal('hide');
    $('#formularioCreacionEmpresa').find('input[type="text"]').val('');
    $('#formularioCreacionEmpresa').find('input[type="password"]').val('');
    $('#formularioCreacionEmpresa').find('input[type="file"]').val('');
    $('#formularioCreacionEmpresa').find('input[type="email"]').val('');
    $('#formularioCreacionEmpresa').find('select').val(0).change();
    $('#formularioCreacionEmpresa').parsley().reset();
    $('.errorSector').addClass('hidden');
}

function OnClickEliminarEmpresa(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEmpresa = $(TarjetaGeneralControl).attr('idtarjeta');
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminarán los establecimientos y usuarios asginados a la empresa. No se podrán revertir los cambios de la eliminación",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then(function (resultado) {
        console.log(resultado);
        if(resultado.dismiss == 'cancel')
            return;
            $.ajax({
                type: 'POST',
                url: '/administracion/empresas/eliminarEmpresa',
                data: 
                {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    idEmpresa:idEmpresa
                },
                cache: false,
                dataType: 'json',
                beforeSend: function() 
                {
                    CargandoMostrar();
                },
                success: function(data) 
                {
                    CargandoNoMostrar();
                    switch (data.codigoRespuesta) 
                    {
                        case 203:
                            $(TarjetaGeneralControl).remove();
                            Swal.fire(
                                'Eliminado!',
                                data.mensaje,
                                'success'
                            );
                            break;
                        case 406:
                            toastr.error(data.mensaje);
                            break;
                    
                        default:
                            break;
                    }
                    
                },
                error: function(data) 
                {
                    CargandoNoMostrar()
                }
            });
        
        
    });    
}

function OnClickEditarEmpresa(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEmpresa = $(TarjetaGeneralControl).attr('idtarjeta');

    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/consultarEmpresaEdicion',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEmpresa:idEmpresa
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            // CargandoMostrar();
        },
        success: function(data) 
        {
            // CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    $('.nombreEmpresaPopUp').val(data.datos.nombre);
                    $('.nitPopUp').val(data.datos.identificacion);
                    $('.corrreoPopUp').val(data.datos.correo);
                    $('.direccionPopUp').val(data.datos.direccion);
                    $('#telefono').val((data.datos.TELEFONO == 0) ? '' : data.datos.TELEFONO);
                    $('.paisPopUp').val(data.datos.PAIS_ID).change();
                    if(data.datos.PAIS_ID != 0)
                    {
                        var timer;
                        clearTimeout(timer);
                        timer = setTimeout(function(){$('.departamentoPopUp').val(data.datos.DEPARTAMENTO_ID).change();},500);
                        var timer2;
                        clearTimeout(timer2);
                        timer2 = setTimeout(function(){$('.ciudadPopUp').val(data.datos.CIUDAD_ID).change();},1000);
                    }
                    
                    $('.sectorPopUp').val(data.datos.SECTOR).change();
                    $('.usuarioPopUp').val(data.datos.RESPONSABLE).change();

                    $('#crearEmpresaPopUp').find('.modal-title').html('Edición de empresas');
                    $('#crearEmpresaPopUp').find('.crearEmpresa').html('Actualizar');
                    $('#crearEmpresaPopUp').find('.crearEmpresa').attr('accion',1);
                    $('#crearEmpresaPopUp').find('.crearEmpresa').attr('idEmpresa',idEmpresa);
                    $('#crearEmpresaPopUp').modal('show');
                    break;
                case 404:

                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            // CargandoNoMostrar();
        }
    });
}

function OnClickDirectorio() 
{
    
}

function OnClickCambiarEstado(control) 
{
    let controlEstado = $(control);
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEmpresa = $(TarjetaGeneralControl).attr('idtarjeta');
    let estadoActual = $(control).attr('idEstado');
    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/actualizarEstadoEmpresa',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEmpresa:idEmpresa,
            estadoActual:estadoActual
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrar();
        },
        success: function(data) 
        {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 201:
                    $(controlEstado).removeClass('badge-primary').removeClass('badge-danger').addClass('badge-'+arrayEstatos[data.datos].claseEstado).html(arrayEstatos[data.datos].nombreClase);
                    $(controlEstado).attr('idEstado',data.datos);
                    break;
                case 406:
                    toastr.error(data.mensaje);
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            CargandoNoMostrar()
        }
    });
}

function ComponenteEmpresa(
    idTarjeta,
    foto,
    nombreEmpresa,
    nitEmpresa,
    correoEmpresa,
    direccionEmpresa,
    telefonoEmpresa,
    paisCiudad,
    sector,
    responsable,
    idEstado,
    estadoColor,
    estadoTexto,
    display='block'
    ) 
{
    let stringTarjeta = `<div class="col-lg-4" idTarjeta="${idTarjeta}" style="display:${display};">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="media">
                                        <img class="d-flex mr-3 rounded-circle thumb-lg" src="../..${foto}" alt="Generic placeholder image">
                                        <div class="media-body contenedorPrincipal">
                                            <h5 class="m-t-10 mb-1 texto-personalizado ellipseText" data-toggle="tooltip" data-placement="left" title="${nombreEmpresa}">${nombreEmpresa}</h5>
                                            <p class="text-muted m-b-5"><span class="mdi mdi-cards" data-toggle="tooltip" data-placement="left" title="Código"></span> ${nitEmpresa}</p>
                                            <p class="text-muted m-b-5 ellipseText" data-toggle="tooltip" data-placement="top" title="${correoEmpresa}"><span class="mdi mdi-email-outline" data-toggle="tooltip" data-placement="left" title="Correo"></span> ${correoEmpresa}</p>
                                            <p class="text-muted m-b-5"><span class="mdi mdi-cellphone-iphone" data-toggle="tooltip" data-placement="left" title="Teléfono" ></span> ${telefonoEmpresa}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="contenedorGeneral">
                                            <div class="col-lg-12 contenedorSubtitulos">
                                                <p class="ellipseText" data-toggle="tooltip" data-placement="top" title="${direccionEmpresa}"><span><span class="mdi mdi-map-marker"></span> ${direccionEmpresa}</span></p>
                                                <p><span><span class="mdi mdi-earth" data-toggle="tooltip" data-placement="left" title="Pais, Ciudad"></span> ${paisCiudad}</span></p>
                                                <p><span><span class="mdi mdi-google-circles-communities" data-toggle="tooltip" data-placement="left" title="Sector"></span> ${sector}</span></p>
                                                <p class="ellipseText">
                                                    <span class="mdi mdi-account-settings-variant" data-toggle="tooltip" data-placement="top" title="Responsable empresa"></span>
                                                    <span data-toggle="tooltip" data-placement="left" title="${responsable}" class="ellipseText"> ${responsable}</span>
                                                </p>
                                                <div class="contenedorIconosAcciones">
                                                    <div class="contenedorEstados" id="estado-tour">
                                                        <span class="badge badge-pill badge-${estadoColor} badge-custom" onclick="OnClickCambiarEstado(this);" idEstado="${idEstado}">${estadoTexto}</span>
                                                    </div>

                                                    <div class="contenedorBotonesAcciones" id="acciones-tour">
                                                        <a href="/administracion/empresas/directorio/${idTarjeta}" class="directorioLink"><li data-toggle="tooltip" data-placement="top" title="Directorio" class="mdi mdi-account-multiple"></li></a>
                                                        <li data-toggle="tooltip" data-placement="top" title="Editar" onclick="OnClickEditarEmpresa(this);" class="mdi mdi-pen"></li>
                                                        <li data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="OnClickEliminarEmpresa(this);" class="mdi mdi-delete"></li>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>`;

    return stringTarjeta;
}

var scrollLoad = true;
$(window).scroll(function() {
    

    if(isMobile.any() != null)
    {
        if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
            if(scrollLoad)
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarEmpresas();
            }
        }
    }else
    {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 20) 
        {
            if(scrollLoad)
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarEmpresas();
            }
        }
    }
});

function OnChangePais() 
{
    let idPais = $(this).val();
    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/cambioPais',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idPais:idPais
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            // CargandoMostrar();
        },
        success: function(data) 
        {
            // CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    // CARGA DEPARTAMENTOS
                    $('.departamentoPopUp').html('');

                    $('.departamentoPopUp')
                    .append($("<option></option>")
                    .attr("value",0)
                    .text('Selecciona el departamento')); 

                    $.each(data.datos, function (key, value) 
                    { 
                        $('.departamentoPopUp')
                        .append($("<option></option>")
                        .attr("value",value.id)
                        .text(value.nombre)); 
                    });

                    $(".departamentoPopUp").select2({
                        dropdownAutoWidth: !0,
                        width: "100%",
                        dropdownParent: $('#crearEmpresaPopUp')
                    }); 

                     // CARGA CIUDADES
                     $('.ciudadPopUp').html('');

                     $('.ciudadPopUp')
                     .append($("<option></option>")
                     .attr("value",0)
                     .text('Selecciona la ciudad')); 
 
                     $(".ciudadPopUp").select2({
                         dropdownAutoWidth: !0,
                         width: "100%",
                         dropdownParent: $('#crearEmpresaPopUp')
                     }); 
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            // CargandoNoMostrar()
        }
    });
}

function OnChangeDepartamento() 
{
    let idDepartamento = $(this).val();
    $.ajax({
        type: 'POST',
        url: '/administracion/empresas/cambioDepartamento',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idDepartamento:idDepartamento
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            // CargandoMostrar();
        },
        success: function(data) 
        {
            // CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    // CARGA CIUDADES
                    $('.ciudadPopUp').html('');

                    $('.ciudadPopUp')
                    .append($("<option></option>")
                    .attr("value",0)
                    .text('Selecciona la ciudad')); 

                    $.each(data.datos, function (key, value) 
                    { 
                        $('.ciudadPopUp')
                        .append($("<option></option>")
                        .attr("value",value.id)
                        .text(value.nombre)); 
                    });

                    $(".ciudadPopUp").select2({
                        dropdownAutoWidth: !0,
                        width: "100%",
                        dropdownParent: $('#crearEmpresaPopUp')
                    }); 
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            // CargandoNoMostrar()
        }
    });
}

function OnClickRestablecerBusqueda() 
{
    location.reload();
}

function OnClickBuscarBoton() 
{
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

$('#crearEmpresa').on('click',OnClickCrearEmpresaPopUp);
$('.cancelarPopUp').on('click',OnClickCerrarCrearEmpresaPopUp);
$('.crearEmpresa').on('click',OnClickFormulario);
$('.paisPopUp').on('change',OnChangePais);
$('.departamentoPopUp').on('change',OnChangeDepartamento);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);
$('.buscarBoton').on('click',OnClickBuscarBoton);

// PAGINACION
var pageNum = 0, pageOffset = 0;
function InicializacionPaginacion(baseElement, pages, pageShow) 
{
    _initNav(baseElement,pageShow,pages);
}

function _initNav(baseElement,pageShow,pages)
{
    //create pages
    for(i=1;i<pages+1;i++){
        $((i==1?'<li class="active">':'<li>')+(i)+'</li>').appendTo('.nav-pages', baseElement).css('min-width','4em');
    }

    //calculate initial values
    function ow(e){return e.first().outerWidth()}
    var w = ow($('.nav-pages li', baseElement)),bw = ow($('.nav-btn', baseElement));
    baseElement.css('width',w*(pages <= 5 ? pages : pageShow)+(bw*(pages <= 5 ? 2 : 4))+'px');
    $('.nav-pages', baseElement).css('margin-left',bw+'px');

    //init events
    baseElement.on('click', '.nav-pages li, .nav-btn', function(e){
        if($(e.target).is('.nav-btn')){
        var toPage = $(this).hasClass('prev') ? pageNum-1 : pageNum+1;
        }else{
        var toPage = $(this).index(); 
        }
        _navPage(baseElement,pages,toPage,pageShow);
    });
}

function _navPage(baseElement,pages,toPage,pageShow)
{
    var sel = $('.nav-pages li', baseElement), w = sel.first().outerWidth(),
        diff = toPage-pageNum;

    if(toPage>=0 && toPage <= pages-1){
        sel.removeClass('active').eq(toPage).addClass('active');
        pageNum = toPage;
    }else{
        return false;
    }

    if(toPage<=(pages-(pageShow+(diff>0?0:1))) && toPage>=0){
        pageOffset = pageOffset + -w*diff;  
    }else{
        pageOffset = (toPage>0)?-w*(pages-pageShow):0;
    }

    sel.parent().css('left',pageOffset+'px');
}
// PAGINACION - FIN
