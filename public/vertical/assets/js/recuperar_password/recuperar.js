$(document).ready(function () {
    let formulario = $('#formRecuperar').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    formulario.options.equaltoMessage = "Las contraseña no coinciden";
});

function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn(); }
function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut(); }

function OnClickRecuperar() 
{
    var form = $('#formRecuperar');
    form.parsley().validate();    
    let nuevoPassword = $('#paswordRegistro').val();

    if (form.parsley().isValid())
    {
        $.ajax({
            type: 'POST',
            url: '/cambiarPassword',
            data: 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                idUsuario:idUsuario,
                nuevoPassword:nuevoPassword
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
                    case 201:
                          Swal.fire({
                            title: 'Excelente',
                            text: "Se realizó el cambio de tu contraseña correctamente",
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar',
                            allowOutsideClick: false
                          }).then((result) => {
                            let url = window.location.origin;
                            window.location.href = url;      
                          });
                        
                        break;

                    case 401:
                        $('.errorTextos').html(data.mensaje).removeClass('hidden');
                        break;
                
                    default:
                        break;
                }
                
            },
            error: function(data) 
            {
                CargandoNoMostrar()
            }
        });
    }    
}

$('.guardarCambios').on('click',OnClickRecuperar);