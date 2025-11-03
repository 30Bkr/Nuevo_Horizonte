// Esperamos a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {

    // 1. Lógica para CREAR USUARIO
    const formCrearUsuario = document.getElementById('formCrearUsuario');

    if (formCrearUsuario) {
        formCrearUsuario.addEventListener('submit', function(e) {
            e.preventDefault(); // Evitamos el envío normal

            const formData = new FormData(this);
            // Añadimos la 'action' que espera el controlador
            formData.append('action', 'crear');

            fetch('/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ¡Éxito! Usamos SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirigimos al listado
                        window.location.href = 'usuarios_listado.php';
                    });
                } else {
                    // Error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor.'
                });
            });
        });
    }

    // 2. Lógica para ACTUALIZAR USUARIO (próximo paso)
    // ...

});