<?php
include('checkuserlogin.php');
try {
    $servername = 'localhost';
    $database = 'reservas';
    $userdb = 'root';
    $passworddb = '';
    $conn = new PDO("mysql:host=$servername;dbname=$database", $userdb, $passworddb); 

    $stmt = $conn->query("SELECT * FROM historial ORDER BY id DESC");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITAS - Historial</title>
    <link rel="stylesheet" href="homebarranav.css">
    <link rel="stylesheet" href="historial.css">
    <style>
        .oculto { display: none; }
    </style>
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
    <div class="table-wrapper">
        <table border="1" id="tablahistorial">
            <thead>
                <tr>
                    <th>ID <br><br><input type="text" id="buscarID" placeholder="Buscar por ID"></th>
                    <th>Título <br><br><input type="text" id="buscarTitulo" placeholder="Buscar por Título"></th>
                    <th>Sala <br><br><input type="text" id="buscarDescripcion" placeholder="Buscar por Sala"></th>
                    <th>Inicio <br><br><input type="text" id="buscarInicio" placeholder="Buscar por Inicio"></th>
                    <th>Fin <br><br><input type="text" id="buscarFin" placeholder="Buscar por Fin"></th>
                    <th>Empleados <br><br><input type="text" id="buscarEmpleados" placeholder="Buscar por Empleados"></th>
                    <th>Clientes <br><br><input type="text" id="buscarClientes" placeholder="Buscar por Clientes"></th>
                    <th>Más</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $evento): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento['evento_id']); ?></td>
                        <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                        <td><?php echo (new DateTime($evento['inicio']))->format('d-m-Y H:i:s'); ?></td>
                        <td><?php echo (new DateTime($evento['fin']))->format('d-m-Y H:i:s'); ?></td>
                        <td><?php echo htmlspecialchars($evento['empleados']); ?></td>
                        <td><?php echo htmlspecialchars($evento['clientes']); ?></td>
                        <td><button class="more-info-btn">Más</button></td>
                    </tr>
                    <tr class="oculto">
                        <td colspan="8">
                            <div class="detalle-info">
                                <table class="detalle-tabla">
                                    <tr>
                                        <td><strong>Acción:</strong></td>
                                        <td><?php echo htmlspecialchars($evento['accion']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Inserción:</strong></td>
                                        <td><?php echo (new DateTime($evento['fecha_insercion']))->format('d-m-Y H:i:s'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>ID Usuario:</strong></td>
                                        <td><?php echo htmlspecialchars($evento['iduser']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Observaciones:</strong></td>
                                        <td><?php echo htmlspecialchars($evento['observations']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </center>
    <script src="historial.js"></script>
</body>
</html>
