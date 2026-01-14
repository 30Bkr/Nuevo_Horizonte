<?php
// admin/roles_permisos/guardar_permisos.php

require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/app/roles_permisos_model.php';

// Verificar que solo administradores puedan acceder
if (!isset($_SESSION['usuario_rol_nombre']) || $_SESSION['usuario_rol_nombre'] !== 'Administrador') {
  Notification::set("No tienes permisos para gestionar roles", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// Obtener datos
$id_rol = $_POST['id_rol'] ?? 0;
$permisos = $_POST['permisos'] ?? [];

// Validar
if ($id_rol <= 0) {
  Notification::set("ID de rol inválido", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// No permitir modificar permisos del administrador (ID 1)
if ($id_rol == 1) {
  Notification::set("No se pueden modificar los permisos del rol Administrador", "warning");
  header('Location: ' . URL . '/admin/roles_permisos/index.php?accion=permisos&id=' . $id_rol);
  exit();
}

// Procesar
$model = new RolesPermisosModel();

// Convertir permisos a enteros
$permisosSeleccionados = array_map('intval', $permisos);

// Actualizar permisos
if ($model->updatePermisosRol($id_rol, $permisosSeleccionados)) {
  Notification::set("Permisos actualizados correctamente", "success");
} else {
  Notification::set("Error al actualizar permisos", "error");
}

// Redirigir
header('Location: ' . URL . '/admin/roles_permisos/index.php?accion=permisos&id=' . $id_rol);
exit();
