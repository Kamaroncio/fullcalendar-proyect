<?php
include('checkuserlogin.php');
?>
<!doctype html>
<html lang="en">
<head>
    <title>Calendario con Eventos</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="homebarranav.css">
    <link rel="stylesheet" href="main.css">
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
    <br><br>
    <main>
        <div class="container">
            <div class="col-md-12">
                <div id='calendar'></div>
            </div>
        </div>
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Añadir/Editar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="eventForm">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="eventTitle" class="form-label">Título del Evento</label>
                                    <input type="text" class="form-control" id="eventTitle" maxlength="50" required>
                                    <button type="button" id="addOptions" class="btn btn-primary">Agregar Opciones</button>
                                    <small id="titleHelp" class="form-text text-muted">Máximo 50 caracteres.</small>
                                </div>
                                <div class="col">
                                    <label for="eventOptions" class="form-label">Opciones Predefinidas</label>
                                    <select id="eventOptions" class="form-select" multiple>
                                        <option value="entrevista">Entrevista</option>
                                        <option value="evento">Evento</option>
                                        <option value="conferencia">Conferencia</option>
                                        <option value="reunión">reunión</option>
                                        <option value="presentación">presentación</option>
                                        <option value="observación">observación</option>
                                        <option value="otro">otro</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                document.getElementById('addOptions').addEventListener('click', function() {
                                    const eventTitleInput = document.getElementById('eventTitle');
                                    const eventOptionsSelect = document.getElementById('eventOptions');
                                    // Obtener las opciones seleccionadas
                                    const selectedOptions = Array.from(eventOptionsSelect.selectedOptions)
                                        .map(option => option.value)
                                        .join(' '); // Unirlas con un espacio
                                    // Calcular el nuevo texto y la longitud total
                                    const currentTitle = eventTitleInput.value.trim();
                                    const newTitle = currentTitle + (currentTitle ? ' ' : '') + selectedOptions;
                                    // Verificar la longitud total
                                    if (newTitle.length <= 50) {
                                        eventTitleInput.value = newTitle; // Añadir las opciones seleccionadas al input de texto
                                    } else {
                                        alert('El título no puede exceder los 50 caracteres.');
                                    }
                                });
                            </script>
                            <div class="mb-3">
                                <label for="eventObservations" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="eventObservations" rows="3" maxlength="124" placeholder="Añade observaciones aquí..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="eventDescription" class="form-label">Salas</label><br>
                                <input type="hidden" class="form-control" id="eventDescription" name="salas" maxlength="50">
                                <select id="salasSelect" multiple class="form-select" size="2">
                                    <?php
                                        try {
                                            $servername = 'localhost';
                                            $database = 'reservas';
                                            $userdb = 'root';
                                            $passworddb = '';
                                            $conn = new PDO("mysql:host=$servername;dbname=$database", $userdb, $passworddb); 
                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                                            $stmt = $conn->query("SELECT sala_id, nombre FROM salas");
                                            $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
                                        } catch(PDOException $e) {
                                            echo "Error en la conexión: " . $e->getMessage();
                                        }
                                    ?>
                                    <?php if (!empty($salas)): ?>
                                        <?php foreach ($salas as $option): ?>
                                            <option value="<?php echo htmlspecialchars($option['sala_id']); ?>">
                                                <?php echo htmlspecialchars($option['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <script>
                                function actualizarSalas() {
                                    const salasSelect = document.getElementById('salasSelect');
                                    const eventDescription = document.getElementById('eventDescription');
                                    const valoresSeleccionados = Array.from(salasSelect.selectedOptions)
                                                                        .map(option => option.value);
                                    let descripcionFormateada = '';
                                    if (valoresSeleccionados.length === 1) {
                                        descripcionFormateada = valoresSeleccionados[0];
                                    } else if (valoresSeleccionados.length === 2) {
                                        descripcionFormateada = valoresSeleccionados.join(' y ');
                                    } else if (valoresSeleccionados.length > 2) {
                                        descripcionFormateada = valoresSeleccionados.slice(0, -1).join(', ') + ' y ' + valoresSeleccionados.slice(-1);
                                    }
                                    eventDescription.value = descripcionFormateada;
                                }
                                document.getElementById('salasSelect').addEventListener('change', actualizarSalas);
                            </script>                            
                            <div class="mb-3">
                                <label for="eventEmployees" class="form-label">Empleados</label>
                                <input type="text" class="form-control" id="eventEmployees" placeholder="Ej: Juan, Ana">
                            </div>
                            <div class="mb-3">
                                <label for="eventClients" class="form-label">Clientes</label>
                                <input type="text" class="form-control" id="eventClients" placeholder="Ej: Pedro, María">
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="eventStartVisible" class="form-label">Hora de Inicio</label>
                                    <input type="text" class="form-control" id="eventStartVisible">
                                </div>
                                <div class="col">
                                    <label for="eventEndVisible" class="form-label">Hora de Fin</label>
                                    <input type="text" class="form-control" id="eventEndVisible">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="eventDateVisible" class="form-label">Fecha</label>
                                <input type="text" class="form-control" id="eventDateVisible">
                            </div>
                            <input type="hidden" id="eventStart">
                            <input type="hidden" id="eventEnd">
                            <input type="hidden" id="eventId">
                            <div class="modal-footer">
                                <button type="submit" id="saveEvent" class="btn btn-primary">Guardar Evento</button>
                                <button type="submit" id="updateEvent" class="btn btn-primary" style="display:none;">Modificar Evento</button>
                                <button type="button" id="deleteEvent" class="btn btn-danger" style="display:none;">Eliminar Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer></footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales-all.js"></script>
    <script src="scriptcalendar.js"></script> <!-- Scripts del calendario -->
</body>
</html>
