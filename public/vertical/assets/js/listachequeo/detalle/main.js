$(document).ready(function () {
    //console.log(seccionCuatro);
    if (seccionCuatro != undefined) {
        if (seccionCuatro.length != 0) {
            let arrayLabels = [];
            let arrayValores = [];
            $.each(seccionCuatro, function (indexInArray, seccion) {
                arrayLabels.push(seccion.respuesta);
                arrayValores.push(seccion.cant);
            });
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: arrayLabels,
                    datasets: [{
                        data: arrayValores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    }],

                },
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }

            });
        }
    }

});


function OnClickToggle(e) {
    e.preventDefault();
    console.log('inside');
    // $(this).parent().find('.animacionColl').collapse('toggle');
    $(this).parent().find('.animacionColl').slideDown("slow", function () {
        // Animation complete.
    });
}

function AbrirPopUpImagen() {
    let urlImagen = $(this).attr('src');
    $('#popUpImagenAmplia').modal('show');

    $('.imagenAmpliaDetalle').attr('src', urlImagen);
}

function AbrirPopUpAdjuntos(control,e) {
    e.preventDefault();
    let idRespuesta = $(control).attr('resp')
    $.ajax({
        type: 'POST',
        url: '/listachequeo/detalle/adjuntosConsulta',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idResp: idRespuesta
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            let tabla = $('.tablaAdjuntos > tbody')
            let datos = ''
            data.datos.forEach(el => {
                datos += ` <tr>
                            <th scope="row">${el.nombre}</th>
                            <td>${el.fecha_subida}</td>
                            <td><a href="/listachequeo/detalle/descargarAdjunto/${el.id}" class="linkDescargar"  ><span class="mdi mdi-cloud-download" ></span></a></td>
                        </tr>`
                //<td><a href="${el.urlDescarga}" class="linkDescargar" target="_blank" download><span class="mdi mdi-cloud-download" ></span></td>
            })
            tabla.append(datos)
        },
        error: function (e) {}
    })
    $('#popUpAdjuntos').modal('show')
}

function clickPlanAccionManual(control) {
    let idPregunta = $(control).attr('idpregunta')
    let listaChequeoEje = $('.datosLista').attr('idlistachequeoejecutada')
    $.ajax({
        type: 'POST',
        url: '/listachequeo/detalle/cargar_plan_accion_manual',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            idpregunta: idPregunta,
            idlistachequeo: listaChequeoEje
        },
        cache: false,
        dataType: 'json',
        beforeSend: function () {
            CargandoMostrar();
        },
        success: function (res) {
            let html = ''
            let etiqueta = 'input'
            let tipo_input = 'text'
            let conte_textarea = ''

            $.each(res.data, function (i, el) {
                    etiqueta = 'input'
                    tipo_input = 'text'
                    conte_textarea = ''

                    if (el.id_opcion == 4) {
                        tipo_input = 'date'
                    } else if (el.id_opcion == 7) {
                        tipo_input = 'number'
                    } else if (el.id_opcion == 9) {
                        etiqueta = 'textarea'
                        conte_textarea = el.respuesta
                    }

                    if(el.id_opcion != 9){
                        html += `
                        <div class="form-group form-inputs" >
                            <label for="exampleFormControlInput1">${el.opcion}</label>
                            <${etiqueta} type="${tipo_input}" class="form-control input-plan-accionm"  value="${el.respuesta}" readonly  />${conte_textarea}
                        </div>
                    `
                    }else{
                        html += `
                        <div class="form-group form-inputs" >
                            <label for="exampleFormControlInput1">${el.opcion}</label>
                            <textarea class="form-control input-plan-accionm"  readonly >${conte_textarea}</textarea>
                        </div>
                    ` 
                    }
                   
                })

            $('.modal-body').append(html)
            CargandoNoMostrar()
            $('#modal-plan-manual').modal('show')

        },
        error: function (error) {

        }
    })
}

$('#modal-plan-manual').on('hidden.bs.modal', function (e) {
    $('.modal-body').html('')
})

function cerrarPopUpAdjuntos() {
    $('.tablaAdjuntos > tbody').html('')
    $('#popUpAdjuntos').modal('hide')
}

$('.categoriaItem').on('click', OnClickToggle);
$('.imagenesReportes').on('click', AbrirPopUpImagen);
$('.categoriaItem').on('click', OnClickToggle);
$('.cancelarPopUpAdjuntos').on('click', cerrarPopUpAdjuntos)

//ROBERTO JOSE ARENAS MAZUERA //

$(document).on('click', '.descarga-excel-pdf', function () {
    const listaId = parseInt(window.location.href.split('/')[5])
    const tipo = $(this).attr('tipo')
    $('#tipo').val('')
    $('#listaId').val('')
    $('#listaId').val(listaId)
    $('#tipo').val(tipo)

    $('#descargar-excel').submit()
});