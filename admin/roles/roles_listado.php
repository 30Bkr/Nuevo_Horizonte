<?php 
// 1. Configuración global (usamos ruta relativa, la más cercana)
require_once('../../app/config.php'); 

// 2. INCLUIR LAS LIBRERÍAS USANDO LA RUTA ABSOLUTA (ROOT_PATH)
// ASEGÚRATE DE QUE ESTAS RUTAS COINCIDAN CON LA UBICACIÓN REAL DE TUS ARCHIVOS
require_once(ROOT_PATH . '/app/controllers/alerts.php'); 
require_once(ROOT_PATH . '/app/libs/auth.php'); 

// 3. Incluir el Modelo de Roles
require_once(ROOT_PATH . '/app/models/models_roles.php');

// 4. PROTEGER LA PÁGINA
//protegerPagina('admin/roles/roles_listado.php'); 

// 5. Header
include('../../layout/header_admin.php');

$rolModel = new RolModel();
$roles = $rolModel->getListadoRoles();
$contador = 1;
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('.content-header .col-sm-6').innerHTML = '<h1><i class="fas fa-users-cog"></i> Listado de Roles</h1>';
    });
</script>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Roles Registrados</h3>
            </div>
            
            <div class="card-body">
                <div class="d-flex mb-3">
                    <a href="roles_formulario.php" class="btn btn-success mr-2" id="btnCrearRol">
                        <i class="fas fa-plus-circle"></i> Crear Nuevo Rol
                    </a>
                </div>
                
                <table id="tablaRoles" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>ID</th>
                            <th>Nombre del Rol</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $rol): 
                            $es_activo = $rol['estatus'] == 1;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rol['descripcion']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="roles_formulario.php?id_rol=<?php echo htmlspecialchars($rol['id_rol']); ?>" class="btn btn-info btn-sm" title="Editar Rol">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button class="btn btn-<?php echo $es_activo ? 'danger' : 'success'; ?> btn-sm btn-cambiar-estatus" 
                                            data-id="<?php echo htmlspecialchars($rol['id_rol']); ?>" 
                                            data-nombre="<?php echo htmlspecialchars($rol['nom_rol']); ?>"
                                            data-estatus-actual="<?php echo htmlspecialchars($rol['estatus']); ?>"
                                            title="<?php echo $es_activo ? 'Desactivar Rol' : 'Activar Rol'; ?>">
                                        <i class="fas fa-<?php echo $es_activo ? 'ban' : 'check'; ?>"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include('../../layout/footer_admin.php'); 
?>

<script>
    $(function () {
        // Inicializar DataTables
        $("#tablaRoles").DataTable({
            // ... (Configuración de DataTables) ...
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        }).buttons().container().appendTo('#tablaRoles_wrapper .col-md-6:eq(0)');

        // En admin/roles/roles_listado.php (dentro de <script>)

// Lógica de SweetAlert para el botón de cambiar estatus
$('.btn-cambiar-estatus').on('click', function(e) {
    e.preventDefault();
    const id_rol = $(this).data('id');
    const nom_rol = $(this).data('nombre');
    const estatus_actual = $(this).data('estatus-actual');
    
    // Determinar el nuevo estatus y el mensaje
    const nuevo_estatus = estatus_actual == 1 ? 0 : 1;
    const accion_texto = nuevo_estatus == 1 ? 'activar' : 'desactivar';
    const verbo_accion = nuevo_estatus == 1 ? 'Activar' : 'Desactivar';
    
    Swal.fire({
        title: `¿Desea ${accion_texto} el rol?`,
        text: `El rol "${nom_rol}" será ${accion_texto} del sistema.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${verbo_accion}!`
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige al controlador para la acción de cambiar estatus
            window.location.href = `../../app/controllers/controller_roles.php?accion=cambiar_estatus&id_rol=${id_rol}&estatus=${nuevo_estatus}`;
        }
    });
});
});
</script>