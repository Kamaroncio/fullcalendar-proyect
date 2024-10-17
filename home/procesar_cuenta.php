<?php

include('checkuserlogin.php');

session_start();
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'reservas';
$username = 'root'; // Cambiar si es necesario
$password = ''; // Cambiar si es necesario

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        $_SESSION['repetido'] = "Las contraseñas no coinciden <br> <br>";
        $_SESSION['correcto'] = "";
        header("Location: anadir.php");
        exit();
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insertar el usuario en la base de datos
    try {
        // Asegurarse de que los nombres de los parámetros coincidan en la consulta SQL y en bindParam
        $stmt = $conn->prepare("INSERT INTO users (user, pass) VALUES (?, ?)");
        $stmt->execute([$nombre, $hashed_password]);
        $_SESSION['correcto'] = "Ususario añadido correctamente <br> <br>";
        header("Location: anadir.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['repetido'] = "Nombre de usuario repetido <br> <br>";
        $_SESSION['correcto'] = "";
        header("Location: anadir.php");
        exit();
    }
}
?>
