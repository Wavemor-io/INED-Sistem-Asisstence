<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambiar si es necesario
$pass = "";
$db = "regis";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4"); // Soporte para caracteres especiales

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// Si no hay ID válido redirige
if ($id <= 0) {
    header("Location: listar_alumnos.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codest = $conn->real_escape_string($_POST['codest']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $cui = $conn->real_escape_string($_POST['cui']);
    $grado = $conn->real_escape_string($_POST['grado']);
    $seccion = $conn->real_escape_string($_POST['seccion']);
    $año = $conn->real_escape_string($_POST['año']); // Cambiado

    $sql = "UPDATE alumnos 
            SET codest='$codest', nombre='$nombre', cui='$cui', grado='$grado', seccion='$seccion', año='$año' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Alumno actualizado correctamente.";
    } else {
        $mensaje = "Error: " . $conn->error;
    }
}

// Obtener datos actuales del alumno
$result = $conn->query("SELECT * FROM alumnos WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: listar_alumnos.php");
    exit;
}
$alumno = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; }
        button { margin-top: 15px; padding: 10px 15px; }
        .mensaje { margin-top: 20px; font-weight: bold; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<h2>Editar Alumno</h2>
<p><a href="listar_alumnos.php">Volver a la lista</a></p>

<form method="post" action="">
    <label for="codest">Código Estudiante:</label>
    <input type="text" id="codest" name="codest" value="<?= htmlspecialchars($alumno['codest']) ?>" required>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>

    <label for="cui">CUI:</label>
    <input type="text" id="cui" name="cui" value="<?= htmlspecialchars($alumno['cui']) ?>" required>

    <label for="grado">Grado:</label>
    <input type="text" id="grado" name="grado" value="<?= htmlspecialchars($alumno['grado']) ?>" required>

    <label for="seccion">Sección:</label>
    <input type="text" id="seccion" name="seccion" value="<?= htmlspecialchars($alumno['seccion']) ?>" required>

    <label for="año">Año:</label> <!-- Cambiado -->
    <input type="number" id="año" name="año" min="2000" max="2100" value="<?= htmlspecialchars($alumno['año']) ?>" required>

    <button type="submit">Guardar Cambios</button>
</form>

<?php if ($mensaje): ?>
    <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
