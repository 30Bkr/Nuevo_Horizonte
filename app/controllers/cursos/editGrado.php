<?php

include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");


$objCurso = new Cursos();
// $esto = $_POST['id_persona'];
// $esto1 = $_POST['seccion'];
// $esto2 = $_POST['turno'];
// $esto3 = $_POST['observacion'];
// $esto4 = $_POST['grado'];
// $esto5 = $_POST['descripcion'];

$objCurso->nom_seccion    = $_POST['seccion'];
$objCurso->turno          = $_POST['turno'];
$objCurso->observacion    = $_POST['observacion'];
$objCurso->grado        = $_POST['grado'];
$objCurso->descripcion    = $_POST['descripcion'];
$objCurso->capacidad      = $_POST['capacidad'];
$objCurso->actualizarGS($_POST['id_persona']);
// echo "$esto";
// echo "$esto1";
// echo "$esto2";
// echo "$esto3";
// echo "$esto4";
// echo "$esto5";
header('Location:' . URL . '/admin/cursos/index.php');
