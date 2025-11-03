<?php
// En app/controllers/controller_usuarios.php
// Carga de configuración (Única ruta relativa: Sube un nivel)
require_once('../config.php'); 

// Inclusión de Modelos y Librerías (TODOS USAN ROOT_PATH)
require_once(ROOT_PATH . '/app/models/models_usuarios.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');

$R_LISTADO = '/admin/usuarios/usuarios_listado.php';
$R_CREAR = '/admin/usuarios/usuarios_crear.php';

// ----------------------------------------------------
// 1. GESTIÓN DE PETICIONES POST (Crear y Actualizar)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    
    $accion = $_POST['accion'];
    $usuarioModel = new UsuarioModel();
    $redirect_url_post = '../../admin/usuarios/usuarios_listado.php'; // URL base para redirecciones POST

    switch ($accion) {
        case 'crear':
            if (isset($_POST['usuario']) && isset($_POST['id_rol'])) {
                
                $datos_usuario = [
                    'usuario' => trim($_POST['usuario']),
                    'id_rol' => (int)$_POST['id_rol']
                ];

                if (empty($datos_usuario['usuario']) || $datos_usuario['id_rol'] <= 0) {
                    setAlert('warning', 'Todos los campos obligatorios deben ser llenados.', '../../admin/usuarios/usuarios_crear.php');
                    exit();
                }

                if ($usuarioModel->crearUsuario($datos_usuario)) {
                    setAlert('success', 'Usuario creado exitosamente. Contraseña por defecto: 12345678.', $R_LISTADO);
                } else {
                    setAlert('error', 'Error al crear el usuario. Intente con otro nombre de usuario.', $R_CREAR);
                }

            } else {
                setAlert('error', 'Datos incompletos para la acción CREAR.', $R_CREAR);
                header("Location: " . BASE_URL . $R_LISTADO);
                exit();
            }
            break;
            
        case 'actualizar':
            if (isset($_POST['id_usuario'], $_POST['id_rol'], $_POST['estatus'])) {
                
                $id_usuario = (int)$_POST['id_usuario'];
                $id_rol = (int)$_POST['id_rol'];
                $estatus = (int)$_POST['estatus'];

                if ($id_usuario <= 0 || $id_rol <= 0) {
                    setAlert('warning', 'Datos de actualización inválidos.', '../../admin/usuarios/usuarios_editar.php?id_usuario=' . $id_usuario);
                    exit();
                }

                if ($usuarioModel->actualizarUsuario($id_usuario, $id_rol, $estatus)) {
                    setAlert('success', "Usuario ID {$id_usuario} actualizado correctamente.", $redirect_url_post);
                } else {
                    setAlert('error', "Error al actualizar el usuario ID {$id_usuario}.", '../../admin/usuarios/usuarios_editar.php?id_usuario=' . $id_usuario);
                }

            } else {
                setAlert('error', 'Datos incompletos para la acción ACTUALIZAR.', $redirect_url_post);
            }
            break;

        default:
            setAlert('warning', 'Acción POST no válida.', $redirect_url_post);
            break;
    }
    
    // REDIRECCIÓN FINAL DEL BLOQUE POST
    header("Location: " . getAlertRedirect());
    exit();
} 
// ----------------------------------------------------
// 2. GESTIÓN DE PETICIONES GET (Resetear y Cambiar Estatus)
// ----------------------------------------------------
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {

    $accion = $_GET['accion'];
    $usuarioModel = new UsuarioModel();
    $redirect_url_get = '../../admin/usuarios/usuarios_listado.php'; 

    switch ($accion) {
        case 'reset_password':
            if (isset($_GET['id_usuario'])) {
                $id_usuario = (int)$_GET['id_usuario'];
                
                if ($usuarioModel->resetearPassword($id_usuario)) {
                    setAlert('success', "La contraseña del usuario ID {$id_usuario} ha sido reestablecida a 12345678.", $redirect_url_get);
                } else {
                    setAlert('error', "ERROR: No se pudo resetear la contraseña del usuario ID {$id_usuario}. Verifique que el usuario existe.", $redirect_url_get);
                }
            } else {
                setAlert('warning', 'ID de usuario no especificado.', $redirect_url_get);
            }
            break; 
            
        case 'cambiar_estatus':
            if (isset($_GET['id_usuario']) && isset($_GET['estatus'])) {
                $id_usuario = (int)$_GET['id_usuario'];
                $estatus = (int)$_GET['estatus'];
                
                $mensaje_estatus = ($estatus == 1) ? 'activado' : 'desactivado';
                
                if ($usuarioModel->cambiarEstatus($id_usuario, $estatus)) {
                    setAlert('success', "El usuario ID {$id_usuario} ha sido {$mensaje_estatus} correctamente.", $redirect_url_get);
                } else {
                    setAlert('error', "ERROR: No se pudo cambiar el estatus del usuario ID {$id_usuario}. Verifique que el usuario existe.", $redirect_url_get);
                }
            } else {
                setAlert('warning', 'Datos incompletos para cambiar el estatus.', $redirect_url_get);
            }
            break; 

        default:
            setAlert('warning', 'Acción GET no válida.', $redirect_url_get);
            break;
    }
    
    // REDIRECCIÓN FINAL DEL BLOQUE GET
    header("Location: " . getAlertRedirect());
    exit();
} 
// ----------------------------------------------------
// 3. ACCESO DIRECTO NO VÁLIDO
// ----------------------------------------------------
else {
    // Si se accede directamente al controlador sin un POST o GET válido
    setAlert('error', 'Acceso denegado. Petición no válida.', '../../admin/dashboard.php');
    header("Location: " . getAlertRedirect());
    exit();
}