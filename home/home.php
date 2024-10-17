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
    <link rel="stylesheet" href="homebarranav.css">
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
    
    <?php
    // echo "ID del usuario logueado: " . $iduser;
    ?>
</body>
</html>
