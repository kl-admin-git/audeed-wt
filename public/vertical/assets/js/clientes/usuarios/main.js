$(document).ready(function() 
{
    // $('#datatable').DataTable();
    // var table = $('#datatable-buttons')
    // .on( 'order.dt',  function () { console.log('Order' ); } )
    // .on( 'search.dt', function () {console.log('Search' ); } )
    // .on( 'page.dt',   function () { console.log('Page' ); } )
    // .DataTable({
    //     buttons: ['excel'],
    //     // buttons: [{
    //     //     extend: "excel",
    //     //     text: '<i class="mdi mdi-file-excel-box"></i>',
    //     //     className: "img-excel"
    //     // }],
    //     "language": {
    //         "lengthMenu": "Mostrar _MENU_ registros por página.",
    //         "zeroRecords": "Lo sentimos. No se encontraron registros.",
    //         "info": "Mostrando página _PAGE_ de _PAGES_",
    //         "infoEmpty": "No hay registros aún.",
    //         "infoFiltered": "(filtrados de un total de _MAX_ registros)",
    //         "search": "Búsqueda",
    //         "LoadingRecords": "Cargando ...",
    //         "Processing": "Procesando...",
    //         "SearchPlaceholder": "Comience a teclear...",
    //         "paginate": {
    //             "previous": "Anterior",
    //             "next": "Siguiente",
    //         }
    //     },
    //     "processing": false,
    //     "serverSide": false,
    //     "sort": false,
    //     "lengthChange": false,
    //     "order": [],
    // });

    // table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    let formulario = $('#formularioCreacion').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2();

    InicializacionPaginacion($('.pagination'), 25, 5);
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
    $('#crearUsuarioPopUp').find('.modal-title').html('Crear usuarios');
    $('#crearUsuarioPopUp').find('.crearUsuario').html('Crear usuario');
    $('#crearUsuarioPopUp').modal('show');
}

function OnClickCerrarCrearUsuarioPopUp() 
{
    $('#crearUsuarioPopUp').modal('hide');
    $('#formularioCreacion').find('input[type="text"]').val('');
    $('#formularioCreacion').find('input[type="password"]').val('');
    $('#formularioCreacion').find('input[type="file"]').val('');
    $('#formularioCreacion').parsley().reset();
}

function OnClickEliminarUsuario() 
{
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
        Swal.fire(
            'Eliminado!',
            'El usuario ha sido eliminado.',
            'success'
        )
    });    
}

function OnClickEditarUsuario() 
{
    $('#crearUsuarioPopUp').find('.modal-title').html('Editar usuarios');
    $('#crearUsuarioPopUp').find('.crearUsuario').html('Actualizar');
    $('#crearUsuarioPopUp').modal('show');
}

$('#crearUsuario').on('click',OnClickCrearUsuarioPopUp);
$('.cancelarPopUp').on('click',OnClickCerrarCrearUsuarioPopUp);
$('.crearUsuario').on('click',OnClickFormulario);

// PAGINACION
var pageNum = 0, pageOffset = 0;
function InicializacionPaginacion(baseElement, pages, pageShow) 
{
    _initNav(baseElement,pageShow,pages);
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
        if($(e.target).is('.nav-btn')){
        var toPage = $(this).hasClass('prev') ? pageNum-1 : pageNum+1;
        }else{
        var toPage = $(this).index(); 
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