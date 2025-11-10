<?php
include_once('../app/users.php');
include_once('../global/utils.php');

$email = $_POST['email'];
$password = $_POST['password'];

$contrasena = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
$email = $_POST["email"];
$user = new Usuarios;
$listaPersona = $user->consultar($email);
$info = $user->info($listaPersona[0]->nombre_usuario);

echo "<pre>";
var_dump('primer: ', $user->consultar($email));
// var_dump($listaPersona[0]->contrasena);
// echo (password_verify($listaPersona[0]->contrasena, $password));
// var_dump($listaPersona[0]->id_persona);
var_dump($info);
// var_dump($listaPersona);


echo "</pre>";
echo "<pre>";
// var_dump($listaPersona);
echo "</pre>";
if (password_verify($listaPersona[0]->contrasena, $contrasena) && ($listaPersona[0]->estatus === 1)) {
  echo "aca2 si es la misma";
  session_start();
  header('Location:' . URL . '/admin/index.php');
  $_SESSION['icono'] = "success";
  $_SESSION['sesion_email'] = $email;
  echo $_SESSION['sesion_email'];
} else {
  echo 'aca3 no es la misma';
  session_start();
  $_SESSION['mensaje'] = "Los datos introducidos son incorrectos";
  header('Location: ' . URL . '/login/index.php');
}
// echo "<br>Usuario: $email<br>Contrasena: $contrasena";
