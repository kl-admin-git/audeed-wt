$(document).ready(function() 
{
    $('#datatable').DataTable();
    var table = $('#datatable-buttons').DataTable({
        buttons: ['excel'],
        // buttons: [{
        //     extend: "excel",
        //     text: '<i class="mdi mdi-file-excel-box"></i>',
        //     className: "img-excel"
        // }],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página.",
            "zeroRecords": "Lo sentimos. No se encontraron registros.",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros aún.",
            "infoFiltered": "(filtrados de un total de _MAX_ registros)",
            "search": "Búsqueda",
            "LoadingRecords": "Cargando ...",
            "Processing": "Procesando...",
            "SearchPlaceholder": "Comience a teclear...",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente",
            }
        },
        "processing": false,
        "serverSide": false,
        "sort": false,
        "lengthChange": false,
        "order": []
    });

    table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    let formulario = $('#formularioCreacion').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2();
});

function OnClickFormulario(e) 
{ 
    e.preventDefault();
    var form = $('#formularioCreacion');

    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        console.log('Después de validar todo');
    }
}

function OnClickCrearUsuarioPopUp() 
{
    $('#crearUsuarioPopUp').modal('show');
}

function OnClickCerrarCrearUsuarioPopUp() 
{
    $('#crearUsuarioPopUp').modal('hide');
    $('#formularioCreacion').find('input[type="text"]').val('');
    $('#formularioCreacion').parsley().reset();
}

$('#crearUsuario').on('click',OnClickCrearUsuarioPopUp);
$('.cancelarPopUp').on('click',OnClickCerrarCrearUsuarioPopUp);
$('.crearUsuario').on('click',OnClickFormulario);

