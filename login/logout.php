<?php
include_once('../global/utils.php');

session_unset();
header('Location: ' . URL . '/login/index.php');
