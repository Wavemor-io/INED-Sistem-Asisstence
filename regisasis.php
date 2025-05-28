<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'regis';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['codigo_estudiantil'])) {
    $codigo_estudiantil = $conn->real_escape_string($_POST['codigo_estudiantil']);
    $sql = "SELECT id, codest, nombre, fecha FROM registro WHERE codest = '$codigo_estudiantil'";
    $query = $conn->query($sql);
    if ($query) {
        while ($row = $query->fetch_row()) {
            $results[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 80px 20px 20px 20px;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #005fa3;
        }
        table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #0077cc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
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

        /* Botón menú */
        #toggleSidebar {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1050;
        }
    </style>
</head>
<body>

<!-- Botón menú -->
<button class="btn btn-primary" id="toggleSidebar">&#9776;</button>

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

<!-- Contenido -->
<h1>Buscar Registros de Asistencia</h1>

<form method="POST">
    <input type="text" name="codigo_estudiantil" placeholder="Ingrese Código Estudiantil" required>
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($results)): ?>
    <h2>Resultados de la Búsqueda</h2>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Código Estudiantil</th>
                <th>Nombre</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record[0]); ?></td>
                <td><?php echo htmlspecialchars($record[1]); ?></td>
                <td><?php echo htmlspecialchars($record[2]); ?></td>
                <td><?php echo htmlspecialchars($record[3]); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- JS -->
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
