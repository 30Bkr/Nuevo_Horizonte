<?php
    include '../../layout/header_admin.php'; 
    
    // Verificación de Rol (solo Admin puede ver esto)
    if ($_SESSION['id_rol'] != 1) {
        echo '<section class="content"><div class="container-fluid"><div class="alert alert-danger">Acceso Denegado.</div></div></section>';
        include '../../layout/footer_admin.php';
        exit;
    }
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestión de Usuarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Listado de Usuarios</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            
            <div class="card-header">
                <h3 class="card-title">Listado de Usuarios</h3>
                <div class="card-tools">
                    <a href="usuarios_crear.php" class="btn btn-sm btn-primary" title="Crear Nuevo Usuario">
                        <i class="bi bi-person-fill-add"></i> Crear Usuario
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table id="tablaUsuarios" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estatus</th>
                            <th>Creación</th>
                            <th>Acciones</th> 
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
            </div>
        </div>
</section>

<?php 
    include '../../layout/footer_admin.php'; 
?>

<script>
    $(document).ready(function() {
        
        // 1. Inicialización de Tooltips (Se mantiene la velocidad de respuesta)
        $('[data-toggle="tooltip"]').tooltip({
            delay: { show: 100, hide: 100 }
        });
        
        // Helper para recargar DataTables (Se mantiene)
        function recargarTabla() {
            if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
                $('#tablaUsuarios').DataTable().ajax.reload(null, false);
            }
        }
        
        // 2. Configuración de DataTables (Volviendo al renderizado de acciones)
        var tabla = $('#tablaUsuarios').DataTable({ 
            "ajax": {
                "url": "<?php echo BASE_URL; ?>/app/controllers/usuarios/controller_usuario.php?action=listar",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "id_usuario" },
                { "data": "nom_usuario" },
                { "data": "usuario" },
                { "data": "nom_rol" },
                { "data": "estatus", "render": function(data) {
                    return data == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
                }},
                { "data": "creacion" },
                { 
                    "data": "id_usuario", 
                    "render": function(data, type, row) {
                        // El valor de 'row' contiene todos los datos de la fila (incluyendo el estatus)
                        const estatusTexto = row.estatus == 1 ? 'Inactivar' : 'Activar';
                        const estatusIcono = row.estatus == 1 ? 'fas fa-toggle-off' : 'fas fa-toggle-on';
                        
                        return `
                            <button class='btn btn-sm btn-info btn-actualizar mx-1' data-id='${data}' title='Editar Usuario' data-toggle='tooltip' data-placement='top'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='btn btn-sm btn-secondary btn-cambio-estatus mx-1' data-id='${data}' data-estatus='${row.estatus}' title='${estatusTexto}' data-toggle='tooltip' data-placement='top'>
                                <i class='${estatusIcono}'></i>
                            </button>
                            <button class='btn btn-sm btn-warning btn-reset-contrasena mx-1' data-id='${data}' title='Reset Contraseña' data-toggle='tooltip' data-placement='top'>
                                <i class='fas fa-key'></i>
                            </button>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ],
            // IMPORTANTE: Eliminamos la propiedad "select"
        });
        
        // 3. Lógica para el botón ACTUALIZAR (Abre el modal)
        $('#tablaUsuarios tbody').on('click', '.btn-actualizar', function () {
            var id_usuario = $(this).data('id');
            // Llama a la función global para cargar datos y abrir el modal
            window.cargarDatosUsuario(id_usuario); 
        });

        // 4. Lógica para el botón CAMBIO ESTATUS
        $('#tablaUsuarios tbody').on('click', '.btn-cambio-estatus', function () {
            var id_usuario = $(this).data('id');
            var estatus_actual = $(this).data('estatus');
            
            // Llama a la función global para ejecutar el cambio de estatus
            // Le pasamos la función de recarga como callback
            window.ejecutarCambioEstatus(id_usuario, estatus_actual, recargarTabla); 
        });
        
        // 5. Lógica para el botón RESET CONTRASEÑA
        $('#tablaUsuarios tbody').on('click', '.btn-reset-contrasena', function () {
            var id_usuario = $(this).data('id');
            
            // Llama a la función global para ejecutar el reset
            window.ejecutarResetContrasena(id_usuario); 
        });
    });
    
    // NOTA: Asegúrate de que tu helper de recargarTabla sigue definido
    function recargarTabla() {
        if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
            $('#tablaUsuarios').DataTable().ajax.reload(null, false);
        }
    }
</script>