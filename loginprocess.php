<?php
session_start();
$user = $_POST['name'];
$password = $_POST['password'];

try {
    $servername = 'localhost';
    $database = 'reservas';
    $userdb = 'root';
    $passworddb = '';
    $db = new PDO("mysql:host=$servername;dbname=$database", $userdb, $passworddb); 
    
    // Busca el hash de la contraseña en la base de datos
    $usuario = $db->prepare("SELECT user_id, pass FROM users WHERE user = :user");
    $usuario->bindParam(':user', $user);
    $usuario->execute();

    // Recupera el resultado, incluyendo el hash almacenado de la contraseña
    $resultado = $usuario->fetch(PDO::FETCH_ASSOC);

    // Aquí se verifica la contraseña ingresada con el hash almacenado
    if ($resultado && password_verify($password, $resultado['pass'])) {
        // Si la contraseña es correcta
        $randomKey = bin2hex(random_bytes(32));     // ------------------------------------------------- Genera una cadena aleatoria segura
        $_SESSION['auth_key'] = $randomKey;         // ------------------------------------------------- Guardar la key en la sesión del servidor
        $_SESSION['userid'] = $resultado['user_id'];
        $_SESSION['incorrecto'] = "";
        header("Location: home/home.php");
        exit();
    } else {
        // Si la contraseña es incorrecta
        $_SESSION['incorrecto'] = "contraseña incorrecta <br>";
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) { 
    print "Error: ". $e->getMessage() . "<br/>"; 
}
?>
