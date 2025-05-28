<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambiar si es necesario
$pass = "";
$db = "regis";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// Si no hay ID válido redirige
if ($id <= 0) {
    header("Location: index1.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codest = $conn->real_escape_string($_POST['codest']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $cui = $conn->real_escape_string($_POST['cui']);
    $grado = $conn->real_escape_string($_POST['grado']);
    $seccion = $conn->real_escape_string($_POST['seccion']);
    $año = $conn->real_escape_string($_POST['año']);

    $sql = "UPDATE alumnos SET codest='$codest', nombre='$nombre', cui='$cui', grado='$grado', seccion='$seccion', año='$año' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Alumno actualizado correctamente.";
    } else {
        $mensaje = "Error: " . $conn->error;
    }
}

// Obtener datos actuales del alumno
$result = $conn->query("SELECT * FROM alumnos WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: index1.php");
    exit;
}
$alumno = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 430px;
            margin: 40px auto 0 auto;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 32px 32px;
        }
        h2 {
            text-align: center;
            color: #2d3a4b;
            margin-bottom: 18px;
            font-weight: 700;
        }
        form label {
            display: block;
            margin-top: 16px;
            color: #34495e;
            font-weight: 500;
        }
        form input[type=text],
        form input[type=number] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            background: #f9fafb;
            transition: border-color 0.2s;
        }
        form input:focus {
            border-color: #1976d2;
            outline: none;
            background: #fff;
        }
        button[type=submit] {
            width: 100%;
            background: linear-gradient(90deg, #1976d2 0%, #42a5f5 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 0;
            font-size: 16px;
            font-weight: 700;
            margin-top: 28px;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type=submit]:hover {
            background: linear-gradient(90deg, #1565c0 0%, #1e88e5 100%);
        }
        .mensaje {
            margin-top: 22px;
            font-weight: bold;
            text-align: center;
            color: #388e3c;
            background: #e8f5e9;
            border-radius: 6px;
            padding: 10px 0;
        }
        .mensaje.error {
            color: #c62828;
            background: #ffebee;
        }
        .volver {
            display: inline-block;
            margin-bottom: 18px;
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .volver:hover {
            color: #0d47a1;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Alumno</h2>
    <a class="volver" href="index1.php">&larr; Volver a la lista</a>
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

        <label for="año">Año:</label>
        <input type="number" id="año" name="año" min="2000" max="2100" value="<?= $alumno['año'] ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
    <?php if ($mensaje): ?>
        <div class="mensaje<?= strpos($mensaje, 'Error') !== false ? ' error' : '' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php $conn->close(); ?>