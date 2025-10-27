<?php
include_once("/xampp/htdocs/final/app/controllers/cursos/cursosA.php");
include_once("/xampp/htdocs/final/global/utils.php");

$objCurso = new Cursos2();
$objCurso->año         = $_POST['año'];
$objCurso->nom_seccion    = $_POST['seccion'];
$objCurso->turno          = $_POST['turno'];
$objCurso->capacidad      = $_POST['capacidad'];
$objCurso->crearAño2();

// header('Location:' . URL . '/admin/cursos/index.php');
