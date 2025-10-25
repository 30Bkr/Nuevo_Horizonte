<?php

include_once("/xampp/htdocs/final/app/personas.php");
include_once("/xampp/htdocs/final/global/utils.php");
$docente = new Persona();

$docente->id_rol = $_POST['id_rol'];
$docente->nombres = $_POST['nombres'];
$docente->apellidos = $_POST['apellidos'];
$docente->cedula = $_POST['cedula'];
$docente->correo = $_POST['correo'];
$docente->telefono = $_POST['telefono'];
$docente->telefono_hab = $_POST['telefono_hab'];
$docente->especialidad = $_POST['especialidad'];
$docente->estado = $_POST['estado'];
$docente->parroquia = $_POST['parroquia'];
$docente->casa = $_POST['casa'];
$docente->calle = $_POST['calle'];
$docente->lugar_nac = $_POST['lugar_nac'];
$docente->fecha_nac = $_POST['fecha_nac'];
$docente->sexo = $_POST['sexo'];
$docente->nacionalidad = $_POST['nacionalidad'];

$docente->crearDocente();
header('Location:' . URL . '/admin/docentes/index.php');
