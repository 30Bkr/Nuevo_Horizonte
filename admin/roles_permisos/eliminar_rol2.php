<?php
// admin/roles_permisos/eliminar_rol.php

require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/app/roles_permisos_model.php';

// Verificar que solo administradores puedan acceder
if (!isset($_SESSION['usuario_rol_nombre']) || $_SESSION['usuario_rol_nombre'] !== 'Administrador') {
  Notification::set("No tienes permisos para gestionar roles", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// Obtener ID
$id_rol = $_GET['id'] ?? 0;

// Validar
if ($id_rol <= 0) {
  Notification::set("ID de rol inválido", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// No permitir eliminar administrador (ID 1)
if ($id_rol == 1) {
  Notification::set("No se puede eliminar el rol Administrador", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// Procesar
$model = new RolesPermisosModel();

if ($model->eliminarRol($id_rol)) {
  Notification::set("Rol eliminado correctamente", "success");
} else {
  Notification::set("Error al eliminar el rol", "error");
}

// Redirigir
header('Location: ' . URL . '/admin/roles_permisos/index.php');
exit();
