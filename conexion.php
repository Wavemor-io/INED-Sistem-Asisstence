<?php
$conexion = new mysqli("localhost", "root", "", "regis");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
