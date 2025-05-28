<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'regis';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Parámetros
$sort_by = $_GET['sort_by'] ?? 'codest';
$order = $_GET['order'] ?? 'asc';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Validación
$allowed_sort = ['codest', 'nombre', 'fecha'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort_by, $allowed_sort)) $sort_by = 'codest';
if (!in_array($order, $allowed_order)) $order = 'asc';

// Consulta
$where = [];
$params = [];
$types = '';

if ($start_date) {
    $where[] = "DATE(fecha) >= ?";
    $params[] = $start_date;
    $types .= 's';
}
if ($end_date) {
    $where[] = "DATE(fecha) <= ?";
    $params[] = $end_date;
    $types .= 's';
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT id, codest, nombre, fecha FROM registro $where_sql ORDER BY $sort_by $order";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_row()) {
    $data[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
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
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        form input, form select, form button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
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

        @media (max-width: 768px) {
            form {
                flex-direction: column;
                align-items: center;
            }
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

<!-- Título -->
<h1>Buscar Asistencia</h1>

<!-- Formulario -->
<form method="get" action="">
    <select name="sort_by">
        <option value="codest" <?php if($sort_by=='codest') echo 'selected'; ?>>Código Estudiante</option>
        <option value="nombre" <?php if($sort_by=='nombre') echo 'selected'; ?>>Nombre</option>
        <option value="fecha" <?php if($sort_by=='fecha') echo 'selected'; ?>>Fecha</option>
    </select>
    <select name="order">
        <option value="asc" <?php if($order=='asc') echo 'selected'; ?>>Ascendente</option>
        <option value="desc" <?php if($order=='desc') echo 'selected'; ?>>Descendente</option>
    </select>
    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    <button type="submit" class="btn btn-success">Filtrar</button>
</form>

<!-- Tabla -->
<table>
    <thead>
        <tr>
            <th>N°</th>
            <th>Código Estudiante</th>
            <th>Nombre</th>
            <th>Fecha y Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $record): ?>
        <tr>
            <td><?php echo htmlspecialchars($record[0]); ?></td>
            <td><?php echo htmlspecialchars($record[1]); ?></td>
            <td><?php echo htmlspecialchars($record[2]); ?></td>
            <td><?php echo htmlspecialchars($record[3]); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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
