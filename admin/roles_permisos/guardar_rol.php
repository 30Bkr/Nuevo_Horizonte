<?php
// admin/roles_permisos/guardar_rol.php

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
$nom_rol = trim($_POST['nom_rol'] ?? '');

// Validar
if (empty($nom_rol)) {
  Notification::set("El nombre del rol es requerido", "error");
  header('Location: ' . URL . '/admin/roles_permisos/index.php');
  exit();
}

// Procesar
$model = new RolesPermisosModel();

if ($id_rol > 0) {
  // Actualizar rol existente
  if ($model->actualizarRol($id_rol, $nom_rol)) {
    Notification::set("Rol actualizado correctamente", "success");
  } else {
    Notification::set("Error al actualizar el rol", "error");
  }
} else {
  // Crear nuevo rol
  $nuevoId = $model->crearRol($nom_rol);
  if ($nuevoId) {
    Notification::set("Rol creado correctamente", "success");
    // Redirigir a permisos del nuevo rol
    header('Location: ' . URL . '/admin/roles_permisos/index.php?accion=permisos&id=' . $nuevoId);
    exit();
  } else {
    Notification::set("Error al crear el rol", "error");
  }
}

// Redirigir
header('Location: ' . URL . '/admin/roles_permisos/index.php');
exit();
