// =================================================================
// 1. DECLARACIONES DE FUNCIONES GLOBALES (ACCESIBLES INMEDIATAMENTE)
// =================================================================

/**
 * Lógica para cargar los datos de un usuario en el modal de edición.
 */
window.cargarDatosUsuario = function(id) {
    // 1. Petición AJAX al controlador para obtener los datos del usuario por ID
    fetch('/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php?action=obtener_usuario&id_usuario=' + id)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const usuario = data.data;

            // 2. Llenar el formulario del modal
            $('#id_usuario_actualizar').val(usuario.id_usuario);
            $('#nom_usuario_actualizar').val(usuario.nom_usuario);
            $('#usuario_actualizar').val(usuario.usuario);
            $('#id_rol_actualizar').val(usuario.id_rol);

            // Limpiar campos de contraseña para no enviar hashes viejos
            $('#contrasena_actualizar').val('');
            $('#contrasena_actualizar_conf').val('');

            // 3. Abrir el modal
            $('#modalActualizarUsuario').modal('show');
        } else {
            mostrarAlertaGlobal(data.message || 'Error al cargar los datos del usuario.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error AJAX al cargar usuario:', error);
        // Error solucionado: La función mostrarAlertaGlobal ya está definida en footer_admin.php
        mostrarAlertaGlobal('Error de conexión al intentar obtener los datos del usuario.', 'danger');
    });
};

/**
 * Lógica para cambiar el estatus de un usuario (Activo/Inactivo).
 */
window.ejecutarCambioEstatus = function(id_usuario, estatus_actual, callbackRecargar) {
    // Usamos el ID para obtener la FILA y luego los datos.
    const tabla = $('#tablaUsuarios').DataTable();
    const rowData = tabla.row($(`.btn-cambio-estatus[data-id="${id_usuario}"]`).closest('tr')).data();
    
    if (!rowData) {
        mostrarAlertaGlobal('Error: No se pudo obtener la información del usuario para cambiar el estatus.', 'danger');
        return;
    }
    
    const nombreUsuario = rowData.nom_usuario;
    const nuevoEstatusTexto = estatus_actual == 1 ? 'INACTIVAR' : 'ACTIVAR';

    // *** INCLUIMOS LA CONFIRMACIÓN AQUÍ USANDO SweetAlert2 ***
    Swal.fire({
        title: `¿Confirmar ${nuevoEstatusTexto} Usuario?`,
        text: `Estás a punto de ${nuevoEstatusTexto.toLowerCase()} a ${nombreUsuario}. ¿Continuar?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${nuevoEstatusTexto.toLowerCase()}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            
            const formData = new FormData();
            formData.append('action', 'cambiar_estatus');
            formData.append('id_usuario', id_usuario);
            formData.append('estatus_actual', estatus_actual); 
        
            fetch('/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlertaGlobal(data.message, 'success');
                    callbackRecargar(); // Recarga la tabla
                } else {
                    mostrarAlertaGlobal(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlertaGlobal('Error de Conexión al intentar cambiar el estatus.', 'danger');
            });
        }
    }); // Fin SweetAlert2
};

/**
 * Lógica para resetear la contraseña de un usuario a '12345678'.
 */
window.ejecutarResetContrasena = function(id_usuario) {
    // Usamos el ID para obtener la FILA y luego los datos.
    const tabla = $('#tablaUsuarios').DataTable();
    const rowData = tabla.row($(`.btn-reset-contrasena[data-id="${id_usuario}"]`).closest('tr')).data();
    
    if (!rowData) {
        mostrarAlertaGlobal('Error: No se pudo obtener la información del usuario para resetear la contraseña.', 'danger');
        return;
    }

    const nombreUsuario = rowData.nom_usuario;
    
    // *** INCLUIMOS LA CONFIRMACIÓN AQUÍ USANDO SweetAlert2 ***
    Swal.fire({
        title: `¿Resetear Contraseña?`,
        text: `ADVERTENCIA: ¿Restablecer la contraseña de ${nombreUsuario} a '12345678'?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Resetear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            
            const formData = new FormData();
            formData.append('action', 'reset_contrasena');
            formData.append('id_usuario', id_usuario);

            fetch('/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlertaGlobal(data.message, 'success');
                } else {
                    mostrarAlertaGlobal(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlertaGlobal('Error de Conexión al intentar resetear la contraseña.', 'danger');
            });
        }
    }); // Fin SweetAlert2
};


// =================================================================
// 2. LÓGICA DE FORMULARIO (Requiere que el DOM esté cargado)
// =================================================================

// Esperamos a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {

    // Lógica para CREAR USUARIO
    const formCrearUsuario = document.getElementById('formCrearUsuario');

    if (formCrearUsuario) {
        formCrearUsuario.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(this);
            formData.append('action', 'crear');

            fetch('/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Usamos SweetAlert2 que ya está incluido
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
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
});