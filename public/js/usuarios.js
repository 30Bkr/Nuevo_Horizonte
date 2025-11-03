// ... (código anterior)

$(document).ready(function() {

    // Manejar el envío del formulario de creación
    $('#formCrearUsuario').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize() + '&action=crear';

        $.ajax({
            type: "POST",
            // RUTA ACTUALIZADA: Sube dos niveles (de admin/usuarios/ a la raíz) 
            // y luego entra a app/controllers/
            url: "../../app/controllers/controller_usuarios.php", 
            data: formData,
            dataType: "json",
            
            // ... (el resto del código de Ajax sigue igual)
        });
    });
});