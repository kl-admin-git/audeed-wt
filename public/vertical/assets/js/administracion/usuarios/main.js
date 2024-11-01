
let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstados[1] = { claseEstado: 'primary', nombreClase: 'Activo' };
let paginacion = 1;
let arrayFiltros = {};

$(document).ready(function() 
{
    let formulario = $('#formularioCreacion').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    formulario.options.equaltoMessage = "Las contraseña no coinciden";
    
    // Select2
    $(".select2").select2({
    });

    // InicializacionPaginacion($('.pagination'), 25, 5);
    IniciarVista(true);
});

function IniciarVista(activarCargando = false) 
{
    arrayFiltros['filtro_nombre_usuario'] = $('.usuarioSearch').val();
    arrayFiltros['filtro_correo'] = $('.correoSearch').val();
    arrayFiltros['filtro_cargo']=  $('.cargoSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();

    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');

    $.ajax({
        type: 'POST',
        url: '/administracion/usuarios/consultaUsuarios',
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
                    $.each(data.datos, function (indexInArray, usuario) 
                    { 
                        let responsabilidadTexto = '';
                        if(usuario.ES_RESPONSABLE_EMPRESA != "")
                            responsabilidadTexto = 'Responsable empresa de '+usuario.ES_RESPONSABLE_EMPRESA;
                        else if(usuario.ES_RESPONSABLE_ESTABLECIMIENTO != '')
                            responsabilidadTexto = 'Responsable establecimiento de '+usuario.ES_RESPONSABLE_ESTABLECIMIENTO;
                        
                        stringGeneralTarjetas += ComponenteUsuario(usuario.id,usuario.nombre_completo,usuario.correo,usuario.TELEFONO,usuario.identificacion,(usuario.CARGO +', '+usuario.PERFIL),(usuario.EMPRESA+', '+usuario.ESTABLECIMIENTO),usuario.CIUDAD,usuario.USUARIO,usuario.PASSWORD,usuario.FOTO,usuario.estado,arrayEstados[usuario.estado].claseEstado,arrayEstados[usuario.estado].nombreClase,responsabilidadTexto);
                    });

                    $('.contenedorUsuario').html(stringGeneralTarjetas);
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorUsuario'));
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

function CargarUsuarios() 
{
    arrayFiltros['filtro_nombre_usuario'] = $('.usuarioSearch').val();
    arrayFiltros['filtro_correo'] = $('.correoSearch').val();
    arrayFiltros['filtro_cargo']=  $('.cargoSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();

    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/usuarios/consultaUsuarioScroll',
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
                    $.each(data.datos, function (indexInArray, usuario) 
                    { 
                        let responsabilidadTexto = '';
                        if(usuario.ES_RESPONSABLE_EMPRESA != "")
                            responsabilidadTexto = 'Responsable empresa de '+usuario.ES_RESPONSABLE_EMPRESA;
                        else if(usuario.ES_RESPONSABLE_ESTABLECIMIENTO != '')
                            responsabilidadTexto = 'Responsable establecimiento de '+usuario.ES_RESPONSABLE_ESTABLECIMIENTO;

                        stringGeneralTarjetas += ComponenteUsuario(usuario.id,usuario.nombre_completo,usuario.correo,usuario.TELEFONO,usuario.identificacion,(usuario.CARGO +', '+usuario.PERFIL),(usuario.EMPRESA+', '+usuario.ESTABLECIMIENTO),usuario.CIUDAD,usuario.USUARIO,usuario.PASSWORD,usuario.FOTO,usuario.estado,arrayEstados[usuario.estado].claseEstado,arrayEstados[usuario.estado].nombreClase,responsabilidadTexto,'none');
                    });
                                            
                    $(stringGeneralTarjetas).appendTo('.contenedorUsuario').animate({
                        height: "toggle"
                    }, 500, function() {
                        scrollLoad= true;
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorUsuario'));
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

function ComponenteUsuario(
    idUsuario,
    nombreColaborador,
    correo,
    telefono,
    identificacion,
    cargoPerfil,
    empresaEstablecimiento,
    paisCiudad,
    usuario,
    password,
    foto,
    idEstado,
    estadoColor,
    estadoTexto,
    responsabilidadTexto,
    display='block') 
{
    let estado = '';
    let eliminar = '';
    if($('.datosUsuario').attr('idUsuario') != idUsuario)
    {
        estado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" onclick="OnClickCambiarEstado(this);" idEstado="${idEstado}">${estadoTexto}</span>`;
        eliminar = `<li data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="OnClickEliminarUsuario(this);" class="mdi mdi-delete"></li>`;
    }        
    
    let stringIconoResponsable = '';
    if(responsabilidadTexto != '')
        stringIconoResponsable = `<span class="mdi mdi-account-settings-variant m-l-10" style="color: #4FB648;" data-toggle="tooltip" data-placement="top" title="${responsabilidadTexto}"></span>`;

    let stringTarjeta = `<div class="col-lg-4" style="display:${display}" idTarjetaUsuario="${idUsuario}">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="media">
                                        <img class="d-flex mr-3 rounded-circle thumb-lg" src="../..${foto}" alt="Generic placeholder image">
                                        <div class="media-body contenedorPrincipal">
                                            <h5 class="m-t-10 mb-1 texto-personalizado">${nombreColaborador}</h5>
                                            <p class="text-muted m-b-5 ellipseText" data-toggle="tooltip" data-placement="top" title="${correo}"><span class="mdi mdi-email-outline" data-toggle="tooltip" data-placement="left" title="Correo"></span> ${correo}</p>
                                            <p class="text-muted m-b-5"> <span class="mdi mdi-cellphone-iphone" data-toggle="tooltip" data-placement="left" title="Teléfono"></span>${telefono}</p>
                                            <p class="text-muted font-14 font-500 font-secondary"><span class="mdi mdi-account-card-details" data-toggle="tooltip" data-placement="left" title="Identificación"></span> ${identificacion}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="contenedorGeneral">
                                            <div class="col-lg-12 contenedorSubtitulos">
                                                <p><span class="mdi mdi-account-card-details" data-toggle="tooltip" data-placement="left" title="Cargo, perfil"></span> ${cargoPerfil}</p>
                                                <p><span><span class="mdi mdi-factory" data-toggle="tooltip" data-placement="left" title="Empresa, establecimiento"></span> ${empresaEstablecimiento}</span> ${stringIconoResponsable} </p>
                                                <p><span><span class="mdi mdi-earth" data-toggle="tooltip" data-placement="left" title="Pais, Ciudad"></span> ${paisCiudad}</span></p>
                                                <div class="contenedorPassword">
                                                    <span class="ellipseText hidden" style="width:50%;"> <i data-toggle="tooltip" data-placement="left" title="Usuario" class="mdi mdi-account"></i>${(usuario == undefined ? "Sin asignar" : usuario)}</span>
                                                    <span><i data-toggle="tooltip" data-placement="top" title="Contraseña" class="mdi mdi-key" password="${password}" isPassword="true" onclick="VisualizarPassword(this);"></i><span class="textopassword noselect">******</span></span>
                                                </div>
                                                <div class="contenedorIconosAcciones" id="acciones-estado">
                                                    <div class="contenedorEstados">
                                                    ${estado}
                                                    </div>

                                                    <div class="contenedorBotonesAcciones" id="acciones-tour">
                                                        <li data-toggle="tooltip" data-placement="top" title="Editar" onclick="OnClickEditarUsuario(this);" class="mdi mdi-pen"></li>
                                                        ${eliminar}
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
                CargarUsuarios();
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
                CargarUsuarios();
            }
        }
    }
});

function OnClickFormulario(e) 
{ 
    e.preventDefault();
    var form = $('#formularioCreacion');

    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        if($('.perfilPopUp').val() == 0)
        {
            $('.errorPerfil').removeClass('hidden');
            return;
        }
        else
            $('.errorPerfil').addClass('hidden');

        if($('.establecimientoPopUp').val() == 0)
        {
            $('.errorEstablecimiento').removeClass('hidden');
            return;
        }
        else
            $('.errorEstablecimiento').addClass('hidden');

        let objetoEnviar = 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nombres: $('.nombrePopUp').val(),
            identificacion: $('.identificacionPopUp').val(),
            correo: $('.corrreoPopUp').val(),
            // usuario: $('.usuarioPopUp').val(),
            password: $('#passwordUsuario').val(),
            perfilId: $('.perfilPopUp').val(),
            establecimientoId: $('.establecimientoPopUp').val(),
            telefono: $('.telefonoPopUp').val(),
            idCargo: $('.cargoPopUp').val(),
        };

        var formData = new FormData();
        var files = $('#avatarUsuario')[0].files[0];
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
                url: '/administracion/usuarios/crearUsuario',
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
            let idUsuario = $(this).attr('idUsuario');
            objetoEnviar['idUsuario'] = idUsuario;
            formData.append('objetoEnviar',JSON.stringify(objetoEnviar));

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/administracion/usuarios/editarUsuario',
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
                            let control = $('.contenedorUsuario').find('div[idtarjetausuario="'+idUsuario+'"]');
                            console.log(control);
                            let stringGeneralTarjetas = '';

                            let responsabilidadTexto = '';
                            if(data.datos.ES_RESPONSABLE_EMPRESA != "")
                                responsabilidadTexto = 'Responsable empresa de '+data.datos.ES_RESPONSABLE_EMPRESA;
                            else if(data.datos.ES_RESPONSABLE_ESTABLECIMIENTO != '')
                                responsabilidadTexto = 'Responsable establecimiento de '+data.datos.ES_RESPONSABLE_ESTABLECIMIENTO;

                            stringGeneralTarjetas += ComponenteUsuario(data.datos.id,data.datos.nombre_completo,data.datos.correo,data.datos.TELEFONO,data.datos.identificacion,(data.datos.CARGO +', '+data.datos.PERFIL),(data.datos.EMPRESA+', '+data.datos.ESTABLECIMIENTO),data.datos.CIUDAD,data.datos.USUARIO,data.datos.PASSWORD,data.datos.FOTO,data.datos.estado,arrayEstados[data.datos.estado].claseEstado,arrayEstados[data.datos.estado].nombreClase,responsabilidadTexto);
                            $(control).after(stringGeneralTarjetas);

                            $('[data-toggle="tooltip"]').tooltip();

                            $(control).remove();

                            OnClickCerrarCrearUsuarioPopUp();
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

function OnClickCrearUsuarioPopUp() 
{
    $('.requeridoPassword').removeClass('hidden');
    $('.perfilPopUp').parent().removeClass('hidden');
    $('#passwordUsuario').attr('required',true);
    $('#confirmPassword').attr('required',true);
    $('#crearUsuarioPopUp').find('.modal-title').html('Crear usuarios');
    $('#crearUsuarioPopUp').find('.crearUsuario').html('Crear usuario');
    $('#crearUsuarioPopUp').find('.crearUsuario').attr('accion',0);
    $('#crearUsuarioPopUp').modal('show');
}

function OnClickCerrarCrearUsuarioPopUp() 
{
    $('#crearUsuarioPopUp').modal('hide');
    $('#formularioCreacion').find('input[type="text"]').val('');
    $('#formularioCreacion').find('input[type="password"]').val('');
    $('#formularioCreacion').find('input[type="file"]').val('');
    $('#formularioCreacion').find('select').val(0).change();
    $('#formularioCreacion').parsley().reset();
}

function OnClickEliminarUsuario(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idUsuario = $(TarjetaGeneralControl).attr('idtarjetausuario');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "No se podrán revertir los cambios de la eliminación",
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
                url: '/administracion/usuarios/eliminarUsuario',
                data: 
                {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    idUsuario:idUsuario
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

function OnClickEditarUsuario(control) 
{

    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idUsuario = $(TarjetaGeneralControl).attr('idtarjetausuario');

    $.ajax({
        type: 'POST',
        url: '/administracion/usuarios/consultarUsuarioEdicion',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idUsuario:idUsuario
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
                    if(idUsuario == $('.datosUsuario').attr('idUsuario'))
                        $('.perfilPopUp').parent().addClass('hidden');
                    else
                        $('.perfilPopUp').parent().removeClass('hidden');

                    $('.requeridoPassword').addClass('hidden');
                    $('#passwordUsuario').removeAttr('required');
                    $('#confirmPassword').removeAttr('required');
                    
                    $('.nombrePopUp').val(data.datos.nombre_completo);
                    $('.perfilPopUp ').val(data.datos.PERFIL).change();
                    $('.corrreoPopUp').val(data.datos.correo);
                    $('.identificacionPopUp').val(data.datos.identificacion);
                    $('.telefonoPopUp').val((data.datos.TELEFONO == 0) ? '' : data.datos.TELEFONO);
                    $('.usuarioPopUp').val(data.datos.USUARIO);
                    $('.establecimientoPopUp').val(data.datos.ESTABLECIMIENTO).change();
                    $('.cargoPopUp ').val(data.datos.CARGO).change();

                    $('#crearUsuarioPopUp').find('.modal-title').html('Editar usuarios');
                    $('#crearUsuarioPopUp').find('.crearUsuario').html('Actualizar');
                    $('#crearUsuarioPopUp').find('.crearUsuario').attr('accion',1);
                    $('#crearUsuarioPopUp').find('.crearUsuario').attr('idUsuario',idUsuario);
                    $('#crearUsuarioPopUp').modal('show');

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

function VisualizarPassword(control) 
{
    let isPassword =  $(control).attr('ispassword');
    let textoPassword =  $(control).attr('password');
    let controlTexto = $(control).parent().find('.textopassword');
    console.log($(control).parent());
    if(isPassword == 'true')
    {
        $(control).attr('ispassword','false');
        $(controlTexto).html(textoPassword);
    }else
    {
        $(control).attr('ispassword','true');
        $(controlTexto).html('*******');
    }
}

function OnClickRestablecerBusqueda() 
{
    location.reload();
}

function OnClickCambiarEstado(control) 
{
    let controlEstado = $(control);
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idUsuario = $(TarjetaGeneralControl).attr('idtarjetausuario');
    let estadoActual = $(control).attr('idEstado');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '/administracion/usuarios/actualizarEstadoUsuario',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idUsuario:idUsuario,
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
                    $(controlEstado).removeClass('badge-primary').removeClass('badge-danger').addClass('badge-'+arrayEstados[data.datos].claseEstado).html(arrayEstados[data.datos].nombreClase);
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

function OnClickBuscarBoton() 
{
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

$('#crearUsuario').on('click',OnClickCrearUsuarioPopUp);
$('.cancelarPopUp').on('click',OnClickCerrarCrearUsuarioPopUp);
$('.crearUsuario').on('click',OnClickFormulario);
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