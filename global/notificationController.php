<?php

require_once '/xampp/htdocs/final/global/notifications.php';

$mensaje = $_POST['mensaje'];
$tipo = $_POST['tipo'];

Notification::set($mensaje, $tipo);


Notification::show();
