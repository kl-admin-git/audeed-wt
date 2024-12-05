let paginacion=1;
let totalpaginas = 0;
let inicializacionPaginacion = false;
let arrayFiltros = {};
let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Cancelada' };
arrayEstados[1] = { claseEstado: 'warning', nombreClase: 'Proceso' };
arrayEstados[2] = { claseEstado: 'primary', nombreClase: 'Terminada' };
$(document).ready(function ()
{
    // Select2
    $(".select2").select2({});

    // Date Picker
    $('#datepicker-autoclose').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'es'
    });

    $('#datepicker-autoclose-end').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'es'
    });

    IniciarVista();
});

function IniciarVista()
{
    arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
    arrayFiltros['filtro_inicio'] = $('#datepicker-autoclose').val();
    arrayFiltros['filtro_fin'] = $('#datepicker-autoclose-end').val();
    arrayFiltros['filtro_estado'] = $('.estadoSearch').val();

    $.ajax({
        type: 'POST',
        url: '/informes/get_report_detail',
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion: paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
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
                case 202:
                  $('#tablaInformesDetalle tbody').html('');
                  $('#tablaInformesDetalle thead').html('');

                  // CONSTRUYENDO ENCABEZADO
                  if(data.datos.data.length != 0)
                  {
                    let string_head_table = '<tr>';
                    Object.entries(data.datos.data[0]).forEach((key) => {
                      if(key[0] != "ID_AUDITORIA" && key[0] != "ID_ESTADO" && key[0] != "latitud" && key[0] != "longitud")
                        string_head_table += `<th class="text-center">${key[0].replaceAll("_"," ")}</th>`;
                    });
                    string_head_table += '</tr>';
                    $('#tablaInformesDetalle thead').html(string_head_table);
                  }

                  let stringTabla = '';
                  $.each(data.datos.data, function (indexInArray, item)
                  {
                      stringTabla += `<tr>`;
                      Object.entries(item).forEach((key) => {
                        if(key[0] != "ID_AUDITORIA" && key[0] != "latitud" && key[0] != "longitud")
                        {
                          if (key[0] == "DIRECCION")
                            stringTabla += (key[1] != ''
                              ? `<td style="cursor:pointer;" class="text-primary" onclick="OnClickMapa(this);" latitud="${(item.latitud == undefined ? '' : item.latitud)}" longitud="${(item.longitud == undefined ? '' : item.longitud)}">${key[1]}</td>`
                              : `<td latitud="${(item.latitud == undefined ? '' : item.latitud)}" longitud="${(item.longitud == undefined ? '' : item.longitud)}">${key[1]}</td>`);
                          else
                            stringTabla += `<td>${key[1]}</td>`;
                        }

                      });
                      stringTabla += `</tr>`;
                    });

                    // if(data.datos.data.length == 0)
                    //     stringTabla = '<tr><td class="text-center" colspan="9">No tienes registros actualmente</td></tr>';

                    $('#tablaInformesDetalle tbody').html(stringTabla);
                    totalpaginas = data.datos.cantidadTotal;
                    if(!inicializacionPaginacion)
                        InicializacionPaginacion($('.pagination'),data.datos.cantidadTotal,paginacion);
                    break;

                case 402:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }

        },
        error: function(data)
        {
            CargandoNoMostrar();
        }
    });
}

function OnClickRestablecerBusqueda()
{
    let url = window.location.origin + '/informes/detalle';
    window.location.href = url;
}

function OnClickBuscarBoton()
{
    arrayFiltros['filtro_inicio'] = $('#datepicker-autoclose').val();
    arrayFiltros['filtro_fin'] = $('#datepicker-autoclose-end').val();
    if (($('#datepicker-autoclose').val() != "" && $('#datepicker-autoclose-end').val() == "")
      || ($('#datepicker-autoclose').val() == "" && $('#datepicker-autoclose-end').val() != ""))
    {
      toastr.warning("Debes agregar las 2 fechas");
      return;
    }

    paginacion = 1;
    inicializacionPaginacion = false;
     $('.pagination').html(`<div class="nav-btn prev"></div>
                              <ul class="nav-pages"></ul>
                            <div class="nav-btn next"></div>`);
    pageNum = 0;
    pageOffset = 0;

    IniciarVista();
}

$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);

function OnClickMapa(control)
{
    let long = parseFloat($(control).attr('longitud'));
    let lat = parseFloat($(control).attr('latitud'));
    let direccion = $.trim($(control).html());

    initMap(lat,long,direccion);
    $("#modalViewMap").modal('show');
}

function initMap(lat= -25.363,lng = 131.044, texto="")
{
    var myLatLng = {lat: lat, lng: lng};

    // Create a map object and specify the DOM element
    // for display.
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      zoom: 16
    });

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: myLatLng,
      title: texto
    });
}

// PAGINACION
var pageNum = 0, pageOffset = 0;
function InicializacionPaginacion(baseElement, pages, pageShow)
{
    $(baseElement).unbind("click");
    _initNav(baseElement,pageShow,pages);
    inicializacionPaginacion = true;
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
        if($(e.target).is('.nav-btn'))
        {
            var toPage;
            if($(this).hasClass('prev'))
            {
                toPage = pageNum-1;
                if(toPage >= 0)
                {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            }
            else
            {
                toPage = pageNum+1;
                if(toPage < totalpaginas)
                {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            }
        }
        else
        {
            var toPage = $(this).index();
            paginacion = (toPage + 1);
            IniciarVista();
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

/// descarga de excel

$(document).on('click','.mdi-file-excel',function () {
    let filtros = JSON.stringify(arrayFiltros)
    $('#filtros_busqueda').val('')
    $('#filtros_busqueda').val(filtros)
    $('#descargar-excel').submit()
});
