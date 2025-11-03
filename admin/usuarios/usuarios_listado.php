<?php
    // Incluimos el NUEVO header de admin
    // La lógica de sesión ya está DENTRO de header_admin.php
    include '../../layout/header_admin.php'; 
    
    // Verificación específica de Rol para esta página
    if ($_SESSION['id_rol'] != 1) { // 1 = Admin
        // Si no es admin, podemos mostrar un error o redirigir
        echo '<div class="content-wrapper"><section class="content"><div class="container-fluid"><div class="alert alert-danger">Acceso Denegado. No tienes permisos para ver esta página.</div></div></section></div>';
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
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                        <i class="fa fa-plus me-1"></i> Nuevo Usuario
                    </button>
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
    // Incluimos el NUEVO footer de admin
    include '../../layout/footer_admin.php'; 
?>

<script>
    $(document).ready(function() {
        $('#tablaUsuarios').DataTable({
            // "ajax": "../../app/controllers/usuarios/controller_usuario.php?action=listar", (Ruta corregida)
            "ajax": {
                // Usamos la URL absoluta para evitar problemas de rutas
                "url": "/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php?action=listar",
                "dataSrc": "data" // Aseguramos que lea el array 'data'
            },
            "columns": [
                { "data": "id_usuario" },
                { "data": "nom_usuario" },
                { "data": "usuario" },
                { "data": "nom_rol" },
                { "data": "estatus", "render": function(data) {
                    return data == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                }},
                { "data": "creacion" },
                // { "data": "actualizacion" },
                { 
                    "data": null, 
                    "defaultContent": "<button class='btn btn-sm btn-info btn-actualizar'>Actualizar</button>",
                    "orderable": false
                }
            ],
            "language": { 
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" 
            },
            "responsive": true
        });
    });
</script>