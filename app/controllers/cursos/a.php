<?php
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");
header('Content-Type: application/json');

$objCurso = new Cursos();

$grado2 = (int) $_POST['grado'];

$objCurso->grado          = $grado2;
$objCurso->nom_seccion    = $_POST['nom_seccion'];
$objCurso->turno          = $_POST['turno'];
$objCurso->capacidad      = $_POST['capacidad'];
$objCurso->crearGrado();


$json_data = [
  'success' => true,
  'message' => 'Conexion exitosa',
  'data' => ['grado' => $_POST['grado'], 'turno' => $_POST['turno'], 'capacidad' => $_POST['capacidad'], 'nom_seccion' => $_POST['nom_seccion']]
];
echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
