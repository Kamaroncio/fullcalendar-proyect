<?php
session_start();

function checkAuth() {
    // Verificar que la sesión esté activa
    if (!isset($_SESSION['auth_key'])) {
        // Redirigir al login si no está logueado
        header('Location: ../login.php');
        exit();
    }
}

// Llamar a la función de verificación en cada página
checkAuth();
?>
