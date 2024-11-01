let arrayEstatos = [];
arrayEstatos[0] = { claseEstado: "danger", nombreClase: "Inactivo" };
arrayEstatos[1] = { claseEstado: "primary", nombreClase: "Activo" };
let paginacion = 1;
let arrayFiltros = {};

$(document).ready(function () {
    // Select2

    IniciarVista(true);

    $(".select2").select2();
    $(".guardar-modelo-asignacion").hide();
});

function IniciarVista(activarCargando = false) {
    arrayFiltros["filtro_modelo"] = $(".nombreModeloSearch").val();
    $("#modal-asignacion-modelo").modal("hide");
    $.ajax({
        type: "POST",
        url: "/listachequeo/modelos/consultaListaModelos",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            paginacion: paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros),
        },
        cache: false,
        dataType: "json",
        beforeSend: function () {
            if (activarCargando) CargandoMostrar();
        },
        success: function (data) {
            $("#modal-asignacion-modelo").modal("hide");
            if (activarCargando) CargandoNoMostrar();
            switch (data.codigoRespuesta) {
                case 202:
                    let stringGeneralTarjetas = "";
                    $.each(data.datos, function (indexInArray, modelo) {
                        stringGeneralTarjetas += ComponenteModelos(
                            modelo.id,
                            modelo.nombre,
                            modelo.FOTO,
                            modelo.descripcion
                        );
                    });

                    $(".contenedorModelos").html(stringGeneralTarjetas);
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($(".contenedorModelos"));
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

function ComponenteModelos(
    idModelo,
    nombreModelo,
    fotoModelo,
    descripcionModelo,
    display = "block"
) {
    let stringUsarModelo = "";
    if (perfilIdUsuarioActual == 1)
        // ES ADMINISTRADOR
        stringUsarModelo = `<a href="#" onclick="OnClickUsarModelo(this);" nombreModelo="${nombreModelo}" class="btn btn-primary waves-effect waves-light buttonsModelo" idModelo="${idModelo}">Usar modelo</a>`;

    let stringTarjeta = ` <div class="col-md-6 col-lg-6 col-xl-3" style="display:${display}" idModelo="${idModelo}">
                                <div class="card m-b-20">
                                    <img class="card-img-top img-fluid" src="../..${fotoModelo}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title font-18 mt-0">${nombreModelo}</h4>
                                        <p class="card-text">${descripcionModelo}</p>
                                        ${stringUsarModelo}
                                    </div>
                                </div>

                            </div>`;

    return stringTarjeta;
}

function OnClickUsarModelo(control) {
    let idModelo = $(control).attr("idModelo");
    let nombreModelos = $(control).attr("nombreModelo");

    $.ajax({
        type: "POST",
        url: "/listachequeo/modelos/crearListaChequeoDesdeModelo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            idModelo: idModelo,
            nombreModelos: nombreModelos,
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
                        "/listachequeo/mislistas/" +
                        data.datos.idNuevaListaChequeo;
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

function CargarModelos() {
    arrayFiltros["filtro_modelo"] = $(".nombreModeloSearch").val();

    $.ajax({
        type: "POST",
        url: "/listachequeo/modelos/consultaListaModelosScroll",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            paginacion: paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros),
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
                    $.each(data.datos, function (indexInArray, modelo) {
                        stringGeneralTarjetas += ComponenteModelos(
                            modelo.id,
                            modelo.nombre,
                            modelo.FOTO,
                            modelo.descripcion,
                            "none"
                        );
                    });

                    $(stringGeneralTarjetas)
                        .appendTo(".contenedorModelos")
                        .animate(
                            {
                                height: "toggle",
                            },
                            500,
                            function () {
                                scrollLoad = true;
                            }
                        );
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($(".contenedorModelos"));
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

function OnClickRestablecerBusqueda() {
    location.reload();
}

function OnClickBuscarBoton() {
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

var scrollLoad = true;
$(window).scroll(function () {
    if (isMobile.any() != null) {
        if (
            $(window).scrollTop() + window.innerHeight >=
                document.body.scrollHeight &&
            $(".contenedorModelos").children().length == 9
        ) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarModelos();
            }
        }
    } else {
        if (
            $(window).scrollTop() >=
                $(document).height() - $(window).height() - 20 &&
            $(".contenedorModelos").children().length == 9
        ) {
            if (scrollLoad) {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarModelos();
            }
        }
    }
});

$(".buscarModelo").on("click", OnClickBuscarBoton);
$(".restablecerBoton").on("click", OnClickRestablecerBusqueda);

/// ROBERTO JOSE ARENAS MAZUERA //
let sectorID = [];

$(document).on("click", "#asignar-modelos", function () {
    $("#modal-asignacion-modelo").modal("show");
});

$(document).on("change", "#modeloId", function () {
    const modeloId = $(this).val();

    $.ajax({
        type: "GET",
        url: `/listachequeo/modelos/asignacion-modelos/${modeloId}`,
        dataType: "json",
        cache: false,
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (response) {
            CargandoNoMostrar();
            const ul = $(".list-group").children();
            $(".sector-id").prop("checked", false);
            response.data.forEach((data) => {
                Array.from(ul).forEach((element) => {
                    const sectorId = $(element)
                        .children()
                        .eq(0)
                        .attr("sectorid");

                    if (sectorId == data.sector_id) {
                        $(element).children().eq(0).prop("checked", true);
                    }
                });
                $("input:checkbox:not(:checked)").each(function () {
                    if ($("input:checkbox:not(:checked)").length === 1) {
                        $(".sectorAll").attr("checked", true);
                        // $('.guardar-modelo-asignacion').show();
                    }
                });
            });
        },
        error: function (err) {
            CargandoNoMostrar();
        },
    });
});

$(document).on("click", ".sector-id", function () {
    const checked = $(this)[0].checked;
    let tipo = "";
    let modeloId = $("#modeloId").val();
    let sectoriId = $(this).attr("sectorId");
    $(".guardar-modelo-asignacion").hide();
 
    if (checked) {
        tipo = "agregar";
        if (modeloId === "") {
            $(this).prop("checked", false);
            toastr.info("Seleccione un modelo");
            return;
        }
    } else {
        tipo = "remover";
        console.log(sectorID);
    }

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: "/listachequeo/modelos/asignacion-modelos",
        data: {
            modeloId,
            sectoriId,
            tipo,
        },
        dataType: "json",
        cache: false,
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (response) {
            CargandoNoMostrar();
            toastr.success(response.mensaje);
        },
        error: function (err) {
            CargandoNoMostrar();
        },
    });
});

$(document).on("click", ".sectorAll", function () {
    const checked = $(this)[0].checked;
    const ul = $(".list-group").children();
    let tipo = "";
    sectorID = [];
    if (checked) {
        tipo = "agregar";
        let modeloId = $("#modeloId").val();

        if (modeloId === "") {
            $(this).prop("checked", false);
            toastr.info("Seleccione un modelo");
            return;
        }
        $("input:checked").each(function () {
            if ($(this).val() != "on") {
                var sectorId = $(this).val();
                sectorID.push(sectorId);
            }
        });
        Array.from(ul).forEach((element) => {
            const sectorId = $(element).children().eq(0).attr("sectorid");

            sectorID.push(sectorId);
            $(element).children().eq(0).prop("checked", true);
            // $(element).children().eq(0).prop("disabled", true);
        });

       
    } else {
        tipo = "remover";
        // $('.guardar-modelo-asignacion').hide();
        Array.from(ul).forEach((element) => {
            const sectorId = $(element).children().eq(0).attr("sectorid");

            sectorID.push(sectorId);
            $(element).children().eq(0).prop("checked", false);
        });
       
        
       
    }
    $('.guardar-modelo-asignacion').click()

    // $('.guardar-modelo-asignacion').show();
});

$(document).on("click", ".guardar-modelo-asignacion", function () {
    let modeloId = $("#modeloId").val();
    let sectorAll = $(".sectorAll")[0].checked;
    let tipo = "";

    if (sectorAll) {
        tipo = "agregar";
    } else {
        tipo = "remover";
    }
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: "/listachequeo/modelos/asignacion-modelos",
        data: {
            modeloId,
            sectoriId: sectorID,
            tipo,
        },
        dataType: "json",
        cache: false,
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (response) {
            CargandoNoMostrar();
            toastr.success(response.mensaje);
        },
        error: function (err) {
            CargandoNoMostrar();
        },
    });
});
