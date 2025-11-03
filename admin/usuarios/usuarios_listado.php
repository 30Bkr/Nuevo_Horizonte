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
                    <a href="usuarios_crear.php" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i> Nuevo Usuario
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
        $('#tablaUsuarios').DataTable({
            "ajax": {
                // Usamos la URL absoluta del controlador
                "url": "/nuevo_horizonte/app/controllers/usuarios/controller_usuario.php?action=listar",
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
                    "data": "id_usuario", // Usamos el ID para generar el botón
                    "render": function(data) {
                        // Aquí irán los botones de Actualizar/Editar
                        return `<button class'btn btn-sm btn-info btn-actualizar' data-id='${data}'>Actualizar</button>`;
                    },
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