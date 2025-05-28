<?php
require('db.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carnets Estudiantiles</title>
    <!-- Bootstrap CSS y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f2f2f2;
            padding: 20px;
        }
        .container-custom {
            max-width: 900px;
            margin: 100px auto 40px auto;
        }
        .card-custom {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: box-shadow 0.2s;
        }
        .card-custom:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
        .btn-custom {
            background: #007bff;
            color: #fff;
            border-radius: 20px;
            padding: 6px 18px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-custom:hover {
            background: #0056b3;
            color: #fff;
        }
        .header-bar {
            background: #007bff;
            color: #fff;
            padding: 24px 0 16px 0;
            border-radius: 12px 12px 0 0;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .download-link {
            font-size: 1.1em;
            margin-bottom: 24px;
            display: inline-block;
        }

        /* Menú lateral */
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

<!-- Menú lateral -->
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

<!-- Contenido principal -->
<div class="container container-custom">
    <div class="header-bar">
        <h1 class="mb-2">Carnets Estudiantiles</h1>
        <a href="qr_todos.php" target="_blank" class="btn btn-light download-link">📚 Descargar PDF con Todos los Carnets</a>
    </div>
    <div class="row">
    <?php
    $sql = "SELECT * FROM alumnos";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $codest = $row['codest'];
        $nombre = $row['nombre'];
        echo "<div class='col-md-6'>";
        echo "<div class='card-custom'>";
        echo "<h5 class='mb-2'><strong>$nombre</strong></h5>";
        echo "<div class='mb-2 text-muted'>Código: $codest</div>";
        echo "<a href='qr_individual.php?codest=$codest' target='_blank' class='btn btn-custom'>📄 Descargar Carnet</a>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    </div>
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
