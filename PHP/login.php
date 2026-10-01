<?php
session_start();
require 'conexion.php';
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut= trim($_POST['rut']);
    $password = $_POST['password'];

    //Nota de Rukasu: aqui quedé, sigue por mi, voy a almorzar xd

}