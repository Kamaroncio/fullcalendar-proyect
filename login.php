<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div id="contenedor">
        <form action="loginprocess.php" method="POST">
            <center>
                <h1>reserva de salas</h1>
            <label for="name"><b>Nombre de usuario:</b></label>
            </center>
            <input type="text" id="name" name="name" required>
            <br>
            <center>
            <label for="incorrecta" id="incorrecta">
                <b>
                <?php
                    session_start();
                    if (!isset($_SESSION['incorrecto']) || $_SESSION['incorrecto'] === null) {
                        $_SESSION['incorrecto'] = "";
                    }
                    $incorrecta = $_SESSION['incorrecto'];
                    echo $incorrecta;
                ?>
                </b>
            </label>
            <br>
            <label for="password"><b>Contraseña:</b></label>
            </center>
            <input type="password" id="password" name="password" required>
            <br>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>