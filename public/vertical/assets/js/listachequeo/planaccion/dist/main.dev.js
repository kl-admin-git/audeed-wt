"use strict";

var paginacion = 1;
var totalpaginas = 0;
var inicializacionPaginacion = false;
var arrayFiltros = {};
$(document).ready(function () {
  // Select2
  $(".select2").select2({}); // Date Picker

  $('#datepicker-autoclose').datepicker({
    autoclose: true,
    format: 'dd/mm/yyyy',
    language: 'es'
  });
  IniciarVista();
});

function IniciarVista() {
  arrayFiltros['filtro_realizacion'] = $('#datepicker-autoclose').val();
  arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
  arrayFiltros['filtro_evaluado'] = $('.evaluadoSearch').val();
  arrayFiltros['filtro_evaluador'] = $('.evaluadorSearch').val();
  arrayFiltros['filtro_codigo'] = $('.codigoSearch').val();
  arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();
  $.ajax({
    type: 'POST',
    url: '/listachequeo/planaccion/traerPlanesDeAccion',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      paginacion: paginacion,
      arrayFiltros: JSON.stringify(arrayFiltros)
    },
    cache: false,
    dataType: 'json',
    beforeSend: function beforeSend() {
      CargandoMostrar();
    },
    success: function success(data) {
      CargandoNoMostrar();

      switch (data.codigoRespuesta) {
        case 202:
          $('#tablaPlanAccion tbody').html('');
          var stringTabla = '';
          $.each(data.datos.planesAccion, function (indexInArray, item) {
            var stringBadge = '';

            if (item.tipo_plan_accion == 1) {
              stringBadge = "<span class=\"badge badge-danger\">Automatico</span>";
            } else if (item.tipo_plan_accion == 2) {
              stringBadge = "<span class=\"badge badge-dark\">Manual</span>";
            }

            stringTabla += "<tr idAsignacion=\"".concat(item.ID_EJECT_OPCIONES, "\">\n                                            <td>").concat(item.CODIGO_PLAN_ACCION, "</td>\n                                            <td>").concat(item.FECHA_REALIZACION, "</td>\n                                            <td>").concat(item.nombre, "</td>\n                                            <td>").concat(item.EMPRESA, "</td>\n                                            <td>").concat(item.evaluado == undefined ? '' : item.evaluado, "</td>\n                                            <td>").concat(item.evaluador, "</td>\n                                            <td>").concat(item.pregunta, "</td>\n                                            <td>").concat(item.respuesta, "</td>\n                                            <td>").concat(item.OBSERVACION, "</td>\n                                            <td>").concat(stringBadge, "</td>\n                                            <td>\n                                                <a href=\"/listachequeo/planaccion/seguimiento/").concat(item.ejecutada_id, "\">Seguimiento</a> \n                                            </td>\n                                        </tr>   ");
          });
          if (data.datos.planesAccion.length == 0) stringTabla = '<tr><td class="text-center" colspan="12">No tienes registros actualmente</td></tr>';
          $('#tablaPlanAccion tbody').html(stringTabla);
          totalpaginas = data.datos.cantidadTotal;
          if (!inicializacionPaginacion) InicializacionPaginacion($('.pagination'), data.datos.cantidadTotal, paginacion);
          break;

        case 402:
          toastr.error(data.mensaje);
          break;

        default:
          break;
      }
    },
    error: function error(data) {
      CargandoNoMostrar();
    }
  });
}

var controlTemporalBadge; // function OnClickAsignarBoton(control) 
// {
//     let pregunta = $(control).attr('pregunta');
//     let respuesta = $(control).attr('respuesta');
//     let evaluado = $(control).attr('evaluado');
//     $('.preguntaTexto').html(pregunta);
//     $('.respuestaTexto').html(respuesta);
//     $('.evaluadoTexto').html(evaluado);
//     controlTemporalBadge = $(control).parent().parent().find('.badge');
//     $('.asignarPlanAccionBoton').attr('idOpcPlanAccion',$(control).parent().parent().attr('idasignacion'));
//     $.ajax({
//         type: 'POST',
//         url: '/listachequeo/planaccion/traerCorrectivos',
//         data: {
//             _token: $('meta[name="csrf-token"]').attr('content'),
//         },
//         cache: false,
//         dataType: 'json',
//         beforeSend: function()
//         {
//             CargandoMostrar();
//         },
//         success: function(data)
//         {
//             CargandoNoMostrar();
//             switch (data.codigoRespuesta)
//             {
//                 case 202:
//                      // CARGA CORRECTIVO
//                      $('.correctivo').html('');
//                      $.each(data.datos, function (key, value) 
//                      { 
//                          $('.correctivo')
//                          .append($("<option></option>")
//                          .attr("value",value.id)
//                          .text(value.titulo)); 
//                      });
//                     $(".correctivo").select2({}); 
//                     $('#asignacionPlanAccion').modal('show');
//                     break;
//                 case 402:
//                     toastr.error(data.mensaje);
//                     break;
//                 default:
//                     break;
//             }
//         },
//         error: function(data)
//         {
//             CargandoNoMostrar();
//         }
//     });
// }

function OnClickSeleccionarColor() {
  $.each($('.contenedorAccionCorrectiva').find('.selectorColores'), function (indexInArray, selector) {
    $(selector).removeClass('scaleAnimate');
  });
  $(this).addClass('scaleAnimate');
}

function OnClickCorrectivaPlanAccion() {
  if ($('#tituloCorrectivo').val() == '') {
    toastr.warning('Debes colocar un título');
    return;
  }

  if ($('.contenedorAccionCorrectiva').find('.scaleAnimate').length == 0) {
    toastr.warning('Debes seleccionar el color de la acción correctiva');
    return;
  }

  var color = '';
  $.each($('.contenedorAccionCorrectiva').find('.selectorColores'), function (indexInArray, item) {
    if ($(item).hasClass('scaleAnimate')) color = $(item).attr('color');
  });
  var objetoEnviar = {
    _token: $('meta[name="csrf-token"]').attr('content'),
    titulo: $('#tituloCorrectivo').val(),
    descripcion: $('#descripcion').val(),
    color: color
  };
  $.ajax({
    type: 'POST',
    url: '/listachequeo/planaccion/crearAccionCorrectiva',
    data: objetoEnviar,
    cache: false,
    dataType: 'json',
    beforeSend: function beforeSend() {
      CargandoMostrar();
    },
    success: function success(data) {
      CargandoNoMostrar();

      switch (data.codigoRespuesta) {
        case 200:
          toastr.success(data.mensaje);
          LimpiarCajasCorreccion();
          var stringTabla = ''; // CARGA CORRECTIVO

          $('.correctivo').html('');
          $.each(data.datos.opcionesSelects, function (indexInArray, item) {
            stringTabla += "<tr idCorrectivo=\"".concat(item.id, "\">\n                                            <td>").concat(item.titulo, "</td>\n                                            <td>").concat(item.descripcion == undefined ? '' : item.descripcion, "</td>\n                                            <td><span class=\"badge badge-").concat(item.color, "\">").concat(item.titulo, "</span></td>\n                                            <td><button type=\"button\" class=\"btn btn-danger waves-effect m-l-5\" idCorrectivo=\"").concat(item.id, "\" onclick=\"OnClickEliminarCorrectivo(this);\" >Eliminar</button></td>\n                                        </tr>");
            $('.correctivo').append($("<option></option>").attr("value", item.id).text(item.titulo));
          });
          $(".correctivo").select2({});
          $('#tablaCorrectivos tbody').html(stringTabla);
          break;

        case 402:
          toastr.error(data.mensaje);
          break;

        default:
          break;
      }
    },
    error: function error(data) {
      CargandoNoMostrar();
    }
  });
}

function OnClickTraerCorrectivos() {
  $.ajax({
    type: 'POST',
    url: '/listachequeo/planaccion/traerCorrectivos',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    cache: false,
    dataType: 'json',
    beforeSend: function beforeSend() {
      CargandoMostrar();
    },
    success: function success(data) {
      CargandoNoMostrar();

      switch (data.codigoRespuesta) {
        case 202:
          var stringTabla = '';
          $.each(data.datos, function (indexInArray, item) {
            stringTabla += "<tr idCorrectivo=\"".concat(item.id, "\">\n                                            <td>").concat(item.titulo, "</td>\n                                            <td>").concat(item.descripcion == undefined ? '' : item.descripcion, "</td>\n                                            <td><span class=\"badge badge-").concat(item.color, "\">").concat(item.titulo, "</span></td>\n                                            <td><button type=\"button\" class=\"btn btn-danger waves-effect m-l-5\" idCorrectivo=\"").concat(item.id, "\" onclick=\"OnClickEliminarCorrectivo(this);\" >Eliminar</button></td>\n                                        </tr>");
          });
          $('#tablaCorrectivos tbody').html(stringTabla);
          $('#creacionPlanAccion').modal('show');
          break;

        case 402:
          toastr.error(data.mensaje);
          break;

        default:
          break;
      }
    },
    error: function error(data) {
      CargandoNoMostrar();
    }
  });
}

function OnClickEliminarCorrectivo(control) {
  var idCorrectivo = $(control).attr('idCorrectivo');
  $.ajax({
    type: 'POST',
    url: '/listachequeo/planaccion/eliminarAccionCorrectiva',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      idCorrectivo: idCorrectivo
    },
    cache: false,
    dataType: 'json',
    beforeSend: function beforeSend() {
      CargandoMostrar();
    },
    success: function success(data) {
      CargandoNoMostrar();

      switch (data.codigoRespuesta) {
        case 206:
          toastr.success(data.mensaje);
          var stringTabla = ''; // CARGA CORRECTIVO

          $('.correctivo').html('');
          $.each(data.datos, function (indexInArray, item) {
            stringTabla += "<tr idCorrectivo=\"".concat(item.id, "\">\n                                            <td>").concat(item.titulo, "</td>\n                                            <td>").concat(item.descripcion == undefined ? '' : item.descripcion, "</td>\n                                            <td><span class=\"badge badge-").concat(item.color, "\">").concat(item.titulo, "</span></td>\n                                            <td><button type=\"button\" class=\"btn btn-danger waves-effect m-l-5\" idCorrectivo=\"").concat(item.id, "\" onclick=\"OnClickEliminarCorrectivo(this);\" >Eliminar</button></td>\n                                        </tr>");
            $('.correctivo').append($("<option></option>").attr("value", item.id).text(item.titulo));
          });
          $(".correctivo").select2({});
          $('#tablaCorrectivos tbody').html(stringTabla);
          break;

        case 406:
          toastr.error(data.mensaje);
          break;

        default:
          break;
      }
    },
    error: function error(data) {
      CargandoNoMostrar();
    }
  });
}

function LimpiarCajasCorreccion() {
  $('#tituloCorrectivo').val('');
  $('#descripcion').val('');
  $('.contenedorAccionCorrectiva').find('.selectorColores').removeClass('scaleAnimate');
}

function OnClickCancelarCorrectivaPlanAccion() {
  LimpiarCajasCorreccion();
  $('#creacionPlanAccion').modal('hide');
}

function OnClickPlanAccionAsignar() {
  var idOpcPlanAccion = $(this).attr('idOpcPlanAccion');
  var idCritico = $('.correctivo').val();
  $.ajax({
    type: 'POST',
    url: '/listachequeo/planaccion/asignacionDeCritico',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      idOpcPlanAccion: idOpcPlanAccion,
      idCritico: idCritico
    },
    cache: false,
    dataType: 'json',
    beforeSend: function beforeSend() {
      CargandoMostrar();
    },
    success: function success(data) {
      CargandoNoMostrar();

      switch (data.codigoRespuesta) {
        case 201:
          toastr.success(data.mensaje);
          var padre = $(controlTemporalBadge).parent();
          $(padre).html("<span class=\"badge badge-".concat(data.datos.color, "\">").concat(data.datos.titulo, "</span>"));
          OnClickCancelarPopUpCorreccion();
          break;

        case 402:
          toastr.error(data.mensaje);
          break;

        default:
          break;
      }
    },
    error: function error(data) {
      CargandoNoMostrar();
    }
  });
}

function OnClickCancelarPopUpCorreccion() {
  $('#asignacionPlanAccion').modal('hide');
}

function OnClickRestablecerBusqueda() {
  var url = window.location.origin + '/listachequeo/planaccion';
  window.location.href = url;
}

function OnClickBuscarBoton() {
  paginacion = 1;
  inicializacionPaginacion = false;
  $('.pagination').html("<div class=\"nav-btn prev\"></div>\n                        <ul class=\"nav-pages\"></ul>\n                        <div class=\"nav-btn next\"></div>");
  pageNum = 0;
  pageOffset = 0;
  IniciarVista();
} /// DESCARGAR EL EXCEL


$(document).on('click', '.mdi-file-excel', function () {
  var filtros = JSON.stringify(arrayFiltros);
  $('#filtros_busqueda').val('');
  $('#filtros_busqueda').val(filtros);
  $('#descargar-excel-planAccion').submit();
});
$('.asignarBoton').on('click', OnClickAsignarBoton);
$('.crearPlan').on('click', OnClickTraerCorrectivos);
$('.selectorColores').on('click', OnClickSeleccionarColor);
$('.crearAccionCorrectiva').on('click', OnClickCorrectivaPlanAccion);
$('.cancelarPopUpCreacion').on('click', OnClickCancelarCorrectivaPlanAccion);
$('.asignarPlanAccionBoton').on('click', OnClickPlanAccionAsignar);
$('.cerrarPopUpAsignacion').on('click', OnClickCancelarPopUpCorreccion);
$('.buscarBoton').on('click', OnClickBuscarBoton);
$('.restablecerBoton').on('click', OnClickRestablecerBusqueda); // PAGINACION

var pageNum = 0,
    pageOffset = 0;

function InicializacionPaginacion(baseElement, pages, pageShow) {
  $(baseElement).unbind("click");

  _initNav(baseElement, pageShow, pages);

  inicializacionPaginacion = true;
}

function _initNav(baseElement, pageShow, pages) {
  //create pages
  for (i = 1; i < pages + 1; i++) {
    $((i == 1 ? '<li class="active">' : '<li>') + i + '</li>').appendTo('.nav-pages', baseElement).css('min-width', '4em');
  } //calculate initial values


  function ow(e) {
    return e.first().outerWidth();
  }

  var w = ow($('.nav-pages li', baseElement)),
      bw = ow($('.nav-btn', baseElement));
  baseElement.css('width', w * (pages <= 5 ? pages : pageShow) + bw * (pages <= 5 ? 2 : 4) + 'px');
  $('.nav-pages', baseElement).css('margin-left', bw + 'px'); //init events

  baseElement.on('click', '.nav-pages li, .nav-btn', function (e) {
    if ($(e.target).is('.nav-btn')) {
      var toPage;

      if ($(this).hasClass('prev')) {
        toPage = pageNum - 1;

        if (toPage >= 0) {
          paginacion = toPage + 1;
          IniciarVista();
        }
      } else {
        toPage = pageNum + 1;

        if (toPage < totalpaginas) {
          paginacion = toPage + 1;
          IniciarVista();
        }
      }
    } else {
      var toPage = $(this).index();
      paginacion = toPage + 1;
      IniciarVista();
    }

    _navPage(baseElement, pages, toPage, pageShow);
  });
}

function _navPage(baseElement, pages, toPage, pageShow) {
  var sel = $('.nav-pages li', baseElement),
      w = sel.first().outerWidth(),
      diff = toPage - pageNum;

  if (toPage >= 0 && toPage <= pages - 1) {
    sel.removeClass('active').eq(toPage).addClass('active');
    pageNum = toPage;
  } else {
    return false;
  }

  if (toPage <= pages - (pageShow + (diff > 0 ? 0 : 1)) && toPage >= 0) {
    pageOffset = pageOffset + -w * diff;
  } else {
    pageOffset = toPage > 0 ? -w * (pages - pageShow) : 0;
  }

  sel.parent().css('left', pageOffset + 'px');
} // PAGINACION - FIN