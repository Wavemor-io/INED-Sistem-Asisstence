<?php
include 'conexion.php';

$busqueda = '';
if (isset($_GET['buscar'])) {
  $busqueda = $conexion->real_escape_string($_GET['buscar']);
  $sql = "SELECT * FROM alumnos WHERE nombre LIKE '%$busqueda%' OR codest LIKE '%$busqueda%'";
} else {
  $sql = "SELECT * FROM alumnos";
}
$resultado = $conexion->query($sql);

if (!$resultado) {
  die("Error al obtener alumnos: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Buscar y Editar Alumnos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f8; }
    h1 { color: #333; }
    .container { max-width: 900px; margin: 100px auto 40px auto; padding: 20px; background: #fff; border-radius: 8px; }

    form.busqueda { margin-bottom: 20px; display: flex; gap: 10px; }

    table { border-collapse: collapse; width: 100%; background: #fff; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background: #007bff; color: #fff; }
    tr:nth-child(even) { background: #f2f2f2; }

    .editar-btn {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }

    .editar-btn:hover { background: #218838; }

    /* Sidebar Menu */
    #sidebarMenu {
      width: 250px;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      z-index: 1040;
      background-color: rgba(255, 255, 255, 0.6);
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

<!-- Botón de menú -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
  <button class="btn btn-primary" id="toggleSidebar">&#9776;</button>
</div>

<!-- Sidebar -->
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

<!-- Fondo oscuro para cerrar menú -->
<div id="overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>

<div class="container">
  <h1>Buscar y Editar Alumnos</h1>
  <form class="busqueda" method="get">
    <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o código" value="<?= htmlspecialchars($busqueda) ?>">
    <button type="submit" class="btn btn-primary">Buscar</button>
  </form>

  <table>
    <tr>
      <th>Código</th>
      <th>Nombre</th>
      <th>CUI</th>
      <th>Grado</th>
      <th>Sección</th>
      <th>Año</th>
      <th>Acción</th>
    </tr>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($alumno = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($alumno['codest']) ?></td>
          <td><?= htmlspecialchars($alumno['nombre']) ?></td>
          <td><?= htmlspecialchars($alumno['cui']) ?></td>
          <td><?= htmlspecialchars($alumno['grado']) ?></td>
          <td><?= htmlspecialchars($alumno['seccion']) ?></td>
          <td><?= htmlspecialchars($alumno['año']) ?></td>
          <td>
            <form action="editar.php" method="get" style="margin:0;">
              <input type="hidden" name="id" value="<?= $alumno['id'] ?>">
              <button type="submit" class="editar-btn">Editar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">No se encontraron alumnos.</td></tr>
    <?php endif; ?>
  </table>
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
