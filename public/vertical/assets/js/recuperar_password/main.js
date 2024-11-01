$(document).ready(function () {
    let formulario = $('#formRecuperar').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
});

function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn(); }
function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut(); }

function OnClickRecuperar() 
{
    var form = $('#formRecuperar');
    form.parsley().validate();    
    let email = $('#email').val();

    if (form.parsley().isValid())
    {
        $.ajax({
            type: 'POST',
            url: '/recuperarPassword',
            data: 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email:email,
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
                        Swal.fire(
                            'Excelente',
                            'Se ha enviado un correo con las instrucciones para recuperar tu contraseña',
                            'success',
                          ).then((result) => {
                            let url = window.location.origin;
                            window.location.href = url;      
                          });
                        break;

                    case 404:
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

$('.recuperarPassword').on('click',OnClickRecuperar);