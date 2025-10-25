<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripcion.php");
include_once("/xampp/htdocs/final/global/utils.php");
$docente = new Inscripcion();


//Datos del estudiante:
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
$docente->relacion = $_POST['relacion'];

//Datos del representante: 
$docente->id_rol2 = $_POST['id_rolr'];

$docente->nombres2 = $_POST['nombresr'];
$docente->apellidos2 = $_POST['apellidosr'];
$docente->cedula2 = $_POST['cedular'];
$docente->correo2 = $_POST['correor'];
$docente->telefono2 = $_POST['telefonor'];
$docente->telefono_hab2 = $_POST['telefono_habr'];
$docente->estado2 = $_POST['estador'];
$docente->parroquia2 = $_POST['parroquiar'];
$docente->casa2 = $_POST['casar'];
$docente->calle2 = $_POST['caller'];
$docente->lugar_nac2 = $_POST['lugar_nacr'];
$docente->fecha_nac2 = $_POST['fecha_nacr'];
$docente->sexo2 = $_POST['sexor'];
$docente->nacionalidad2 = $_POST['nacionalidadr'];
$docente->ocupacion = $_POST['ocupacionr'];
$docente->lugar_trabajo = $_POST['lugar_trabajor'];

//DAtos del grado:

$docente->id_grado_seccion = $_POST['gradopo'];

$docente->inscribirPrimaria();


// header('Location:' . URL . '/admin/inscripciones/index.php');
