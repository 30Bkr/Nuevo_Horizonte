<?php
include_once("/xampp/htdocs/final/app/persona.php");

$objPersona = new Persona();

$ci = $_POST['cedula'];

$objPersona->consultar($ci);

header('Content-Type: application/json');
