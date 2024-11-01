let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstados[1] = { claseEstado: 'primary', nombreClase: 'Activo' };
let paginacion = 1;
let arrayFiltros = {};
let paginador = {start: 1, last: 1} //AH

$(document).ready(function () 
{
    let formulario = $('#formularioCreacionEstablecimiento').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2();
    $('.paisPopUp').select2({dropdownParent: $('#crearEstablecimientoPopUp'),});
    IniciarVista(true);
});

// ----------- MODAL PARA VER USUARIOS -------------------
//Limpio la tabla cuando el modal se oculta
$('#verUsuarios').on('hide.bs.modal', function (e) {
    $('.pagination').empty()
    $('#tdatos').empty()
})
//Se ejecuta cuando se activa el modal para ver establecimientos
$('#verUsuarios').on('shown.bs.modal', function (e) {
   
    paginador.start = 0
    paginador.last = 0
    
     let idEstablecimiento = $(e.relatedTarget).attr('idEstablecimiento')
     if(idEstablecimiento == undefined || idEstablecimiento == null)
        return 0

     $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/consultaColaboradores' ,
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEstablecimiento: idEstablecimiento
        },
        cache: false,
        dataType: 'json',
        success: function (resp) {
            console.log(resp)
            let info = resp.datos
            let tbody = ''
           console.log(resp)
            if(resp.status == 200){
                info.data.forEach(el=>{
                    tbody += ` <tr>
                                    <td >${el.nombre}</td>
                                    <td>${el.perfil}</td>
                                    <td>${el.cargo}</td>
                              </tr>`
                })
            }
            $('#tdatos').append(tbody)

            //------- FUNCION PARA PAGINAR -----------------
            if(parseInt(info.current_page) < parseInt(info.last_page)){
                $('.menu').show()
                paginador.start = info.current_page
                paginador.last = info.last_page

                let paginas = ''
                for(let i=0; i < paginador.last; i++){
                    paginas += `<li class="page-item pageBtn">
                                    <a href="#" class="page-link" page="${i+1}">${i+1}</a>
                                </li>`
                }
            
                $('.pagination').append(paginas)

                //Registros si hay clic en los botones
                $('.page-link').click(function(){
                    let page = $(this).attr('page')
                    cambioPagina(page,idEstablecimiento)
                })
                 //------- FIN FUNCION PARA PAGINAR -----------------
            }

        },
        error: function (data) {
          
        }
     })
})

//Funcion para cambio de pagina para el Modal de Establecimientos
function cambioPagina(page, idEstablecimiento){
    $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/consultaColaboradores?page=' + page ,
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEstablecimiento: idEstablecimiento
        },
        cache: false,
        dataType: 'json',
        success: function (resp) {
            $('#tdatos').empty()
            let info = resp.datos
            let tbody = ''
            if(resp.status == 200){
                info.data.forEach(el=>{
                    tbody += ` <tr>
                                <td >${el.nombre}</td>
                                <td>${el.perfil}</td>
                                <td>${el.cargo}</td>
                              </tr>`
                })
            }
            $('#tdatos').append(tbody)


        },
        error: function (data) {
          
        }
     })
}

// ----------- FIN MODAL PARA VER USUARIOS -------------------

function IniciarVista(activarCargando = false) 
{
    arrayFiltros['filtro_nombre_establecimiento'] = $('.establecimientoSearch').val();
    arrayFiltros['filtro_codigo'] = $('.codigoSearch').val();
    arrayFiltros['filtro_correo']=  $('.correoSearch').val();
    arrayFiltros['filtro_ciudad'] = $('.ciudadSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
    arrayFiltros['filtro_zona'] = $('.zonaSearch').val();
    arrayFiltros['filtro_responsable'] = $('.responsableSearch').val();
    
    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/consultaEstablecimientos',
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
                    $.each(data.datos, function (indexInArray, establecimiento) 
                    { 
                        
                        stringGeneralTarjetas += ComponenteEstablecimiento(establecimiento.id,establecimiento.nombre,establecimiento.codigo,establecimiento.RESPONSABLE,establecimiento.EMPRESA,establecimiento.ZONA,establecimiento.direccion,establecimiento.correo,establecimiento.TELEFONO,establecimiento.CIUDAD,establecimiento.estado,arrayEstados[establecimiento.estado].claseEstado,arrayEstados[establecimiento.estado].nombreClase, establecimiento.colaboradores);
                    });

                    $('.contenedorEstablecimientos').html(stringGeneralTarjetas);
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorEstablecimientos'));
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

function ComponenteEstablecimiento(
    idEstablecimiento,
    nombreEstablecimiento,
    codigo,
    responsable,
    empresa,
    zona,
    direccion,
    correo,
    telefono,
    paisCiudad,
    idEstado,
    estadoColor,
    estadoTexto,
    cantColaboradores,
    display='block'
    ) 
{

    let stringEStado = '';
    let stringIconos = '';
    if(perfilExacto == 1 || perfilExacto == 2)
    {
        stringEStado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" onclick="OnClickCambiarEstado(this);" idEstado="${idEstado}">${estadoTexto}</span>`;
        stringIconos = `
            <li data-toggle="tooltip" data-placement="top" title="Editar" onclick="OnClickEditarEstablecimiento(this);" class="mdi mdi-pen"></li>
            <li data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="OnClickEliminarEstablecimiento(this);" class="mdi mdi-delete"></li>
        `;
    }else
    {
        stringEStado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" style="cursor: not-allowed!important;" idEstado="${idEstado}">${estadoTexto}</span>`;
        stringIconos = `
            <li disabled style="cursor: not-allowed;" class="mdi mdi-pen"></li>
            <li disabled style="cursor: not-allowed;" class="mdi mdi-delete"></li>
        `;
    }

    let stringTarjeta = `<div class="col-lg-4" style="display:${display};" idTarjetaEstablecimiento="${idEstablecimiento}">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body contenedorPrincipal">
                                            <h5 class="m-t-10 mb-1 texto-personalizado ellipseText" data-toggle="tooltip" data-placement="left" title="${nombreEstablecimiento}">${nombreEstablecimiento}</h5>
                                            <p class="m-b-5"><span class="mdi mdi-cards" data-toggle="tooltip" data-placement="left" title="Código"></span> ${codigo}</p>
                                            <p class="m-b-5"><span class="mdi mdi-email-outline" data-toggle="tooltip" data-placement="left" title="Correo"></span> ${correo}</p>
                                            <p class="m-b-5"><span class="mdi mdi-cellphone-iphone" data-toggle="tooltip" data-placement="left" title="Teléfono"></span> ${telefono}</p>
                                            <p class="m-b-5 ellipseText" data-toggle="tooltip" data-placement="top" title="${direccion}"><span> <span class="mdi mdi-map-marker"></span>${direccion}</span></p>
                                            <p class="m-b-5"><span class="ellipseText"> <span class="mdi mdi-earth" data-toggle="tooltip" data-placement="left" title="Pais, Ciudad"></span> ${paisCiudad}</span></p>
                                            <a href="#" id="linkEstablecimientos" idEstablecimiento="${idEstablecimiento}" data-toggle="modal" data-target="#verUsuarios"> <p class="m-b-5"><span class="mdi mdi-account-multiple" data-toggle="tooltip" data-placement="left" title="Colaboradores"></span> Colaboradores: <span class="badge badge-success">${cantColaboradores}</span></p></a>
                                            <p class="m-b-5"><span class="mdi mdi-store" data-toggle="tooltip" data-placement="left" title="Zona"></span> Zona: ${zona}</p>
                                            <p class="m-b-5"><span class="mdi mdi-factory" data-toggle="tooltip" data-placement="left" title="Empresa"></span> ${empresa}</p>
                                            <p class="m-b-5"><span data-toggle="tooltip" data-placement="left" title="Responsable establecimiento" class="mdi mdi-account-settings-variant" ></span> ${responsable}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="contenedorGeneral">
                                            <div class="col-lg-12 contenedorSubtitulos">
                                                <div class="contenedorIconosAcciones">
                                                    <div class="contenedorEstados" id="acciones-estado">
                                                        ${stringEStado}
                                                    </div>

                                                    <div class="contenedorBotonesAcciones" id="acciones-tour">
                                                        ${stringIconos}
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

function OnClickFormulario(e) 
{ 
    e.preventDefault();
   
    var form = $('#formularioCreacionEstablecimiento');

    form.parsley().validate();

    if (form.parsley().isValid())
    {
        if($('.empresaPopUp').val() == 0)
        {
            $('.errorEmpresa').removeClass('hidden');
            return;
        }
        else
            $('.errorEmpresa').addClass('hidden');

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
            nombreEstablecimiento: $('.nombreEstablecimientoPopUp').val(),
            codigo: $('.codigoPopUp').val(),
            correo: $('.corrreoPopUp').val(),
            direccion: $('.direccionPopUp').val(),
            telefono: $('#telefono').val(),
            idPais: $('.paisPopUp').val(),
            idDepartamento: $('.departamentoPopUp').val(),
            idCiudad: $('.ciudadPopUp').val(),
            idEmpresa: $('.empresaPopUp').val(),
            idZona: $('.zonaPopUp').val(),
            idResponsable: $('.usuarioPopUp').val()
        };

        if($(this).attr('accion') == 0) // CREAR
        {
            $.ajax({
                type: 'POST',
                url: '/administracion/establecimiento/crearEstablecimiento',
                data: objetoEnviar,
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
            let idEstablecimiento = $(this).attr('idEstablecimiento');;
            objetoEnviar['idEstablecimiento'] = idEstablecimiento;
            $.ajax({
                type: 'POST',
                url: '/administracion/establecimiento/editarEmpresa',
                data: objetoEnviar,
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
                            toastr.success(data.mensaje);
                            let control = $('.contenedorEstablecimientos').find('div[idtarjetaestablecimiento="'+idEstablecimiento+'"]');
                    
                            let stringGeneralTarjetas = '';
                            stringGeneralTarjetas += ComponenteEstablecimiento(data.datos.id,data.datos.nombre,data.datos.codigo,data.datos.RESPONSABLE,data.datos.EMPRESA,data.datos.ZONA,data.datos.direccion,data.datos.correo,data.datos.TELEFONO,data.datos.CIUDAD,data.datos.estado,arrayEstados[data.datos.estado].claseEstado,arrayEstados[data.datos.estado].nombreClase,data.datos.colaboradores);
                            $(control).after(stringGeneralTarjetas);

                            $('[data-toggle="tooltip"]').tooltip();

                            $(control).remove();

                            OnClickCerrarCrearEstablecimientoPopUp();
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

function OnClickCrearEstablecimientoPopUp() 
{
    $('#crearEstablecimientoPopUp').find('.modal-title').html('Creación de establecimiento');
    $('#crearEstablecimientoPopUp').find('.crearEstablecimiento').html('Crear establecimiento');
    $('#crearEstablecimientoPopUp').find('.crearEstablecimiento').attr('accion','0');
    $('#crearEstablecimientoPopUp').modal('show');
}

function OnClickCerrarCrearEstablecimientoPopUp() 
{
    $('#crearEstablecimientoPopUp').modal('hide');
    $('#formularioCreacionEstablecimiento').find('input[type="text"]').val('');
    $('#formularioCreacionEstablecimiento').find('input[type="password"]').val('');
    $('#formularioCreacionEstablecimiento').find('input[type="file"]').val('');
    $('#formularioCreacionEstablecimiento').find('input[type="email"]').val('');
    $('#formularioCreacionEstablecimiento').find('select').val(0).change();
    $('#formularioCreacionEstablecimiento').parsley().reset();
    $('.errorEmpresa').addClass('hidden');
}

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
                        dropdownParent: $('#crearEstablecimientoPopUp')
                    }); 

                     // CARGA CIUDADES
                     $('.ciudadPopUp').html('');

                     $('.ciudadPopUp')
                     .append($("<option></option>")
                     .attr("value",0)
                     .text('Selecciona la ciudad')); 
 
                     $(".ciudadPopUp").select2({
                        dropdownParent: $('#crearEstablecimientoPopUp')
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
                        dropdownParent: $('#crearEstablecimientoPopUp')
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

function OnClickCambiarEstado(control) 
{
    let controlEstado = $(control);
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEstablecimiento = $(TarjetaGeneralControl).attr('idtarjetaestablecimiento');
    let estadoActual = $(control).attr('idEstado');
    $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/actualizarEstadoEstablecimiento',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEstablecimiento:idEstablecimiento,
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

function OnClickEditarEstablecimiento(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEstablecimiento = $(TarjetaGeneralControl).attr('idtarjetaestablecimiento');

    $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/consultarEstablecimientoEdicion',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEstablecimiento:idEstablecimiento
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            // CargandoMostrar();
        },
        success: function(data) 
        {
            console.log(data)
            // CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    $('.nombreEstablecimientoPopUp').val(data.datos.nombre);
                    $('.codigoPopUp').val(data.datos.codigo);
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
                    
                    $('.empresaPopUp').val(data.datos.EMPRESA).change();
                    if(data.datos.ZONA == 'Sin zona'){
                        $('.zonaPopUp').val(0).change();
                    }else{
                        $('.zonaPopUp').val(data.datos.ZONA).change();
                    }
                   
                    $('.usuarioPopUp').val(data.datos.RESPONSABLE).change();

                    $('#crearEstablecimientoPopUp').find('.modal-title').html('Edición de establecimiento');
                    $('#crearEstablecimientoPopUp').find('.crearEstablecimiento').html('Actualizar');
                    $('#crearEstablecimientoPopUp').find('.crearEstablecimiento').attr('accion',1);
                    $('#crearEstablecimientoPopUp').find('.crearEstablecimiento').attr('idEstablecimiento',idEstablecimiento);
                    $('#crearEstablecimientoPopUp').modal('show');
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

function CargarEstablecimiento() 
{
    arrayFiltros['filtro_nombre_establecimiento'] = $('.establecimientoSearch').val();
    arrayFiltros['filtro_codigo'] = $('.codigoSearch').val();
    arrayFiltros['filtro_correo']=  $('.correoSearch').val();
    arrayFiltros['filtro_ciudad'] = $('.ciudadSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
    arrayFiltros['filtro_responsable'] = $('.responsableSearch').val();

    let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/establecimiento/consultaEstablecimientoScroll',
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
                    $.each(data.datos, function (indexInArray, establecimiento) 
                    { 
                        stringGeneralTarjetas += ComponenteEstablecimiento(establecimiento.id,establecimiento.nombre,establecimiento.codigo,establecimiento.RESPONSABLE,establecimiento.EMPRESA, establecimiento.ZONA,establecimiento.ZONA,establecimiento.direccion,establecimiento.correo,establecimiento.TELEFONO,establecimiento.CIUDAD,establecimiento.estado,arrayEstados[establecimiento.estado].claseEstado,arrayEstados[establecimiento.estado].nombreClase,establecimiento.colaboradores,'none');
                    });
                                            
                    $(stringGeneralTarjetas).appendTo('.contenedorEstablecimientos').animate({
                        height: "toggle"
                    }, 500, function() {
                        scrollLoad= true;
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorEstablecimientos'));
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

var scrollLoad = true;
$(window).scroll(function() {
    if(isMobile.any() != null)
    {
        if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
            if(scrollLoad)
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarEstablecimiento();
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
                CargarEstablecimiento();
            }
        }
    }
});

function OnClickEliminarEstablecimiento(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idEstablecimiento = $(TarjetaGeneralControl).attr('idtarjetaestablecimiento');
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
            url: '/administracion/establecimiento/eliminarEstablecimiento',
            data: 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                idEstablecimiento:idEstablecimiento
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
                        Swal.fire(
                            'Cuidado!',
                            data.mensaje,
                            'error'
                        );
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


$('#crearEstablecimiento').on('click',OnClickCrearEstablecimientoPopUp);
$('.cancelarPopUp').on('click',OnClickCerrarCrearEstablecimientoPopUp);
$('.paisPopUp').on('change',OnChangePais);
$('.departamentoPopUp').on('change',OnChangeDepartamento);
$('.crearEstablecimiento').on('click',OnClickFormulario);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);
$('.buscarBoton').on('click',OnClickBuscarBoton);