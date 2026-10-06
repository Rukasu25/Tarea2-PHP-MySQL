<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "SALUD_USM";
$conn = new mysqli($servidor, $usuario, $password, $base_datos);
if ($conn->connect_error) {
    die("Error al conectar x-x : " . $conn->connect_error);
}
?>