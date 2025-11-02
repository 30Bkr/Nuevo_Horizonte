<?php
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");

$objCurso = new Cursos();

$objCurso->grado          = $_POST['grado'];
$objCurso->nom_seccion    = $_POST['seccion'];
$objCurso->turno          = $_POST['turno'];
$objCurso->capacidad      = $_POST['capacidad'];
$objCurso->crearGrado();



header('Location:' . URL . '/admin/cursos/index.php');
