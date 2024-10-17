<?php
include('checkuserlogin.php');
$iduser = $_SESSION['userid'];
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "reservas";

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Función para verificar conflictos de sala

function checkRoomConflict($conn, $description, $start, $end, $eventId = null) {
    // Extraer solo los números de la descripción
    preg_match_all('/\d+/', $description, $matches);
    $roomNumbers = $matches[0]; // Array de números

    // Crear una consulta SQL dinámica para verificar conflictos
    if (empty($roomNumbers)) {
        return null; // Si no hay números, no hay conflicto
    }

    // Crear condiciones para la cláusula WHERE
    $conditions = [];
    $params = [];

    foreach ($roomNumbers as $roomNumber) {
        $conditions[] = "description LIKE ?";
        $params[] = "%$roomNumber%"; // Busca el número en la descripción
    }

    // Unir condiciones con OR
    $sql = "SELECT * FROM events 
            WHERE (" . implode(' OR ', $conditions) . ") 
            AND ((start < ? AND end > ?))";

    $params[] = $end;
    $params[] = $start;

    // Si estamos actualizando, excluimos el evento actual del chequeo
    if ($eventId) {
        $sql .= " AND id != ?";
        $params[] = $eventId;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Obtener todos los eventos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM events";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
    exit;
}

// Crear un nuevo evento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    $title = $data['title'];
    $observations = $data['observations'];
    $start = $data['start'];
    $end = $data['end'];
    $description = $data['description'];
    $employees = $data['employees'];
    $clients = $data['clients'];

    // Verificar conflicto de sala
    if (checkRoomConflict($conn, $description, $start, $end)) {
        echo json_encode(['error' => 'Conflicto de sala en la fecha y hora seleccionadas']);
        exit;
    }

    // Si no hay conflicto, insertar el evento
    $sql = "INSERT INTO events (title, start, end, description, employees, clients, iduser, observations) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $start, $end, $description, $employees, $clients, $iduser, $observations]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Event created successfully']);
    } else {
        echo json_encode(['error' => 'Failed to create event']);
    }
    exit;
}

// Actualizar un evento
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(['error' => 'ID parameter is missing']);
        exit;
    }

    $id = $data['id'];
    $title = $data['title'];
    $observations = $data['observations'];
    $start = $data['start'];
    $end = $data['end'];
    $description = $data['description'];
    $employees = $data['employees'];
    $clients = $data['clients'];

    // Verificar conflicto de sala, excluyendo el evento actual
    if (checkRoomConflict($conn, $description, $start, $end, $id)) {
        echo json_encode(['error' => 'Conflicto de sala en la fecha y hora seleccionadas']);
        exit;
    }

    // Actualizar el evento si no hay conflicto
    $sql = "UPDATE events SET title = ?, start = ?, end = ?, description = ?, employees = ?, clients = ?, iduser = ?, observations = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $start, $end, $description, $employees, $clients, $iduser, $observations, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Event updated successfully']);
    } else {
        echo json_encode(['error' => 'Event not found or no changes made']);
    }
    exit;
}

// Eliminar un evento
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id'])) {
        echo json_encode(['error' => 'ID parameter is missing']);
        exit;
    }

    $id = $_GET['id'];
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Event deleted']);
    exit;
}

$conn = null;
?>
