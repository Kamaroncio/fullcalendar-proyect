<?php

include('checkuserlogin.php');

// Verificar si 'userid' está establecido en la sesión
if (!isset($_SESSION['userid'])) {
    // Si no hay 'userid', redirigir al login u otra acción
    echo "Usuario no logueado.";
    exit; // Detener la ejecución si no hay usuario logueado
}

$iduser = $_SESSION['userid'];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITAS</title>
    <!-- Enlace a Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homebarranav.css">
    <link rel="stylesheet" href="herramientas.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="reservarsala.php">Reservar</a></li>
                <li><a href="historial.php">Historial</a></li>
                <li><a href="herramientas.php">Herramientas</a></li>
                <li><a href="../login.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <br><br><br>
    <center>
        <table border="0">
            <tr>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button">Eventos</button>
                    </a>
                </td>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button">Usuarios</button>
                    </a>
                </td>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button">Permisos</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button">Salas</button>
                    </a>
                </td>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button"><center> Empresas<br> y <br>Clientes </center></button>
                    </a>
                </td>
                <td>
                    <a href="anadir.php">
                        <button class="button-herramienta" role="button">Empleados</button>
                    </a>
                </td>
            </tr>
        </table>
    </center>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

