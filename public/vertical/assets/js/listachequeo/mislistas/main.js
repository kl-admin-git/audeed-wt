let arrayEstados = [];
arrayEstados[0] = { claseEstado: "danger", nombreClase: "Despublicada" };
arrayEstados[1] = { claseEstado: "primary", nombreClase: "Publicada" };
arrayEstados[2] = { claseEstado: "warning", nombreClase: "Proceso" };
arrayEstados[3] = { claseEstado: "success", nombreClase: "Terminada" };
let paginacion = 1;
let arrayFiltros = {};

$(document).ready(function () {
    let formulario = $("#formularioCrearMiLista").parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2({});

    IniciarVista(true);
});

function IniciarVista(activarCargando = false) {
    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/consultaListasDeChequeo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            paginacion: paginacion,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            if (activarCargando) CargandoMostrar();
        },
        success: function (data) {
            if (activarCargando) CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 202:
                    let stringGeneralTarjetas = "";

                    $(".contenedorTarjetaListas").html("");

                    $.each(data.datos.listasChequeo, function (
                        indexInArray,
                        itemTarjeta
                    ) {
                        stringGeneralTarjetas = ComponenteMisListas(
                            itemTarjeta.ID_LISTA_CHEQUEO,
                            itemTarjeta.NOMBRE,
                            itemTarjeta.PUBLICADO_EN,
                            itemTarjeta.CREADO,
                            itemTarjeta.CREADO_POR,
                            itemTarjeta.CANTIDAD_PROCESO,
                            itemTarjeta.CANTIDAD_TERMINADAS,
                            itemTarjeta.FRECUENCIA,
                            itemTarjeta.ID_FAVORITO,
                            arrayEstados[2].claseEstado,
                            arrayEstados[2].nombreClase,
                            arrayEstados[3].claseEstado,
                            arrayEstados[3].nombreClase,
                            itemTarjeta.ID_ESTADO,
                            arrayEstados[itemTarjeta.ID_ESTADO].claseEstado,
                            arrayEstados[itemTarjeta.ID_ESTADO].nombreClase
                        );

                        $(".contenedorTarjetaListas").append(
                            stringGeneralTarjetas
                        );

                        $(
                            ".sparkline" + itemTarjeta.ID_LISTA_CHEQUEO
                        ).sparkline(itemTarjeta.ArrayBarra, {
                            type: "line",
                            width: "100%",
                            height: "120",
                            chartRangeMax: 40,
                            lineColor: "#3bc3e9",
                            fillColor: "rgba(59, 195, 233, 0.3)",
                            resize: true,
                            highlightLineColor: "rgba(0,0,0,.1)",
                            highlightSpotColor: "rgba(0,0,0,.2)",
                        });
                    });

                    ValidarSiTieneDatos($(".contenedorTarjetaListas"));
                    $('[data-toggle="tooltip"]').tooltip();
                    break;

                case 406:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            if (activarCargando) CargandoNoMostrar();
        },
    });
}

function ComponenteMisListas(
    idListaChequeo,
    nombreMiLista,
    publicacion,
    creacion,
    creadoPor,
    cantidadProceso,
    cantidadTerminada,
    frecuencia,
    favorita,
    estadoColorPrimero,
    textoColorPrimero,
    estadoColorSegundo,
    textoColorSegundo,
    idEstado,
    estadoColorTarjeta,
    textoColorTarjeta,
    display = "block"
) {
    let idGrafica = "sparkline" + idListaChequeo;
    let stringAccionesListasChequeo = "";
    let stringAccionEstado = "";
    let claseEstrella = "";
    let estadoClass, estadoBoton, eventoBtn, tooltipMsg = ''
    let estadoEditar, accionEditar, tooltipEditar = ''

    //Funcion para validar si una lista de chequeo tiene contenido o esta vacia, en caso de estar vacia deshabilitar la opcion de duplicar
    let res = validarOpcDuplicar(idListaChequeo)
    if (res.datos == false) {
        estadoClass = 'desactivado'
        estadoBoton = 'desactivado'
        tooltipMsg = 'data-toggle="tooltip" data-placement="left" title="Lista de chequeo vacía o sin finalizar."'
    } else {
        estadoBoton = ''
        eventoBtn = "OnClickEjecutarAuditoria(this);"
        estadoClass = 'duplicar-lista'
        tooltipMsg = ''
    }

    if (perfilIdUsuarioActual == 1) {
        // ADMINISTRADOR
        // ACCIONES
        //Valido si las listas de chequeos estan sin ejecutar o terminadas
        if(cantidadProceso > 0 || cantidadTerminada > 0){
            estadoEditar = 'desactivado'
            accionEditar = ''
            tooltipEditar = 'data-toggle="tooltip" data-placement="left" title="No se puede editar listas de chequeo ejecutadas o finalizadas."'
        }else{
            estadoEditar = ''
            accionEditar = "OnClickEdicionListaChequeo(this);"
            tooltipEditar = ''
        }
        

        stringAccionesListasChequeo = `
                <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" onclick="OnClickListaDeChequeo(this);" class="dropdown-item" ><i class="mdi mdi-eye m-r-5"></i>Previsualización</div>
                <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" onclick="OnClickInformacionAuditoria(this);" class="dropdown-item" ><i class="mdi mdi-share-variant m-r-5"></i>Compartir</div>
                <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" nombreLista="${nombreMiLista}" class="dropdown-item ${estadoClass}" ${tooltipMsg} ><i class="mdi mdi-content-copy m-r-5"></i>Duplicar</div>
                <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" onclick="${accionEditar}" class="dropdown-item ${estadoEditar}" ${tooltipEditar}><i class="mdi mdi-pen m-r-5"></i>Editar</div>
                <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" onclick="OnClickEliminarMiLista(this);" class="dropdown-item" ><i class="mdi mdi-delete m-r-5"></i>Eliminar</div>
                `;

        //ESTADO
        stringAccionEstado = `<span idListaChequeo="${idListaChequeo}" onclick="CambiarEstadoTarjetas(this);" idEstado="${idEstado}" class="badge badge-pill badge-${estadoColorTarjeta}">${textoColorTarjeta}</span>`;
    } else if (perfilIdUsuarioActual == 2) {
        // COLABORADOR
        claseEstrella = "hidden";
        stringAccionesListasChequeo = `
        <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" onclick="OnClickInformacionAuditoria(this);" class="dropdown-item" ><i class="mdi mdi-share-variant m-r-5"></i>Compartir</div>
        `;

        stringAccionEstado = `<span idListaChequeo="${idListaChequeo}" idEstado="${idEstado}" class="badge badge-pill badge-${estadoColorTarjeta}">${textoColorTarjeta}</span>`;
    }

    let claseEjecutar = "";
    if (idEstado == 0)
        //DESPUBLICADA
        claseEjecutar = "hidden";

    let stringEstrella = "";
    if (favorita == 0)
        stringEstrella = `<i data-toggle="tooltip" data-placement="left" title="Lista favorita" class="mdi mdi-star-outline startRange ${claseEstrella}" idListaChequeo="${idListaChequeo}" idFavorito="${favorita}" onclick="OnClickFavorito(this);" style="font-size: 25px;"></i>`;
    else
        stringEstrella = `<i data-toggle="tooltip" data-placement="left" title="Lista favorita" class="mdi mdi-star startRange ${claseEstrella}" idListaChequeo="${idListaChequeo}" idFavorito="${favorita}" onclick="OnClickFavorito(this);" style="font-size: 25px;"></i>`;

    let stringTarjeta = `<div class="col-lg-4" style="display:${display}" idListaChequeo="${idListaChequeo}">
                                <div class="card m-b-20">
                                    <div class="card-body">
                      
                                        <div class="media">
                                            <div class="media-body">
                                                
                                                <h5 class="m-t-10 font-18 mb-1">${nombreMiLista}</h5>
                                                <div class="btn-group m-b-10 menuFlotante">
                                                    <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="mdi mdi-dots-vertical" style="color:#4ac18e;font-size: 22px;"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        ${stringAccionesListasChequeo}
                                                    </div>
                                                </div>

                                                <p class="text-muted m-b-5">Publicada en <span class="texto-diferente">${publicacion}</span></p>
                                                <p class="text-muted font-secondary small-subtitle m-b-5">Creada el ${creacion} por ${creadoPor}</p>
                                                <p class="text-muted font-secondary small-subtitle ">Frecuencia de realización: ${frecuencia}</p>
                                            </div>
                                        </div>
                                        <div class="row text-center m-t-20">
                                            <div class="col-6">
                                                <h5 class="mb-0">${cantidadProceso}</h5>
                                                <span class="badge estadoBadge badge-pill badge-${estadoColorPrimero} badge-custom">${textoColorPrimero}</span>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="mb-0">${cantidadTerminada}</h5>
                                                <span class="badge estadoBadge badge-pill badge-${estadoColorSegundo} badge-custom">${textoColorSegundo}</span>
                                            </div>
                                        </div>
                                        <div  class="text-center ${idGrafica}"></div>
                                        <div class="contenedorIconosAcciones">
                                            <div class="contenedorEstados">
                                                ${stringAccionEstado}
                                            </div>

                                            <div class="">
                                                <button idListaChequeo="${idListaChequeo}" onclick="${eventoBtn}" type="button" class="btn btn-success waves-effect waves-light ejecutar ${claseEjecutar} ${estadoBoton}"  ${tooltipMsg}>Ejecutar</button>
                                            </div>  
                                        </div>
                                        <div class="col-sm-4 col-lg-3">
                                            ${stringEstrella}
                                        </div>
                                    </div>
                                </div>
                            </div>`;

    return stringTarjeta;
}

function validarOpcDuplicar(idListaChequeo) {
    let res = $.ajax({
        type: "GET",
        url: "/listachequeo/mislistas/validarDuplicar/" + idListaChequeo,
        async: false,
    }).responseJSON
    return res
}

function OnClickEliminarMiLista(control) {
    let TarjetaControl = $(control).parents().eq(6);
    let idListaChequeo = $(control).attr("idListaChequeo");

    Swal.fire({
        title: "¿Estás seguro?",
        text: "No se podrán revertir los cambios de la eliminación",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger m-l-10",
        confirmButtonText: "Si, eliminarlo!",
        cancelButtonText: "Cancelar",
    }).then(function (resultado) {
        console.log(resultado);
        if (resultado.dismiss == "cancel") return;

        $.ajax({
            type: "POST",
            url: "/listachequeo/mislistas/eliminarListaChequeo",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                idListaChequeo: idListaChequeo,
            },
            cache: false,
            dataType: "json",
            beforeSend: function () {
                CargandoMostrar();
            },
            success: function (data) {
                CargandoNoMostrar();
                switch (data.codigoRespuesta) {
                    case 203:
                        $(TarjetaControl).remove();
                        Swal.fire("Eliminado!", data.mensaje, "success");
                        break;

                    case 406:
                        toastr.error(data.mensaje);
                        break;

                    default:
                        break;
                }
            },
            error: function (data) {
                CargandoNoMostrar();
            },
        });
    });
}

function OnClickCrearNuevaLista() {
    var form = $("#formularioCrearMiLista");
    form.parsley().validate();

    if (form.parsley().isValid()) {
        let objetoEnviar = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            nombre: $(".nombreMiListaPopUp").val(),
            entidad_evaluada: $(".aQuienPopUp ").val(),
            publicacion_destino: $(".estadoInicialPopUp ").val(),
            estadoInicial: $(".estadoInicialPopUp").val(),
            checkAutomatico: $("#checkBoxAutomatico").is(":checked"),
        };

        $.ajax({
            type: "POST",
            url: "/listachequeo/mislistas/crearListaMiLista",
            data: objetoEnviar,
            cache: false,
            dataType: "json",
            beforeSend: function () {
                CargandoMostrar();
            },
            success: function (data) {
                CargandoNoMostrar();
                switch (data.codigoRespuesta) {
                    case 200:
                        let url =
                            window.location.origin +
                            "/listachequeo/mislistas/" +
                            data.datos;
                        window.location.href = url;
                        break;

                    default:
                        break;
                }
            },
            error: function (data) {
                CargandoNoMostrar();
            },
        });
    }
}

function OnClickEdicionListaChequeo(control) {
    let idListaChequeo = $(control).attr("idListaChequeo");
    let url =
        window.location.origin + "/listachequeo/mislistas/" + idListaChequeo;
    window.location.href = url;
}

function OnClickInformacionAuditoria(control) {
    let idListaChequeo = $(control).attr("idListaChequeo");

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/consultarInformacionTarjeta",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idListaChequeo: idListaChequeo,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 202:
                    $("#link").val(data.datos.link);
                    $(".linkSelect")
                        .val(data.datos.frecuencia_ejecucion)
                        .change();
                    $(".cantidadFrecuencia").val(
                        data.datos.cant_ejecucion == undefined
                            ? ""
                            : data.datos.cant_ejecucion
                    );

                    $("#linkPopUp").modal("show");
                    break;

                case 404:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrar();
        },
    });
}

function CopiarClipBoard() {
    /* Get the text field */
    var copyText = document.getElementById("link");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    toastr.success("Tu link ha sido copiado");
}

function CargarListasChequeo() {
    //     arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
    //     arrayFiltros['filtro_nit'] = $('.nitSearch').val();
    //     arrayFiltros['filtro_direccion']=  $('.direccionSearch').val();
    //     arrayFiltros['filtro_pais'] = $('.paisSearch').val();
    //     arrayFiltros['filtro_responsable'] = $('.responsableSearch').val();

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/consultaAuditoriasScroll",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            paginacion: paginacion,
            // arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrarFooter();
        },
        success: function (data) {
            CargandoNoMostrarFooter();
            switch (data.codigoRespuesta) {
                case 202:
                    let stringGeneralTarjetas = "";
                    $.each(data.datos.listasChequeo, function (
                        indexInArray,
                        itemTarjeta
                    ) {
                        stringGeneralTarjetas = ComponenteMisListas(
                            itemTarjeta.ID_LISTA_CHEQUEO,
                            itemTarjeta.NOMBRE,
                            itemTarjeta.PUBLICADO_EN,
                            itemTarjeta.CREADO,
                            itemTarjeta.CREADO_POR,
                            itemTarjeta.CANTIDAD_PROCESO,
                            itemTarjeta.CANTIDAD_TERMINADAS,
                            itemTarjeta.FRECUENCIA,
                            itemTarjeta.ID_FAVORITO,
                            arrayEstados[2].claseEstado,
                            arrayEstados[2].nombreClase,
                            arrayEstados[3].claseEstado,
                            arrayEstados[3].nombreClase,
                            itemTarjeta.ID_ESTADO,
                            arrayEstados[itemTarjeta.ID_ESTADO].claseEstado,
                            arrayEstados[itemTarjeta.ID_ESTADO].nombreClase,
                            "none"
                        );

                        $(stringGeneralTarjetas)
                            .appendTo(".contenedorTarjetaListas")
                            .animate(
                                {
                                    height: "toggle",
                                },
                                500,
                                function () {
                                    scrollLoad = true;
                                }
                            );

                        $(
                            ".sparkline" + itemTarjeta.ID_LISTA_CHEQUEO
                        ).sparkline(itemTarjeta.ArrayBarra, {
                            type: "line",
                            width: "100%",
                            height: "120",
                            chartRangeMax: 40,
                            lineColor: "#3bc3e9",
                            fillColor: "rgba(59, 195, 233, 0.3)",
                            resize: true,
                            highlightLineColor: "rgba(0,0,0,.1)",
                            highlightSpotColor: "rgba(0,0,0,.2)",
                        });
                    });

                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($(".contenedorTarjetaListas"));
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrarFooter();
        },
    });
}

function OnClickEjecutarAuditoria(control) {
    let idListaChequeo = $(control).attr("idlistachequeo");

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/comenzarEjecucionListaChequeo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idListaChequeo: idListaChequeo,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 200:
                    let url =
                        window.location.origin +
                        "/listachequeo/ejecucion/" +
                        idListaChequeo +
                        "/" +
                        data.datos;
                    window.location.href = url;
                    break;

                case 402:
                    Swal.fire({
                        title: "No puedes continuar con la ejecución",
                        text:
                            "Ya superaste las veces que puedes ejecutar esta lista de chequeo",
                        icon: "danger",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    }).then((result) => { });
                    break;

                case 406:
                    if (data.datos != 1) {
                        // DIFERENTE ADMINISTRADOR
                        Swal.fire({
                            title: "No puedes continuar",
                            text: data.mensaje,
                            type: "warning",
                            confirmButtonClass: "btn btn-success",
                            confirmButtonText: "Aceptar",
                        }).then(function () { });
                    } else {
                        Swal.fire({
                            title: "No puedes continuar",
                            text: data.mensaje,
                            type: "warning",
                            confirmButtonClass: "btn btn-success",
                            confirmButtonText: "Cambiar plan",
                            cancelButtonClass: "btn btn-secondary",
                            cancelButtonText: "Cerrar",
                            showCancelButton: true,
                        }).then(function (response) {
                            if (response.dismiss == undefined) {
                                let idPlan = $.trim(
                                    $("#popUpSuscripcion").attr("planActual")
                                );

                                switch (idPlan) {
                                    case "1":
                                        let control = $(
                                            ".contenedoresPlanes"
                                        ).find(
                                            'div[idTarjeta="' + idPlan + '"]'
                                        );
                                        $(control)
                                            .find(".btn-audeed")
                                            .addClass("hidden");
                                        break;

                                    case "2":
                                    case "3":
                                    case "4":
                                        for (
                                            let index = idPlan;
                                            index > 0;
                                            index--
                                        ) {
                                            let control = $(
                                                ".contenedoresPlanes"
                                            ).find(
                                                'div[idTarjeta="' + index + '"]'
                                            );
                                            $(control)
                                                .find(".btn-audeed")
                                                .addClass("hidden");
                                        }

                                        break;

                                    default:
                                        break;
                                }

                                $(".iconoCerrarPopUpPlanes").removeClass(
                                    "hidden"
                                );
                                $("#popUpSuscripcion").modal("show");
                            }
                        });
                    }
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrar();
        },
    });
}

function OnClickNuevaListaCrear() {
    $("#crearMiListaPopUp").modal("show");
}

function OnClickNuevaListaCerrarPopUp() {
    $(".nombreMiListaPopUp").val("");
    $(".aQuienPopUp ").val(1).change();
    $(".estadoInicialPopUp").val(1).change();
    if (!$("#checkBoxAutomatico").is(":checked"))
        $("#checkBoxAutomatico").trigger("click");

    $("#crearMiListaPopUp").modal("hide");
}

function CambiarEstadoTarjetas(control) {
    let controlEstado = $(control);
    let idListaChequeo = $(controlEstado).attr("idListaChequeo");
    let estadoActual = $(control).attr("idEstado");

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/actualizarEstadoListaChequeo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idListaChequeo: idListaChequeo,
            estadoActual: estadoActual,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 201:
                    $(controlEstado)
                        .removeClass("badge-primary")
                        .removeClass("badge-danger")
                        .addClass(
                            "badge-" + arrayEstados[data.datos].claseEstado
                        )
                        .html(arrayEstados[data.datos].nombreClase);
                    $(controlEstado).attr("idEstado", data.datos);
                    if (data.datos == 0)
                        $(controlEstado)
                            .parent()
                            .parent()
                            .find(".ejecutar")
                            .addClass("hidden");
                    else
                        $(controlEstado)
                            .parent()
                            .parent()
                            .find(".ejecutar")
                            .removeClass("hidden");
                    break;
                case 406:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrar();
        },
    });
}

var scrollLoad = true;
$(window).scroll(function () {
    if (isMobile.any() != null) {
        if (
            $(window).scrollTop() + window.innerHeight >=
            document.body.scrollHeight &&
            $(".contenedorTarjetaListas").children().length == 9
        ) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarListasChequeo();
            }
        }
    } else {
        if (
            $(window).scrollTop() >=
            $(document).height() - $(window).height() - 20 &&
            $(".contenedorTarjetaListas").children().length == 9
        ) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarListasChequeo();
            }
        }
    }
});

function OnClickFavorito(control) {
    let idFavoritoActual = $(control).attr("idFavorito");
    let idListaChequeo = $(control).attr("idListaChequeo");

    $.each($(".contenedorTarjetaListas").find(".startRange"), function (
        indexInArray,
        item
    ) {
        $(item).removeClass("mdi-star").addClass("mdi-star-outline");
        $(item).attr("idFavorito", 0);
    });

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/actualizarFavoritoListaChequeo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idListaChequeo: idListaChequeo,
            idFavoritoActual: idFavoritoActual,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 201:
                    toastr.success(data.mensaje);

                    if (data.datos == 0) $(control).removeClass("mdi-star");
                    else
                        $(control)
                            .removeClass("mdi-star-outline")
                            .addClass("mdi-star");

                    $(control).attr("idFavorito", data.datos);
                    break;
                case 406:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrar();
        },
    });
}

function EjemploPayu() {
    $("#popUpSuscripcion").modal("show");
}

function OnClickListaDeChequeo(control) {
    let idListaChequeo = $(control).attr("idListaChequeo");

    $.ajax({
        type: "POST",
        url: "/listachequeo/mislistas/traerListaDeChequeoPrevisualizacion",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idListaChequeo: idListaChequeo,
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (data) {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 202:
                    //CARGAR DE PREGUNTAS
                    let categorias = "";
                    $.each(data.datos, function (indexInArray, categoria) {
                        let stringPreguntas = "";
                        $.each(categoria.PREGUNTAS, function (
                            indexInArray,
                            pregunta
                        ) {
                            let arrayRespuestas = pregunta.tiposRespuestas;
                            if (pregunta.permitir_noaplica != 0) {
                                if (arrayRespuestas.length != 0) {
                                    let objeto = {
                                        id: 0,
                                        valor_personalizado: "N/A",
                                    };
                                    if (
                                        arrayRespuestas[0].EXISTE_REGISTRO != 0
                                    ) {
                                        if (arrayRespuestas[0].NA == 1)
                                            objeto["rta"] = 0;
                                    }

                                    arrayRespuestas.push(objeto);
                                }
                            }

                            stringPreguntas += ComponentePregunta(
                                pregunta.id,
                                pregunta.nombre,
                                arrayRespuestas,
                                pregunta.OpcionesGenerales,
                                parseFloat(pregunta.ponderado).toFixed(0),
                                pregunta.opcionesGeneralesLlenas,
                                pregunta.opcionesGeneralesLlenasFotos
                            );
                        });

                        categorias += ComponenteCategoriaCompleta(
                            categoria.CATEGORIA_ID,
                            categoria.NOMBRE_CATEGORIA,
                            stringPreguntas,
                            categoria.PONDERADO
                        );
                    });

                    $(".contenedorCategorias").html(categorias);

                    $("#visualizacionPopUp").modal("show");
                    break;

                case 402:
                    Swal.fire({
                        title: "No puedes continuar con la ejecución",
                        text:
                            "Ya superaste las veces que puedes ejecutar esta lista de cheuqueo",
                        icon: "danger",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    }).then((result) => {
                        let url =
                            window.location.origin + "/listachequeo/mislistas";
                        window.location.href = url;
                    });
                    break;
                default:
                    break;
            }
        },
        error: function (data) {
            CargandoNoMostrar();
        },
    });
}

function ComponentePregunta(
    idPregunta,
    pregunta,
    respuestas,
    opcionesRespuesta,
    ponderado,
    opcLlenas,
    opcLlenasFotos
) {
    let string = "";
    console.log(respuestas);
    let stringRespuestas = "";
    let tipoRespuesta = 0;
    if (respuestas.length != 0) tipoRespuesta = respuestas[0].TIPO_RESPUESTA;

    $.each(respuestas, function (indexInArray, item) {
        let stringClase = "";
        if (item.id == item.rta) stringClase = "respuestaSeleccion";
        else stringClase = "";

        stringRespuestas += `<div class="m-l-5 m-r-5">
                                <div class="form-group">
                                    <div class="respuesta bg-gray ${stringClase}" idRespuesta="${item.id}">
                                        ${item.valor_personalizado}
                                    </div>
                                </div>
                            </div>`;
    });

    let stringRespuestasOpciones = "";
    $.each(opcionesRespuesta, function (indexInArray, opcionRespuesta) {
        let stringComentario = "";
        let claseSeleccionada = "";
        if (opcLlenas != null && opcLlenas != undefined) {
            if (opcionRespuesta.id == 2) {
                // COMENTARIO
                if (opcLlenas.COMENTARIO == 1) {
                    stringComentario = opcLlenas.TEXTO_COMENTARIO;
                    claseSeleccionada = "respuestaOpcSeleccion";
                }
            }
        }

        if (opcionRespuesta.id == 1) {
            // FOTO
            if (
                opcLlenasFotos != 0 &&
                (opcLlenasFotos != null || opcLlenasFotos != undefined)
            ) {
                claseSeleccionada = "respuestaOpcSeleccion";
                stringComentario = "";
            }
        }

        stringRespuestasOpciones += `<div class="m-l-10">
                                            <div class="form-group">
                                                <div class="respuestaOpc bg-gray ${claseSeleccionada}" idRespuestaOpcion="${opcionRespuesta.id}">
                                                    <i class="${opcionRespuesta.icono}"></i>
                                                </div>
                                            </div>
                                        </div>`;
    });

    string = `<div class="card card-body">
            <div class="row m-b-10 contenedorPregunta" idPregunta="${idPregunta}" ponderadoPregunta="${ponderado}">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <p>
                            ${pregunta}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-12">
                            <div class="row contenedorRespuestas" tipoRespuesta="${tipoRespuesta}">
                                ${stringRespuestas}
                            </div>

                            <div class="row contenedorOpcionesRespuestas">
                                ${stringRespuestasOpciones}
                            </div>
                    </div>

                </div>
            </div>
                
                `;

    return string;
}

function ComponenteCategoriaCompleta(
    idCategoria,
    nombreCategoria,
    preguntas,
    ponderado
) {
    let string = "";

    string += `<div class="m-b-10 contenedorUnaCategoria" idCategoria="${idCategoria}" ponderadoCategoria="${ponderado}">
                    <div class="p-3 categoriaContenedor" id="headingOne" data-toggle="collapse" onclick="OnClickCategoria(this);">
                        <h6 class="m-0">
                            <a class="text-white">
                                    ${nombreCategoria}
                            </a>
                        </h6>
                    </div>

                    <div class="collapse m-t-10 show">
                        <div class="card">
                            <div class="card-body m-t-10 contenedosPreguntas">
                                ${preguntas}
                            </div>
                        </div>
                        
                    </div>
                </div>`;

    return string;
}

function OnClickCategoria(control) {
    let padre = $(control).parent();
    if ($(padre).find(".collapse ").hasClass("show"))
        $(padre).find(".collapse ").collapse("hide");
    else $(padre).find(".collapse ").collapse("show");
}

$(".crearDesdeCero").on("click", OnClickNuevaListaCrear);
$(".cancelarPopUp").on("click", OnClickNuevaListaCerrarPopUp);
$(".continuar").on("click", OnClickCrearNuevaLista);
// $('.colorTabs').on('click',EjemploPayu);

/// ROBERTO JOSE ARENAS MAZUERA //
$(document).on("click", ".duplicar-lista", function () {
    const listaId = $(this).attr("idlistachequeo");
    const nombreLista = $(this).attr("nombrelista");

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: "/listachequeo/modelos/crearListaChequeoDesdeModelo",
        data: {
            listaId,
            nombreLista
        },
        dataType: "json",
        cache: false,
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (response) {
            CargandoNoMostrar();
            if(response.codigoRespuesta == 406){
                toastr.error(response.mensaje)
                return 
            }
            let url = window.location.origin + '/listachequeo/mislistas/' + response.datos.idNuevaListaChequeo;
            window.location.href = url;
            console.log(response)
        },
        error: function (err) {
            CargandoNoMostrar();
        }
    });

});
