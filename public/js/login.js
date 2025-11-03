$(document).ready(function() {

    $('#formLogin').on('submit', function(e) {
        e.preventDefault();

        let usuario = $('#usuario').val().trim();
        let contrasena = $('#contrasena').val().trim();

        if (usuario === "" || contrasena === "") {
            mostrarAlerta('error', 'Campos Vacíos', 'Por favor, introduce tu usuario y contraseña.');
            return;
        }

        $.ajax({
            type: "POST",
            // ACTUALIZADO: Apunta al nuevo controlador
            url: "controller_login.php", 
            data: $(this).serialize(),
            dataType: "json",
            
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('Ingresando...');
            },

            success: function(response) {
                if (response.status === 'success') {
                    mostrarAlerta('success', '¡Bienvenido!', 'Acceso concedido. Redirigiendo...', 2000);
                    
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);

                } else {
                    mostrarAlerta('error', 'Error de Acceso', response.message);
                }
            },

            error: function(xhr, status, error) {
                console.error("Error en Ajax: ", error);
                mostrarAlerta('error', 'Error del Servidor', 'No se pudo conectar con el servidor.');
            },

            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('Ingresar');
            }
        });
    });

    function mostrarAlerta(icon, title, text, timer = 6000) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: timer,
            timerProgressBar: true,
            showCloseButton: true,
            showConfirmButton: false,
        });
    }
});