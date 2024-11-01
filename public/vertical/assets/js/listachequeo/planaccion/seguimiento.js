Dropzone.autoDiscover = false;
var myDropzone = new Dropzone(".dropzone", 
{
    method:"post",
    parallelUploads: 10,
    dictDefaultMessage: "Arrastra o click sobre este cuadro",
    dictRemoveFile: "Eliminar archivo",
    dictCancelUpload: "Cancelar subida",
    autoProcessQueue: false,
    addRemoveLinks: true,
    success: function (file, response) 
    {
        file.previewElement.classList.add("dz-success");
    },
    error: function (file, response) {
        file.previewElement.classList.add("dz-error");
        toastr.success("La información no pudo guardarse");
    },
    complete: function (file) 
    {  
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) 
        {
            toastr.success("Seguimiento guardado correctamente");
            $('.cancelarSeguimiento').trigger('click');
            CargarSeguimientos();
        }
    }
});

var estados=[];
estados[1] = 'primary';
estados[2] = 'warning';
estados[3] = 'danger';


$(document).ready(function () 
{
    $('.select2').select2();
    CargarSeguimientos();
});

function CargarSeguimientos() 
{
    let idPlanDeAccion = $('.idPlanAccion').val();

    $.ajax({
        type: 'POST',
        url: '/listachequeo/planaccion/seguimiento/cargar',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idPlanDeAccion: idPlanDeAccion
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrar();
        },
        success: function(data) 
        {
            let stringSegumiento = '';
            $.each(data.datos, function (indexInArray, itemSeguimiento) 
            { 
                 stringSegumiento += ComponenteSeguimiento(
                    itemSeguimiento.ID_SEGUIMIENTO,
                    itemSeguimiento.ESTADO,
                    itemSeguimiento.ESTADO_NUMERO,
                    itemSeguimiento.FECHA,
                    itemSeguimiento.USUARIO,
                    itemSeguimiento.CARGO,
                    itemSeguimiento.OBSERVACION,
                    itemSeguimiento.ADJUNTOS                    
                 )
            });

            if(data.datos.length == 0)
            {
                stringSegumiento = `<div class="col-lg-12 card-audeed-seguimiento d-flex">
                                            <p class="col-lg-12 font-weight-bold text-center">
                                            No tienes seguimientos para este plan de acción
                                            </p>
                                    </div>`;
            }

            CargandoNoMostrar();

            $('.cuerpoSeguimientoGeneral').html(stringSegumiento);
        },
        error: function(data) 
        {
            
        }
    });
}

function ComponenteSeguimiento(
    idSeguimiento,
    estadoSeguimiento,
    estadoIdSeguimiento,
    fechaSeguimiento,
    UsuarioSeguimiento,
    cargoUsuario,
    observacionSeguimiento,
    archivosAdjuntos) 
{
    let stringAdjuntos = '';
    $.each(archivosAdjuntos, function (indexInArray, adjunto) 
    { 
        if(adjunto.ID_ADJUNTO != undefined && adjunto.ID_ADJUNTO != null)
        {
            stringAdjuntos += `
            <a href="/listachequeo/planaccion/seguimiento/descargarAdjuntoSeguimiento/${adjunto.ID_ADJUNTO}" style="display: block;" class="p-0 m-0" title="${ adjunto.NOMBRE_REAL }">
                <i class="mdi ${ adjunto.ICONO } mr-2 text-audeed-crop"></i>
                ${ adjunto.NOMBRE_REAL }
            </a>`;
        }
        
    });

    let stringSeguimiento = ` <div class="col-lg-12 card-audeed-seguimiento d-flex" idSeguimiento="${idSeguimiento}">
                                    <div class="col-lg-4">
                                        <span class="badge badge-${estados[estadoIdSeguimiento]} text-uppercase mb-3">${ estadoSeguimiento }</span>
                                        <p class="p-0 m-0">${ fechaSeguimiento }</p>
                                        <p class="p-0 m-0">${ UsuarioSeguimiento }</p>
                                        <p class="p-0 m-0">${ cargoUsuario }</p>
                                    </div>

                                    <div class="col-lg-4 text-center">
                                        <p id="" placeholder="Observación del auditor" name="pregunta" rows="4" class="textAreaNoEditable">${ observacionSeguimiento }</p>
                                    </div>

                                    <div class="col-lg-4 text-left">
                                        ${stringAdjuntos}
                                    </div>
                             </div>`;

    return stringSeguimiento;
}

function OnClickBotonSeguimiento() 
{
    $("#agregarSeguimiento").modal('show');
}

function OnClickCancelarSeguimientoPopUp() 
{
    $('.cuerpo-modal-seguimiento').scrollTop(0);
    $('.selectEstadoPopUp').val(0).change();
    $('#descripcion').val('');
    $('#agregarSeguimiento').modal('hide');
    
    $('.dropzone')[0].dropzone.files.forEach(function(file) 
    { 
        file.previewElement.remove(); 
    });
    
    $('.dropzone').removeClass('dz-started');
}

function OnClickGuardarSeguimiento() 
{
    let estado = $('.selectEstadoPopUp').val();
    let observacion = $('#descripcion').val();

    if(estado == 0)
    {
        toastr.warning("Debes seleccionar un estado");
        return;
    }

    $('#estadoHidden').val(estado);
    $('#descripcionHidden').val(observacion);

    let idPlanDeAccion = $('.idPlanAccion').val();
    let idListaEject = $('.idListaEject').val();
    let tipoPlanAccion = $('.tipoPlanAccion').val();
    
    $.ajax({
        type: 'POST',
        url: '/listachequeo/planaccion/seguimiento/guardarSeguimiento',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idPlanDeAccion: idPlanDeAccion,
            idListaEject: idListaEject,
            tipoPlanAccion:tipoPlanAccion,
            estado: estado,
            descripcion: observacion
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
                case 205:
                    $('.idSeguimiento').val(data.datos);
                    myDropzone.processQueue();
                    if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) 
                    {
                        toastr.success("Seguimiento guardado correctamente");
                        $('.cancelarSeguimiento').trigger('click');
                        CargarSeguimientos();
                    }
                    
                    break;
            
                default:
                    break;
            }
        },
        error: function(data) 
        {
            
        }
    });
}

$('.botonAgregarSeguimiento').on('click',OnClickBotonSeguimiento);
$('.guardarSegumiento').on('click',OnClickBotonSeguimiento);
$('.cancelarSeguimiento').on('click',OnClickCancelarSeguimientoPopUp);
$('.guardarSegumiento').on('click',OnClickGuardarSeguimiento);
