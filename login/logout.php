<?php
include_once('../global/utils.php');
$_SESSION['mensaje'] = "";

session_destroy();
header('Location: ' . URL . '/login/index.php');
