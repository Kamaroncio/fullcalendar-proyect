<?php
include('checkuserlogin.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITAS - Crear Cuenta</title>
    <link rel="stylesheet" href="homebarranav.css">
    <link rel="stylesheet" href="anadir.css">
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
    <center>
        <section>
        <h1>Crear Cuenta de Usuario</h1>
        <form action="procesar_cuenta.php" method="POST">
                <b>
                    <br>
                        <?php
                            // session_start();
                            if (!isset($_SESSION['repetido']) || $_SESSION['repetido'] === null) {
                                $_SESSION['repetido'] = "";
                            }
                            if (!isset($_SESSION['correcto']) || $_SESSION['correcto'] === null) {
                                $_SESSION['correcto'] = "";
                            }
                            $correcto = $_SESSION['correcto'];
                            $repetido = $_SESSION['repetido'];
                            echo "<span style='color: red;'>$repetido</span>";
                            echo "<span style='color: green;'>$correcto</span>";

                        ?>
                    <br>
                </b>
            <label for="nombre"><b>Nombre:</b></label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="password"><b>contraseña:</b></label>
            <input type="password" id="password" name="password" required><br>

            <label for="confirm_password"><b>Confirmar Contraseña:</b></label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>

            <button type="submit">Crear Cuenta</button>
        </form>
    </section>
    </center>
</body>
</html>
