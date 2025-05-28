<?php
session_start();

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario'])) {
    header('Location: paginainicio.php');
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'asis');
if ($mysqli->connect_errno) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro'])) {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $confirmar = trim($_POST['confirmar'] ?? '');

    if ($usuario === '' || $contrasena === '' || $confirmar === '') {
        $error = 'Por favor, complete todos los campos.';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // Verificar si el usuario ya existe
        $stmt = $mysqli->prepare('SELECT id FROM inicio WHERE usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'El usuario ya existe.';
        } else {
            $stmt->close();

            // Encriptar contraseña y registrar nuevo usuario
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare('INSERT INTO inicio (usuario, contrasena) VALUES (?, ?)');
            $stmt->bind_param('ss', $usuario, $hash);

            if ($stmt->execute()) {
                $mensaje = 'Registro exitoso. Ahora puede iniciar sesión.';
            } else {
                $error = 'Error al registrar el usuario.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body {
            background: linear-gradient(135deg, #00c9ff 0%, #92fe9d 100%);
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #00c9ff;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.3rem;
            color: #007e99;
        }
        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn {
            width: 100%;
            padding: 0.7rem;
            background-color: #00c9ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #00aacc;
        }
        .mensaje, .error {
            margin-bottom: 1rem;
            padding: 0.7rem;
            border-radius: 6px;
            text-align: center;
        }
        .mensaje {
            background-color: #e0f7e9;
            color: #2e7d32;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #007e99;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
            color: #00aacc;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registro de Usuario</h2>
        <?php if ($mensaje): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group">
                <label for="confirmar">Confirmar Contraseña:</label>
                <input type="password" id="confirmar" name="confirmar" required>
            </div>
            <button type="submit" name="registro" class="btn">Registrarse</button>
        </form>
        <a href="index.php" class="back-link">← Volver a Iniciar Sesión</a>
    </div>
</body>
</html>
