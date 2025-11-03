<?php
require_once('../config.php');
require_once(ROOT_PATH . '/app/models/models_roles.php');
require_once(ROOT_PATH . '/app/libs/alerts.php');

$rolModel = new RolModel();

// GESTIÓN DE PETICIONES POST (Crear y Actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    
    $accion = $_POST['accion'];
    $nom_rol = isset($_POST['nom_rol']) ? trim($_POST['nom_rol']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
    
    $redirect_url_listado = '/admin/roles/roles_listado.php';
    $redirect_url_formulario = ($id_rol > 0) ? '../../admin/roles/roles_formulario.php?id_rol=' . $id_rol : '../../admin/roles/roles_formulario.php';

    switch ($accion) {
        case 'crear':
            if (!empty($nom_rol)) {
                if ($rolModel->crearRol($nom_rol, $descripcion)) {
                    setAlert('success', "El rol ID {$id_rol} ha sido {$mensaje_estatus} correctamente.", $redirect_url_listado);
                } else {
                    setAlert('error', "Error al crear el rol. El nombre podría ya existir.", $redirect_url_formulario);
                }
            } else {
                setAlert('warning', 'El nombre del rol es obligatorio.', $redirect_url_formulario);
            }
            break;

        case 'actualizar':
            if ($id_rol > 0 && !empty($nom_rol)) {
                if ($rolModel->actualizarRol($id_rol, $nom_rol, $descripcion)) {
                    setAlert('success', "Rol ID {$id_rol} actualizado correctamente.", $redirect_url_listado);
                } else {
                    setAlert('error', "Error al actualizar el rol ID {$id_rol}. Posiblemente no hubo cambios.", $redirect_url_formulario);
                }
            } else {
                setAlert('warning', 'Datos incompletos para la actualización.', $redirect_url_listado);
            }
            break;
            
        default:
            setAlert('warning', 'Acción POST no válida.', $redirect_url_listado);
            break;
    }
    
    header("Location: " . BASE_URL . getAlertRedirect());
exit();
} 
// GESTIÓN DE PETICIONES GET (Eliminar)
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {

    $accion = $_GET['accion'];
    $redirect_url_listado = '../../admin/roles/roles_listado.php';
    
    switch ($accion) {
        case 'cambiar_estatus':
            if (isset($_GET['id_rol']) && isset($_GET['estatus'])) {
                $id_rol = (int)$_GET['id_rol'];
                $estatus = (int)$_GET['estatus']; 
                
                // Mensaje para la alerta
                $mensaje_estatus = ($estatus == 1) ? 'activado' : 'desactivado';
                
                if ($rolModel->cambiarEstatusRol($id_rol, $estatus)) {
                    setAlert('success', "El rol ID {$id_rol} ha sido {$mensaje_estatus} correctamente.", $redirect_url_listado);
                } else {
                    setAlert('error', "ERROR: No se pudo cambiar el estatus del rol ID {$id_rol}.", $redirect_url_listado);
                }
            } else {
                setAlert('warning', 'Datos incompletos para cambiar el estatus del rol.', $redirect_url_listado);
            }
            break;

        /* case 'eliminar':
            if (isset($_GET['id_rol'])) {
                $id_rol = (int)$_GET['id_rol'];
                
                if ($rolModel->eliminarRol($id_rol)) {
                    setAlert('success', "El rol ID {$id_rol} ha sido eliminado.", $redirect_url_listado);
                } else {
                    setAlert('error', "ERROR: No se pudo eliminar el rol ID {$id_rol}. Podría estar asociado a usuarios (Clave Foránea).", $redirect_url_listado);
                }
            } else {
                setAlert('warning', 'ID de rol no especificado.', $redirect_url_listado);
            }
            break; */

        default:
            setAlert('warning', 'Acción GET no válida.', $redirect_url_listado);
            break;
    }
    
    header("Location: " . getAlertRedirect());
    exit();
} 
// ACCESO DIRECTO NO VÁLIDO
else {
    setAlert('error', 'Acceso denegado.', '../../admin/dashboard.php');
    header("Location: " . getAlertRedirect());
    exit();
}