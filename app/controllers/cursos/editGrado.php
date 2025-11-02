<?php

include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");
header('Content-Type: application/json; charset=utf-8');

$objCurso = new Cursos();
$esto = $_POST['id'];
// $esto1 = $_POST['seccion'];
$esto2 = $_POST['turno'];
// $esto3 = $_POST['observacion'];
// $esto4 = $_POST['grado'];
$esto5 = $_POST['capacidad'];

$objCurso->turno          = $_POST['turno'];
$objCurso->grado          = $_POST['grado'];
$objCurso->nom_seccion          = $_POST['nom_seccion'];
$objCurso->capacidad      = $_POST['capacidad'];
$objCurso->actualizarGS($_POST['id']);
// echo "$esto";
// echo "$esto1";
// echo "$esto2";
// echo "$esto3";
// echo "$esto4";
// echo "$esto5";
$json_data = [
  'success' => true,
  'message' => 'Conexión exitosa',
  'data' => ['id' => $esto, 'turno' => $esto2, 'capacidad' => $esto5]
];
echo json_encode($json_data);
