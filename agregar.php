<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "regis";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codest = $conn->real_escape_string($_POST['codest']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $cui = $conn->real_escape_string($_POST['cui']);
    $grado = $conn->real_escape_string($_POST['grado']);
    $seccion = $conn->real_escape_string($_POST['seccion']);
    $año = $conn->real_escape_string($_POST['año']);

    $sql = "INSERT INTO alumnos (codest, nombre, cui, grado, seccion, año) 
            VALUES ('$codest', '$nombre', '$cui', '$grado', '$seccion', '$año')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Alumno agregado correctamente.";
    } else {
        $mensaje = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Alumno</title>
    <!-- Bootstrap CSS + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 100px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        #sidebarMenu {
            width: 250px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1040;
            background-color: rgba(255, 255, 255, 0.6); /* fondo claro translúcido */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        }

        #overlay {
            display: none;
            z-index: 1030;
        }

        .nav-link {
            font-size: 1rem;
            padding: 0.5rem 0;
        }

        .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0.25rem;
        }

        .nav-link i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<!-- Botón Menú -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
  <button class="btn btn-primary" id="toggleSidebar">&#9776;</button>
</div>

<!-- Sidebar claro translúcido -->
<div id="sidebarMenu" class="position-fixed top-0 end-0 vh-100 p-4">
  <h5 class="mb-4">Menú</h5>
  <ul class="nav flex-column text-dark">
    <li class="nav-item"><a class="nav-link text-dark" href="regisasis.php"><i class="bi bi-journal-text"></i>Ver registros</a></li>
    <li class="nav-item"><a class="nav-link text-dark" href="records.php"><i class="bi bi-search"></i>Buscar asistencia</a></li>
    <li class="nav-item"><a class="nav-link text-dark" href="qr.php"><i class="bi bi-qr-code"></i>Generar QR</a></li>
    <li class="nav-item"><a class="nav-link text-dark" href="index1.php"><i class="bi bi-pencil-square"></i>Editar Estudiantes</a></li>
    <li class="nav-item"><a class="nav-link text-dark" href="agregar.php"><i class="bi bi-person-plus"></i>Agregar Estudiantes</a></li>
    <li class="nav-item"><a class="nav-link text-danger" href="?logout=1"><i class="bi bi-box-arrow-right"></i>Cerrar sesión</a></li>
  </ul>
</div>

<!-- Fondo oscuro -->
<div id="overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>

<!-- Formulario -->
<div class="container">
    <h2>Agregar Alumno</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="codest" class="form-label">Código Estudiante:</label>
            <input type="text" id="codest" name="codest" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cui" class="form-label">CUI:</label>
            <input type="text" id="cui" name="cui" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="grado" class="form-label">Grado:</label>
            <input type="text" id="grado" name="grado" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="seccion" class="form-label">Sección:</label>
            <input type="text" id="seccion" name="seccion" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="año" class="form-label">Año:</label>
            <input type="number" id="año" name="año" class="form-control" min="2000" max="2100" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Alumno</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-info mensaje mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggleSidebar = document.getElementById('toggleSidebar');
  const sidebarMenu = document.getElementById('sidebarMenu');
  const overlay = document.getElementById('overlay');

  toggleSidebar.addEventListener('click', () => {
    sidebarMenu.style.transform = 'translateX(0)';
    overlay.style.display = 'block';
  });

  overlay.addEventListener('click', () => {
    sidebarMenu.style.transform = 'translateX(100%)';
    overlay.style.display = 'none';
  });

  document.querySelectorAll('#sidebarMenu a').forEach(link => {
    link.addEventListener('click', () => {
      sidebarMenu.style.transform = 'translateX(100%)';
      overlay.style.display = 'none';
    });
  });
</script>

</body>
</html>
