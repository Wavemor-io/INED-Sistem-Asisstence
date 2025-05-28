<?php
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --accent: #57d38c;
            --background: #f1f5f9;
            --white: #fff;
            --text: #1e293b;
            --card: #e0e7ef;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: var(--background);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            border-radius: 18px;
            padding: 40px 32px 32px 32px;
            max-width: 400px;
            width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 70px;
            height: 70px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
        }
        .logo svg {
            width: 40px;
            height: 40px;
            fill: var(--white);
        }

        h1 {
            color: var(--text);
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
        }

        p {
            color: #64748b;
            margin-bottom: 28px;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        li {
            margin: 12px 0;
        }

        a {
            display: block;
            text-decoration: none;
            background: var(--primary);
            color: var(--white);
            padding: 12px 0;
            border-radius: 8px;
            text-align: center;
            font-size: 1.08rem;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 1px 4px rgba(59, 130, 246, 0.08);
        }

        a:hover {
            background: var(--primary-dark);
            transform: translateY(-2px) scale(1.03);
        }

        @media (max-width: 600px) {
            .container {
                padding: 24px 8px 18px 8px;
            }
            h1 {
                font-size: 1.3rem;
            }
            a {
                font-size: 0.98rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <!-- Simple QR icon SVG -->
            <svg viewBox="0 0 48 48">
                <rect x="6" y="6" width="12" height="12" rx="3"/>
                <rect x="30" y="6" width="12" height="12" rx="3"/>
                <rect x="6" y="30" width="12" height="12" rx="3"/>
                <rect x="22" y="22" width="4" height="4" rx="1"/>
                <rect x="30" y="30" width="4" height="4" rx="1"/>
                <rect x="38" y="38" width="2" height="2" rx="1"/>
            </svg>
        </div>
        <h1>Bienvenido al Sistema de Asistencia por QR</h1>
        <p>Gestiona y consulta la asistencia de manera fácil y rápida.</p>
       <ul>
         <li><a href="regisasis.php">Ver registros de asistencia</a></li>
         <li><a href="records.php">Buscar asistencia</a></li>
         <li><a href="qr.php">Generar código QR</a></li>
         <li><a href="index1.php">Editar Estudiantes</a></li>
         <li><a href="agregar.php">Agregar Estudiantes</a></li>
         <li><a href="?logout=1">Cerrar sesión</a></li>
        </ul>

    </div>
</body>
</html>
