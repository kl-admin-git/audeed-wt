var backgoundColorGrafica = "#21252963"
var borderColorGrafica = "#44505c"


var hoverBackgroundGrafica = "#59b74973"
var hoverBorderColorGrafica = "#4fb64852"



$(document).ready(function () {
  promedioResultadoFinal();
  // promedioCategoria()
})

//Enventos Botones
// $(document).on('click', '.btn-mes', function (indice, ele) {
//     filtroPorMes = true
//     checkIndividualBotonMes($(this).text())
// })

//Fin Eventos Botonos

//Funciones
function resetCanvas(canvasID, padreCanvas) {
  if (myBarChart != null) {
    $(`#${canvasID}`).remove(); // this is my <canvas> element
    $(`#${padreCanvas} > div > div`).append(`<canvas id="${canvasID}" width="100" height="100"><canvas>`);
  }
}

function promedioResultadoFinal() {

  let options = {
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        stacked: true,
        gridLines: {
          display: true,
          color: "rgba(255,99,132,0.2)"
        },
        ticks:{
          beginAtZero: true,
          steps: 10,
          stepValue: 5,
          max: 100
        }
      }],
      xAxes: [{
        gridLines: {
          display: false
        },
        ticks: {
          autoSkip: false
        }
      }]
    }
  }

    let objetoEnviar =
    {
      idEmpresa: $('.selectListdoEmpresas').val(),
      idListaChequeo: $('.selectListaChequeo').val(),
      serachRealizada: $('.realizadasSearch').val()
    };

    if($('.realizadasSearch').val() == 3) // SELECCIONA PERIODO
    {
        objetoEnviar['desde'] = $('#pickerDesde').val();
        objetoEnviar['hasta'] = $('#pickerHasta').val();
    }

  $.ajax({
    type: 'POST',
    url: '/informes/cumplimientoLista/consultaPromedioFinal',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      objetoEnviar: objetoEnviar
    },
    cache: false,
    dataType: 'json',
    beforeSend: function () {
      CargandoMostrar();
    },
    success: function (data) 
    {
      //PRIMERA SECCIÓN GRAFICA
      let labels = [];
      let datosParaLabels = [];
      $.each(data.datos.PrimeraSeccionGrafica, function (key, itemData) 
      { 
        labels.push(key);
        datosParaLabels.push(itemData.TotalPorEmpresa);
      });

      let datosGrafica = {
        labels: labels,
        datasets: [{
          label: "Promedio Resultado Final",
          backgroundColor: backgoundColorGrafica,
          borderColor: borderColorGrafica,
          borderWidth: 2,
          hoverBackgroundColor: hoverBackgroundGrafica,
          hoverBorderColor: hoverBorderColorGrafica,
          data: datosParaLabels,
        }]
      }

      $('.contenedorGraficaResultadoFinal').html('');
      $('.contenedorGraficaResultadoFinal').html('<canvas id="canvasPromedioResultadoFinal" width="200" height="200"></canvas>');

      graficarChartjs("canvasPromedioResultadoFinal", datosGrafica, 'bar', options);
      
      //FIN - PRIMERA SECCIÓN GRAFICA

      //PRIMERA SECCIÓN PROMEDIO GENERAL
      $('.porcentajeTexto').html(data.datos.PrimeraSeccionPromedioGeneral+'%');
      //FIN - PRIMERA SECCIÓN PROMEDIO GENERAL


      //SEGUNDA SECCIÓN GRAFICA Y CATEGORIAS

      let labelsCategorias = [];
      let datosParaLabelsCategorias = [];
      let stringCategorias = '';

      $.each(data.datos.SegundaSeccionGraficaCategoria, function (keyCategoria, itemDataCategoria) 
      { 
        labelsCategorias.push(itemDataCategoria.categoria);
        datosParaLabelsCategorias.push(itemDataCategoria.promedio_total_por_categoria);

        stringCategorias += `<li>${itemDataCategoria.categoria} <span class="porce">${itemDataCategoria.promedio_total_por_categoria}%</span></li>`;
      });

      let datosGraficaCategoria = {
        labels: labelsCategorias,
        datasets: [{
          label: "Promedio por Categoria",
          backgroundColor: backgoundColorGrafica,
          borderColor: borderColorGrafica,
          borderWidth: 2,
          hoverBackgroundColor: hoverBackgroundGrafica,
          hoverBorderColor: hoverBorderColorGrafica,
          data: datosParaLabelsCategorias,
        }]
      }

      $('.contenedorGraficaCategorias').html('');
      $('.contenedorGraficaCategorias').html('<canvas id="canvasPromedioCategoria" width="200" height="200"></canvas>');

      graficarChartjs("canvasPromedioCategoria", datosGraficaCategoria, 'bar', options);

      $('.listaInforme').html(stringCategorias);
      $('.subtituloEmpresas').html($( ".selectListdoEmpresas option:selected" ).text());

      //FIN - SEGUNDA SECCIÓN GRAFICA Y CATEGORIAS

      //TERCERA SECCIÓN TABLA

      let stringTabla = '';
      $.each(data.datos.TerceraSeccionTabla, function (indexReincidencias, itemRincidencias) 
      { 
        let stringEmpresas = '';
        $.each(itemRincidencias, function (indexEmpresas, itemEmpresas) 
        { 
          stringEmpresas += `<li>${itemEmpresas.Muestra}</li>`;
        });

        stringTabla += `<tr>
                          <td>${indexReincidencias}</td>
                          <td>
                              <ul>
                                ${stringEmpresas}
                              </ul>
                          </td>
                      </tr>`;
      });

      if(data.datos.TerceraSeccionTabla.length == 0)
        stringTabla = `<tr style="text-align: center;font-weight: bold;">
                          <td colspan="3">No tienes información a mostrar</td>
                      </tr>`;

      $('.tablaReincidencias tbody').html(stringTabla);

      //FIN - TERCERA SECCIÓN TABLA

      //CUARTA SECCIÓN TABLA

      if(data.datos.CuartaSeccionTabla.length != 0)
      {
        let stringTablaCiclo = '';
        let totalPorpregunta = 0;
        let totalPorCategoria = 0;
        let contador = 0;
        $.each(data.datos.CuartaSeccionTabla, function (indexCiclo, itemCiclo) 
        { 
          stringTablaCiclo += `<tr>
                                  <td>${indexCiclo}</td>
                                  <td>${itemCiclo.TotalPorEtiqueta}%</td>
                                  <td>${itemCiclo.categoriaResultado}%</td>
                              </tr>`;
            totalPorpregunta += parseFloat(itemCiclo.TotalPorEtiqueta);
            totalPorCategoria += parseFloat(itemCiclo.categoriaResultado);
            contador = contador + 1;
        });
        contador = 1;
        let stringFooter =  `<tr>
                                <td style="font-weight: bold">TOTAL</td>
                                <td style="font-weight: bold">${(totalPorpregunta / (contador == 0 ? 1 : contador)).toFixed(2)}%</td>
                                <td style="font-weight: bold">${(totalPorCategoria / (contador == 0 ? 1 : contador)).toFixed(2)}%</td>
                            </tr>`;;
  
        $('.tablaPorcentaje tbody').html(stringTablaCiclo);
        $('.tablaPorcentaje tfoot').html(stringFooter);
        $('.contenedorCiclo').removeClass("hidden");
      }
      else
      {
        $('.contenedorCiclo').addClass("hidden");
      }

      //FIN - CUARTA SECCIÓN TABLA
            
      CargandoNoMostrar()
    },
    error: function (ex) 
    {
      CargandoNoMostrar();
    }
  });
  
}

function conteoArray(array, busq){
  let i = 0
  let sumPonderado = 0
  array.forEach((el)=>{
    if(el.empresa == busq){
      let cat_porce = parseFloat(el.porc_cat)
      sumPonderado += cat_porce
      i++
    }
  })

  return {empresa: busq, cantidad: i, ponderado: sumPonderado}
}

function promedioCategoria() {
  let data = {
    labels: ["RECURSOS", "GESTIÓN INTEGRAL DEL SISTEMA GESTIÓN DE LA SEGURIDAD Y SALUD EN EL TRABAJO", "GESTIÓN DE LA SALUD", "GESTIÓN DE PELIGROS Y RIESGOS", "GESTIÓN DE AMENAZAS", "VERIFICACIÓN  DEL SG-SST"],
    datasets: [{
      label: "Promedio por Categoria",
      backgroundColor: backgoundColorGrafica,
      borderColor: borderColorGrafica,
      borderWidth: 2,
      hoverBackgroundColor: hoverBackgroundGrafica,
      hoverBorderColor: hoverBorderColorGrafica,
      data: [65, 59, 20, 81, 56, 55],
    }]
  }

  let options = {
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        stacked: true,
        gridLines: {
          display: true,
          color: "rgba(255,99,132,0.2)"
        }
      }],
      xAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  graficarChartjs("canvasPromedioCategoria", data, 'bar', options)

}
//Fin de Funciones


//Grafico
function graficarChartjs(selector, data, typeGrafico, options = null) {

  var ctx = document.getElementById(selector).getContext('2d')

  myBarChart = new Chart(ctx, {
    type: typeGrafico,
    data: data,
    options: options
  })

}

function OnClickBuscarBoton() 
{
    if($('.realizadasSearch ').val() == 3)
    {
        if($('#pickerDesde').val() == '' || $('#pickerHasta').val() == '')
        {
            toastr.warning('Debes completar el rango de fechas (Fecha inicial - Fecha Final)');
            return;
        }
    }

    promedioResultadoFinal();
}

$('.buscarBotonInforme').on('click',OnClickBuscarBoton);