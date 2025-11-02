<?php

include_once("/xampp/htdocs/final/app/alumnos.php");
include_once("/xampp/htdocs/final/global/utils.php");


$docente = new Alumnos();
$id = $_POST['id_persona'];
echo $_POST['condiciones'];
$docente->id_rol = $_POST['id_rol'];
$docente->nombres = $_POST['nombres'];
$docente->apellidos = $_POST['apellidos'];
$docente->cedula = $_POST['cedula'];
$docente->correo = $_POST['correo'];
$docente->telefono = $_POST['telefono'];
$docente->telefono_hab = $_POST['telefono_hab'];
$docente->estado = $_POST['estado'];
$docente->parroquia = $_POST['parroquia'];
$docente->casa = $_POST['casa'];
$docente->calle = $_POST['calle'];
$docente->lugar_nac = $_POST['lugar_nac'];
$docente->fecha_nac = $_POST['fecha_nac'];
$docente->sexo = $_POST['sexo'];
$docente->nacionalidad = $_POST['nacionalidad'];
$docente->alergias = $_POST['alergias'];
$docente->condiciones = $_POST['condiciones'];

$docente->actualizar($id);
header('Location:' . URL . '/admin/inscripciones/registro.php');
