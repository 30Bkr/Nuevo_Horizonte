<?php

include_once("/xampp/htdocs/final/app/roles/roles.php");
include_once("/xampp/htdocs/final/global/utils.php");

$rolesS = new Roles();

$rolesS->nombre_rol = $_POST['nombre_rol'];
$rolesS->descripcion = $_POST['descripcion'];

$rolesS->createRol();
header('Location:' . URL . '/admin/roles/index.php');
