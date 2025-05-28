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

// Procesar formulario
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Consulta segura usando prepared statements en la tabla correcta
    $stmt = $mysqli->prepare('SELECT contrasena FROM inicio WHERE usuario = ?');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hash);
        $stmt->fetch();
        if (password_verify($contrasena, $hash)) {
            $_SESSION['usuario'] = $usuario;
            header('Location: paginainicio.php');
            exit();
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(33,147,176,0.2);
            width: 100%;
            max-width: 370px;
        }
        .login-container h2 {
            text-align: center;
            color: #2193b0;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #2193b0;
            font-weight: 500;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #b2ebf2;
            border-radius: 8px;
            background: #f0faff;
            font-size: 1rem;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2193b0;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover {
            background: linear-gradient(90deg, #6dd5ed 0%, #2193b0 100%);
        }
        .error {
            color: #e53935;
            background: #ffebee;
            padding: 0.7rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .register-btn {
            margin-top: 1rem;
            width: 100%;
            display: block;
            text-align: center;
            background: #fff;
            color: #2193b0;
            border: 2px solid #2193b0;
            border-radius: 8px;
            padding: 0.7rem 0;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .register-btn:hover {
            background: #2193b0;
            color: #fff;
        }
        .info {
            color: #1565c0;
            background: #e3f2fd;
            padding: 0.7rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" required autofocus>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" required>
            </div>
            <button type="submit" class="btn" name="login">Entrar</button>
        </form>
        <a href="regis.php" class="register-btn">Registrarse</a>
    </div>
    <?php 

?>
</body>
</html>
