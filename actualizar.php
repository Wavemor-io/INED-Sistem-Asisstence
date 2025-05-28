<?php
include 'conexion.php';

$id = $_POST['id'];
$codest = $_POST['codest'];
$nombre = $_POST['nombre'];
$cui = $_POST['cui'];
$grado = $_POST['grado'];
$seccion = $_POST['seccion'];
$año = $_POST['año'];

$sql = "UPDATE alumnos SET 
            codest = ?, 
            nombre = ?, 
            cui = ?, 
            grado = ?, 
            seccion = ?, 
            año = ?
        WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssi", $codest, $nombre, $cui, $grado, $seccion, $año, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Alumno actualizado correctamente.<br><a href='index.php'>Volver</a>";
} else {
    echo "No se pudo actualizar el alumno o no hubo cambios.<br><a href='index.php'>Volver</a>";
}

$stmt->close();
$conexion->close();
