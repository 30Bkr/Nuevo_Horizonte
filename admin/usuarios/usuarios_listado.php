<?php 
// 1. Cargar la configuración global (que define ROOT_PATH, conexión, etc.)
// Desde /admin/usuarios/, se sube dos niveles (../../) para llegar a la raíz.
// Luego se entra a /app/config.php
require_once('../../app/config.php'); 

// 2. Incluir el Header
include('../../layout/header_admin.php'); 

// 3. Incluir el Modelo de Usuarios
require_once(ROOT_PATH . '/app/models/models_usuarios.php');

// Inicializar el modelo y obtener la lista de usuarios
$usuarioModel = new UsuarioModel();
$usuarios = $usuarioModel->getListadoUsuarios(); 
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inyecta el título en el content-header
        document.querySelector('.content-header .col-sm-6').innerHTML = '<h1><i class="fas fa-list-ul"></i> Listado de Usuarios</h1>';
    });
</script>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                
                <div style="float: left; margin-right: 15px;">
                    
                    <a href="usuarios_crear.php" class="btn btn-sm btn-success" title="Crear Nuevo Usuario">
                        <i class="fas fa-user-plus"></i>
                    </a>

                    <button id="btnEditarUsuario" class="btn btn-info btn-sm" title="Editar Usuario">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button id="btnCambiarEstatus" class="btn btn-danger btn-sm" title="Cambiar Estatus">
                        <i class="fas fa-toggle-off"></i> 
                    </button>
                    
                    <button id="btnResetPassword" class="btn btn-warning btn-sm" title="Resetear Contraseña">
                        <i class="fas fa-undo-alt"></i> 
                    </button>
                    
                </div>
                
                <h3 class="card-title" style="display: inline-block;"></h3>
                
            </div>
            <div class="card-body">
                <form id="formAccionesUsuarios">
                    <table id="tablaUsuarios" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px;"></th> 
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr data-id="<?php echo htmlspecialchars($usuario['id_usuario']); ?>" data-status="<?php echo htmlspecialchars($usuario['estatus']); ?>">
                                        
                                        <td>
                                            <input type="checkbox" name="usuario_id[]" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
                                        </td>
                                        
                                        <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['nom_rol']); ?></td>
                                        <td>
                                            <?php if ($usuario['estatus'] == 1): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>
<?php 
// 4. Incluir el Footer
include('../../layout/footer_admin.php'); 
?>

<script>
    $(document).ready(function() {
        // Inicializar DataTables
        var tabla = $('#tablaUsuarios').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Deshabilita la ordenación en la columna de checkbox
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        // Variables de botones
        var $btnEditarUsuario = $('#btnEditarUsuario');
        var $btnCambiarEstatus = $('#btnCambiarEstatus');
        var $btnResetPassword = $('#btnResetPassword');

        // Función para actualizar el estado de los botones
        function actualizarEstadoBotones() {
            var selectedIds = [];
            
            // Recorrer checkboxes seleccionados
            tabla.$('input[type="checkbox"]').each(function() {
                if ($(this).prop('checked')) {
                    selectedIds.push($(this).val());
                }
            });

            // Lógica de habilitación/deshabilitación
            if (selectedIds.length === 1) {
                var selectedRow = $('input[value="' + selectedIds[0] + '"]').closest('tr');
                var currentStatus = selectedRow.data('status');
                
                // Habilitar todos los botones de acción individual
                $btnEditarUsuario.prop('true', false);
                $btnCambiarEstatus.prop('true', false);
                $btnResetPassword.prop('true', false);

                // Cambiar el icono y el color del botón de estatus (para Desactivar/Activar)
                var $iconCambiarEstatus = $btnCambiarEstatus.find('i');
                
                if (currentStatus == 1) {
                    // Si está ACTIVO (1), mostrar botón ROJO para DESACTIVAR (fa-user-slash)
                    $btnCambiarEstatus.removeClass('btn-success').addClass('btn-danger');
                    $iconCambiarEstatus.removeClass('fa-user-check fa-toggle-off').addClass('fa-user-slash').attr('title', 'Desactivar Usuario');
                } else {
                    // Si está INACTIVO (0), mostrar botón VERDE para ACTIVAR (fa-user-check)
                    $btnCambiarEstatus.removeClass('btn-danger').addClass('btn-success');
                    $iconCambiarEstatus.removeClass('fa-user-slash fa-toggle-off').addClass('fa-user-check').attr('title', 'Activar Usuario');
                }
            } else {
                // Deshabilitar todos
                $btnEditarUsuario.prop('disable', true);
                $btnCambiarEstatus.prop('disable', true);
                $btnResetPassword.prop('disable', true);

                // Restaurar color y icono de estatus por defecto
                $btnCambiarEstatus.removeClass('btn-success').addClass('btn-danger');
                $btnCambiarEstatus.find('i').removeClass('fa-user-check fa-user-slash').addClass('fa-toggle-off').attr('title', 'Cambiar Estatus');
            }
        }
        
        // Listener para los cambios en cualquier checkbox
        $('#tablaUsuarios').on('change', 'input[type="checkbox"]', function() {
            actualizarEstadoBotones();
        });
        
        // --- Lógica de Acciones ---

        // 1. Botón Editar Rol o Estatus (Redirige a un formulario futuro)
        $btnEditarUsuario.on('click', function() {
            var userId = tabla.$('input[type="checkbox"]:checked').val();
            if (userId) {
                // A futuro: Creamos la vista usuarios_editar.php
                window.location.href = 'usuarios_editar.php?id_usuario=' + userId; 
            }
        });

        // 2. Botón Cambiar Estatus
        $btnCambiarEstatus.on('click', function() {
            var userId = tabla.$('input[type="checkbox"]:checked').val();
            var selectedRow = $('input[value="' + userId + '"]').closest('tr');
            var status = selectedRow.data('status');
            
            var newStatus = (status == 1) ? 0 : 1;
            var actionText = (newStatus == 0) ? 'desactivado' : 'activado';

            if (userId) {
                Swal.fire({
                    title: 'Confirmar Estatus',
                    text: "¿Desea cambiar el estatus del usuario ID " + userId + " a " + actionText + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../app/controllers/controller_usuarios.php?accion=cambiar_estatus&id_usuario=' + userId + '&estatus=' + newStatus;
                    }
                });
            }
        });
        
        // 3. Botón Resetear Contraseña
        $btnResetPassword.on('click', function() {
            var userId = tabla.$('input[type="checkbox"]:checked').val();
            if (userId) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "La contraseña del usuario ID " + userId + " será reestablecida a 12345678.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, resetear!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../app/controllers/controller_usuarios.php?accion=reset_password&id_usuario=' + userId;
                    }
                });
            }
        });

        // Asegurar que el estado inicial de los botones sea correcto
        actualizarEstadoBotones();
    });
</script>