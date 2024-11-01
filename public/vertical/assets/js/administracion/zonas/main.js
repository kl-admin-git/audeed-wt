let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstados[1] = { claseEstado: 'primary', nombreClase: 'Activo' };
let paginacion = 1;
let paginador = {start: 1, last: 1}
let arrayFiltros = {};

$(document).ready(function () {
    let formulario = $('#formularioCreacionEstablecimiento').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2();
    consultarZonasFiltro()
    IniciarVista(true);
    $('.menu').hide()


   
});

//Limpio la tabla cuando el modal se oculta
$('#verEstablecimientos').on('hide.bs.modal', function (e) {
    $('.pagination').empty()
    $('#tdatos').empty()
})
//Se ejecuta cuando se activa el modal para ver establecimientos
$('#verEstablecimientos').on('shown.bs.modal', function (e) {
   
    paginador.start = 0
    paginador.last = 0
    
     let idZona = $(e.relatedTarget).attr('idzona')
     if(idZona == undefined || idZona == null)
        return 0

     $.ajax({
        type: 'POST',
        url: '/administracion/zonas/consultarEstablecimientoZona' ,
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idZona: idZona
        },
        cache: false,
        dataType: 'json',
        success: function (resp) {
            let info = resp.datos
            let tbody = ''
           console.log(resp)
            if(resp.status == 200){
                info.data.forEach(el=>{
                    tbody += ` <tr>
                                    <td >${el.empresa}</td>
                                    <td>${el.establecimiento}</td>
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
                    cambioPagina(page,idZona)
                })
                 //------- FIN FUNCION PARA PAGINAR -----------------
            }

        },
        error: function (data) {
          
        }
     })
})

//Funcion para cambio de pagina para el Modal de Establecimientos
function cambioPagina(page, idZona){
    $.ajax({
        type: 'POST',
        url: '/administracion/zonas/consultarEstablecimientoZona?page=' + page ,
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idZona: idZona
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
                                    <td >${el.empresa}</td>
                                    <td>${el.establecimiento}</td>
                              </tr>`
                })
            }
            $('#tdatos').append(tbody)


        },
        error: function (data) {
          
        }
     })
}


function IniciarVista(activarCargando = false) {
    let selectValor = $('.zonaSearch').val()
    arrayFiltros['filtro_zona_id'] = selectValor
    
    //let idCuentaPrincipal = $('.datosUsuario').attr('idCuentaPrincipal');
    $.ajax({
        type: 'POST',
        url: '/administracion/zonas/consultarZonas',
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion: paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function () {
            if (activarCargando)
                CargandoMostrar();
        },
        success: function (resp) {
          
            if (activarCargando) {
                CargandoNoMostrar()
            }
            let stringGeneralTarjetas = ''
            let info = ''
            //Valido si existe la propiedad data
            if(resp.datos.hasOwnProperty('data')){
                info = resp.datos.data
            }else{
                info = resp.datos
            }

            $.each(info, function (index, valor){
                if(valor != null){
                    stringGeneralTarjetas += ComponenteZona(valor.id,valor.nombre, valor.descripcion, valor.estado, valor.establecimientos_cantidad)
                }
            })

            $('.contenedorZonas').empty()
            $('.contenedorZonas').html(stringGeneralTarjetas)
            $('[data-toggle="tooltip"]').tooltip()
            ValidarSiTieneDatos($('.contenedorZonas'))
            $(".zonaSearch option[value="+ selectValor +"]").attr("selected",true)
            

        },
        error: function (data) {
            ValidarSiTieneDatos($('.contenedorZonas'))
            if (activarCargando)
                CargandoNoMostrar();
        }
    });
}

function consultarZonasFiltro(){
    $.ajax({
        type: 'GET',
        url: '/administracion/zonas/consultarZonasFiltro',
        success: function (resp) {
            let optionsSelect = ' <option value="">Buscar por nombre zona</option>'
            $('.zonaSearch').empty()
            resp.datos.forEach(data=>{
                optionsSelect += `<option value="${data.id}">${data.nombre}</option>`
            })

            $('.zonaSearch').append(optionsSelect)
            
        },
        error: function (data) {
            toastr.error(data)
        }
    });
}

function ComponenteZona(
    idZona,
    nombreZona,
    descripcionZona,
    estado,
    establecimientos = 0,
    display = 'block'
) {

    let stringEStado = ''
    let stringIconos = ''
    let estadoColor = ''
    //valido el estado
    if(parseInt(estado) == 1){
        estadoTexto = arrayEstados[1].nombreClase
        estadoColor = arrayEstados[1].claseEstado
    }else{
        estadoTexto = arrayEstados[0].nombreClase
        estadoColor = arrayEstados[0].claseEstado
    }

    //Valido que no vengan valores nulos
    if(descripcionZona == null){
        descripcionZona = 'No tiene descripción'
    }

    if (perfilExacto == 1 || perfilExacto == 2) {
        stringEStado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" onclick="OnClickCambiarEstado(this);" idEstado="${estado}">${estadoTexto}</span>`;
        stringIconos = `
            <li data-toggle="tooltip" data-placement="top" title="Editar" onclick="OnClickEditarZona(this);" class="mdi mdi-pen"></li>
            <li data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="OnClickEliminarZona(this);" class="mdi mdi-delete"></li>
        `;
    } else {
        stringEStado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" style="cursor: not-allowed!important;" idEstado="${estado}">${estadoTexto}</span>`;
        stringIconos = `
            <li disabled style="cursor: not-allowed;" class="mdi mdi-pen"></li>
            <li disabled style="cursor: not-allowed;" class="mdi mdi-delete"></li>
        `;
    }
    //Valido si el texo es demasiado extenso y lo acorto con puntos suspencivos
    let text_truncate = ''
    let class_text_truncate = ''
    if(descripcionZona.length > 50){
        text_truncate = `data-toggle="tooltip" data-placement="top" title="${descripcionZona}"`
        class_text_truncate = 'text-truncate'
    }else{
        text_truncate = ''
        class_text_truncate = ''
    }

    let stringTarjeta = `<div class="col-lg-4" style="display:${display};" idTarjetaZona="${idZona}">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body contenedorPrincipal">
                                            <h5 class="m-t-10 mb-1 texto-personalizado ellipseText" data-toggle="tooltip" data-placement="left" title="${nombreZona}">${nombreZona}</h5>
                                            <p class="m-b-5 ${class_text_truncate}" ${text_truncate}><span class="mdi mdi-receipt" data-toggle="tooltip" data-placement="left" title="Descripción"></span> ${descripcionZona}</p>
                                            <a href="#" id="linkEstablecimientos" idZona="${idZona}" data-toggle="modal" data-target="#verEstablecimientos"> <p class="m-b-5"><span class="mdi mdi-store" data-toggle="tooltip" data-placement="left" title="Establecimientos"></span> Establecimientos: <span class="badge badge-success">${establecimientos}</span></p></a>
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

//Crear o Editar Zona
function OnClickFormulario(e) {
    e.preventDefault();
    let _token = $('meta[name="csrf-token"]').attr('content')
    var form = $('#formularioCreacionEstablecimiento');

    form.parsley().validate();

    if (form.parsley().isValid()) {

        if ($(this).attr('accion') == 0) // CREAR
        {
            let objetoEnviar = {
                _token: _token,
                nombreZona: $('.nombreZonaPopUp').val(),
                descripcion: $('.descripcionPopUp').val()
            }

            $.ajax({
                type: 'POST',
                url: '/administracion/zonas/crear',
                data: objetoEnviar,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    CargandoMostrar();
                },
                success: function (data) {
                    CargandoNoMostrar()
                    OnClickCerrarCrearEstablecimientoPopUp()
                    $('#crearZonaPopUp').modal('hide')
                    IniciarVista(true)
                    if (data.status == 200) {
                        toastr.success(data.msg)
                        consultarZonasFiltro()
                    } else {
                        toastr.error(data.msg)
                    }

                },
                error: function (data) {
                    CargandoNoMostrar()
                }
            });
        }
        else if ($(this).attr('accion') == 1) // EDITAR
        {
            //Datos a enviar
            let idZona = $(this).attr('idZona');;
            let nombreZona = $('.nombreZonaPopUp').val()
            let descripcionZona = $('.descripcionPopUp').val()
            console.log('Entro' + idZona)
  
            $.ajax({
                type: 'POST',
                url: '/administracion/zonas/editarZona',
                data:{
                    _token,
                   idZona,
                   nombreZona,
                   descripcionZona
                
                },
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    CargandoMostrar();
                },
                success: function (data) {
                    CargandoNoMostrar();
                    switch (data.status) {
                        case 201:
                            toastr.success(data.msg);
                            let control = $('.contenedorZonas').find('div[idtarjetazona="' + idZona + '"]')
                           
                            $(control).remove();
                            arrayFiltros = {}
                            IniciarVista(false)
                            ValidarSiTieneDatos($('.contenedorZonas'))
                            OnClickCerrarCrearEstablecimientoPopUp()
                            break;

                        case 406:
                            toastr.error(data.msg);
                            break;

                        default:
                            break;
                    }

                },
                error: function (data) {
                    CargandoNoMostrar()
                }
            });
        }

    }
}

function OnClickCrearEstablecimientoPopUp() {
    $('#crearZonaPopUp').find('.modal-title').html('Creación de Zona');
    $('#crearZonaPopUp').find('.crearZona').html('Crear zona');
    $('#crearZonaPopUp').find('.crearZona').attr('accion', '0');
    $('#crearZonaPopUp').modal('show');
}

function OnClickCerrarCrearEstablecimientoPopUp() {
    $('#crearZonaPopUp').modal('hide');
    $('#formularioCreacionEstablecimiento').find('input[type="text"]').val('');
    $('#formularioCreacionEstablecimiento').parsley().reset();
    $('.errorEmpresa').addClass('hidden');
}


function OnClickCambiarEstado(control) {
    let controlEstado = $(control);
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idZona = $(TarjetaGeneralControl).attr('idtarjetazona');
    let estadoActual = $(control).attr('idEstado');
    
    $.ajax({
        type: 'POST',
        url: '/administracion/zonas/actualizarEstadoZona',
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idZona: idZona,
            estadoActual: estadoActual
        },
        cache: false,
        dataType: 'json',
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (resp) {
            CargandoNoMostrar();
            console.log(resp.datos)
            switch (resp.status) {
                case 201:
                    $(controlEstado).removeClass('badge-primary').removeClass('badge-danger').addClass('badge-' + arrayEstados[resp.estadoControl].claseEstado).html(arrayEstados[resp.estadoControl].nombreClase);
                    $(controlEstado).attr('idEstado', resp.datos);
                    break;
                case 406:
                    toastr.error(resp.mensaje);
                    break;

                default:
                    break;
            }

        },
        error: function (data) {
            CargandoNoMostrar()
        }
    });
}

function OnClickRestablecerBusqueda() {
    location.reload();
}

function OnClickBuscarBoton() {
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

function OnClickEditarZona(control) {
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idZona = $(TarjetaGeneralControl).attr('idtarjetazona')
    arrayFiltros['filtro_zona_id'] = idZona
    $.ajax({
        type: 'POST',
        url: '/administracion/zonas/consultarZonas',
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            console.log(data)
            CargandoNoMostrar();
            switch (data.status) {
                case 202:
                    //console.log(data.datos)
                    $('.nombreZonaPopUp').val(data.datos[0].nombre)
                    $('.descripcionPopUp').val(data.datos[0].descripcion)

                    $('#crearZonaPopUp').find('.modal-title').html('Edición de Zonas');
                    $('#crearZonaPopUp').find('.crearZona').html('Actualizar');
                    $('#crearZonaPopUp').find('.crearZona').attr('accion', 1);
                    $('#crearZonaPopUp').find('.crearZona').attr('idZona', idZona);
                    $('#crearZonaPopUp').modal('show');
                    break;
                case 404:

                    break;

                default:
                    break;
            }

        },
        error: function (data) {
            CargandoNoMostrar();
        }
    });
}


var scrollLoad = true;
$(window).scroll(function () {
    if (isMobile.any() != null) {
        if ($(window).scrollTop() + window.innerHeight >= document.body.scrollHeight) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                //CargarEstablecimiento();
                console.log(paginacion)
            }
        }
    } else {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 20) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                //CargarEstablecimiento();
            }
        }
    }
});

function OnClickEliminarZona(control) {
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idZona = $(TarjetaGeneralControl).attr('idtarjetazona');
    
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
        if (resultado.dismiss == 'cancel')
            return;

        $.ajax({
            type: 'POST',
            url: '/administracion/zonas/eliminarZonas',
            data:
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                idZona: idZona
            },
            cache: false,
            dataType: 'json',
            beforeSend: function () {
                //CargandoMostrar();
            },
            success: function (resp) {
                CargandoNoMostrar();
                switch (resp.status) {
                    case 201:
                        $(TarjetaGeneralControl).remove();
                        Swal.fire(
                            'Eliminado!',
                            resp.msg,
                            'success'
                        );
                        consultarZonasFiltro()
                        break;
                    case 406:
                        Swal.fire(
                            'Cuidado!',
                            resp.msg,
                            'error'
                        );
                        break;

                    default:
                        break;
                }
                ValidarSiTieneDatos($('.contenedorZonas'))
            },
            error: function (data) {
                CargandoNoMostrar()
            }
        });
    });
}



$('#crearEstablecimiento').on('click', OnClickCrearEstablecimientoPopUp);
$('.cancelarPopUp').on('click', OnClickCerrarCrearEstablecimientoPopUp);
$('.crearZona').on('click', OnClickFormulario);
$('.restablecerBoton').on('click', OnClickRestablecerBusqueda);
$('.buscarBoton').on('click', OnClickBuscarBoton);
