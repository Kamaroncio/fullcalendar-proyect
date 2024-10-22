
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        nowIndicator: true,
        allDaySlot: false,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        selectable: true,
        dayMaxEventRows: 4,  // Limita el número de eventos visibles a 3 por día en vista mensual
        moreLinkText: 'ver más',  // Cambia el texto del botón "ver más"
  slotMinTime: '07:00:00',

  views: {
    timeGridWeek: { // Vista semana
        hiddenDays: [0, 6],
        slotMinTime: '07:00:00'
      
    },
    timeGridDay: { // Vista día
        hiddenDays: [0, 6],
        slotMinTime: '07:00:00',
        titleFormat: { 
            year: 'numeric',
            month: 'short', 
            day: 'numeric'
        }
    }

  },
        select: function(info) {
            if (calendar.view.type !== 'dayGridMonth') {
                document.getElementById('eventStart').value = info.startStr;
                document.getElementById('eventEnd').value = info.endStr;
                document.getElementById('eventId').value = ""; // Limpiar el ID para nuevos eventos
                document.getElementById('eventForm').reset();
                document.getElementById('deleteEvent').style.display = "none";
                document.getElementById('eventModal').querySelector('.modal-title').textContent = "Añadir Evento";
                
                updateVisibleInputs(info.startStr, info.endStr);

                eventModal.show();
            }
        },
        eventClick: function(info) {
            document.getElementById('eventTitle').value = info.event.title;
            document.getElementById('eventObservations').value = info.event.extendedProps.observations || "";
            document.getElementById('eventDescription').value = info.event.extendedProps.description || "";
            document.getElementById('eventEmployees').value = info.event.extendedProps.employees || "";
            document.getElementById('eventClients').value = info.event.extendedProps.clients || "";
            document.getElementById('eventStart').value = info.event.startStr;
            document.getElementById('eventEnd').value = info.event.endStr || info.event.startStr;
            document.getElementById('eventId').value = info.event.id;
            document.getElementById('deleteEvent').style.display = "block";
            document.getElementById('eventModal').querySelector('.modal-title').textContent = "Modificar Evento";

            updateVisibleInputs(info.event.startStr, info.event.endStr);

            eventModal.show();
        },
        dateClick: function(info) {
            if (calendar.view.type === 'dayGridMonth') {
                calendar.changeView('timeGridDay', info.dateStr);
            }
        },
        eventContent: function(arg) {
            let titleEl = document.createElement('div');
            let descriptionEl = document.createElement('div');
            let timeEl = document.createElement('div'); // Para mostrar la hora de inicio y fin
            let dotEl = document.createElement('span'); // Para el circulito
        
            // Extraer los números de la descripción usando una expresión regular
            let description = arg.event.extendedProps.description;
            let numbers = description.match(/\d+/g);
            numbers = numbers ? numbers.map(Number) : [];
        
            // Generar el color basado en los números
            let color;
            if (numbers.length === 1) {
                switch (numbers[0]) {
                    case 1:
                        color = 'rgb(41, 128, 185)'; 
                        break;
                    case 2:
                        color = 'rgb(185, 41, 41)';
                        break;
                    case 3:
                        color = 'rgb(123, 185, 41)'; 
                        break;
                    case 4:
                        color = 'rgb(41, 185, 185)';
                        break;
                    case 5:
                        color = 'rgb(67, 41, 185)';
                        break;
                    case 6:
                        color = 'rgb(185, 41, 142)'; 
                        break;
                    case 7:
                        color = 'rgb(41, 185, 137)';
                        break;
                    case 8:
                        color = 'rgb(185, 111, 41)'; 
                        break;
                    case 9:
                        color = 'rgb(209, 88, 114)'; 
                        break;
                    default:
                        color = 'rgb(187, 187, 187)';
                }
            } else if (numbers.length === 2 && numbers.includes(1) && numbers.includes(2)) {
                color = 'rgb(85, 170, 85)';  // Verde pastel oscuro solo para "1 y 2"
            } else if (numbers.length > 1) {
                // Variables para acumular los valores de los colores preestablecidos
                let red = 0, green = 0, blue = 0;

                // Definir los colores preestablecidos
                const presetColors = {
                    1: { r: 41, g: 128, b: 185 },
                    2: { r: 185, g: 41, b: 41 },
                    3: { r: 123, g: 185, b: 41 },
                    4: { r: 41, g: 185, b: 185 },
                    5: { r: 67, g: 41, b: 185 },
                    6: { r: 185, g: 41, b: 142 },
                    7: { r: 41, g: 185, b: 137 },
                    8: { r: 185, g: 111, b: 41 },
                    9: { r: 209, g: 88, b: 114 }
                };

                // Sumar los valores RGB de los colores preestablecidos
                numbers.forEach(function(number) {
                    if (presetColors[number]) {
                        red += presetColors[number].r;
                        green += presetColors[number].g;
                        blue += presetColors[number].b;
                    }
                });

                // Normalizar los colores dividiendo entre la cantidad de números
                red = Math.floor(red / numbers.length);
                green = Math.floor(green / numbers.length);
                blue = Math.floor(blue / numbers.length);

                // Incrementar el brillo
                const brightnessBoost = 30;  // Aumentar este valor para más brillo
                red = Math.min(255, red + brightnessBoost);
                green = Math.min(255, green + brightnessBoost);
                blue = Math.min(255, blue + brightnessBoost);

                // Aplicar el color combinado
                color = `rgb(${red}, ${green}, ${blue})`;
            } else {
                color = 'rgb(127, 140, 141)'; // Gris oscuro por defecto
            }
        
            if (arg.view.type !== 'timeGridWeek' && arg.view.type !== 'timeGridDay') {
                dotEl.style.backgroundColor = color;
                dotEl.style.borderRadius = '50%';
                dotEl.style.display = 'inline-block';
                dotEl.style.width = '10px';
                dotEl.style.height = '10px';
                dotEl.style.marginRight = '5px';
                dotEl.style.verticalAlign = 'middle';
                dotEl.style.flexShrink = '0';
            }
        
            let truncatedTitle = arg.event.title.length > 18 ? arg.event.title.substring(0, 18) + '...' : arg.event.title;
            titleEl.innerHTML = '<strong>' + truncatedTitle + '</strong>';
        
            if (arg.event.extendedProps.description) {
                descriptionEl.innerHTML = '<small>' + arg.event.extendedProps.description + '</small>';
            }
        
            if (arg.view.type === 'timeGridWeek' || arg.view.type === 'timeGridDay') {
                let startTime = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                let endTime = arg.event.end ? arg.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : "";
                timeEl.innerHTML = '<small>' + startTime + ' - ' + endTime + '</small>';
            }
        
            let elements = [titleEl, timeEl, descriptionEl];
            if (dotEl.style.backgroundColor) {
                elements.unshift(dotEl); // Agregar el span al principio del array si existe
            }
        
            return { domNodes: elements };
        },
        
        eventDidMount: function(info) {
            let description = info.event.extendedProps.description;
            let numbers = description.match(/\d+/g);
            numbers = numbers ? numbers.map(Number) : [];
        
            let color;
            if (numbers.length === 1) {
                switch (numbers[0]) {
                    case 1:
                        color = 'rgb(41, 128, 185)'; 
                        break;
                    case 2:
                        color = 'rgb(185, 41, 41)';
                        break;
                    case 3:
                        color = 'rgb(123, 185, 41)'; 
                        break;
                    case 4:
                        color = 'rgb(41, 185, 185)';
                        break;
                    case 5:
                        color = 'rgb(67, 41, 185)';
                        break;
                    case 6:
                        color = 'rgb(185, 41, 142)'; 
                        break;
                    case 7:
                        color = 'rgb(41, 185, 137)';
                        break;
                    case 8:
                        color = 'rgb(185, 111, 41)'; 
                        break;
                    case 9:
                        color = 'rgb(209, 88, 114)'; 
                        break;
                    default:
                        color = 'rgb(187, 187, 187)';
                }
            } else if (numbers.length === 2 && numbers.includes(1) && numbers.includes(2)) {
                color = 'rgb(85, 170, 85)';  // Verde pastel oscuro solo para "1 y 2"
            } else if (numbers.length > 1) {
                // Variables para acumular los valores de los colores preestablecidos
                let red = 0, green = 0, blue = 0;

                // Definir los colores preestablecidos
                const presetColors = {
                    1: { r: 41, g: 128, b: 185 },
                    2: { r: 185, g: 41, b: 41 },
                    3: { r: 123, g: 185, b: 41 },
                    4: { r: 41, g: 185, b: 185 },
                    5: { r: 67, g: 41, b: 185 },
                    6: { r: 185, g: 41, b: 142 },
                    7: { r: 41, g: 185, b: 137 },
                    8: { r: 185, g: 111, b: 41 },
                    9: { r: 209, g: 88, b: 114 }
                };

                // Sumar los valores RGB de los colores preestablecidos
                numbers.forEach(function(number) {
                    if (presetColors[number]) {
                        red += presetColors[number].r;
                        green += presetColors[number].g;
                        blue += presetColors[number].b;
                    }
                });

                // Normalizar los colores dividiendo entre la cantidad de números
                red = Math.floor(red / numbers.length);
                green = Math.floor(green / numbers.length);
                blue = Math.floor(blue / numbers.length);

                // Incrementar el brillo
                const brightnessBoost = 30;  // Aumentar este valor para más brillo
                red = Math.min(255, red + brightnessBoost);
                green = Math.min(255, green + brightnessBoost);
                blue = Math.min(255, blue + brightnessBoost);

                // Aplicar el color combinado
                color = `rgb(${red}, ${green}, ${blue})`;
            } else {
                color = 'rgb(127, 140, 141)'; // Gris oscuro por defecto
            }
        
            if (calendar.view.type === 'timeGridWeek' || calendar.view.type === 'timeGridDay') {
                info.el.style.backgroundColor = color;
            }
        
            var popoverContent = `
                <div>Sala: ${info.event.extendedProps.description || ''}</div>
                <div>Empleados: ${info.event.extendedProps.employees || 'N/A'}</div>
                <div>Clientes: ${info.event.extendedProps.clients || 'N/A'}</div>
                <div><small>De ${info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} 
                a ${info.event.end ? info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}</small></div>
            `;
        
            var popover = new bootstrap.Popover(info.el, {
                title: `${info.event.title}`,
                content: popoverContent,
                html: true,
                trigger: 'hover',
                placement: 'top'
            });
        },

        events: function(fetchInfo, successCallback, failureCallback) {
			fetch('./calendar_api.php')
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        }
    });

    calendar.render();

    function updateVisibleInputs(startStr, endStr) {
        const start = new Date(startStr);
        const end = new Date(endStr);
    
        // Formatear la fecha y hora
        const startHour = start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const endHour = end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const date = start.toISOString().split('T')[0];
    
        // Mostrar los valores en los inputs visibles
        document.getElementById('eventStartVisible').value = startHour;
        document.getElementById('eventEndVisible').value = endHour;
        document.getElementById('eventDateVisible').value = date;
    
        // Actualizar los inputs ocultos basados en los visibles
        document.getElementById('eventStart').value = `${date}T${startHour}`;
        document.getElementById('eventEnd').value = `${date}T${endHour}`;
    }
    
    // Añadir listeners a los inputs visibles para que actualicen los ocultos
    document.getElementById('eventStartVisible').addEventListener('input', function() {
        const date = document.getElementById('eventDateVisible').value;
        document.getElementById('eventStart').value = `${date}T${this.value}`;
    });
    
    document.getElementById('eventEndVisible').addEventListener('input', function() {
        const date = document.getElementById('eventDateVisible').value;
        document.getElementById('eventEnd').value = `${date}T${this.value}`;
    });
    
    document.getElementById('eventDateVisible').addEventListener('input', function() {
        const startHour = document.getElementById('eventStartVisible').value;
        const endHour = document.getElementById('eventEndVisible').value;
        document.getElementById('eventStart').value = `${this.value}T${startHour}`;
        document.getElementById('eventEnd').value = `${this.value}T${endHour}`;
    });

    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var title = document.getElementById('eventTitle').value;
        var observations = document.getElementById('eventObservations').value;
        var description = document.getElementById('eventDescription').value;
        var employees = document.getElementById('eventEmployees').value;
        var clients = document.getElementById('eventClients').value;
        var start = document.getElementById('eventStart').value;
        var end = document.getElementById('eventEnd').value;
        var eventId = document.getElementById('eventId').value;

        var event = {
            title: title,
            observations: observations,
            start: start,
            end: end,
            description: description,
            employees: employees,
            clients: clients,
            id: eventId // Incluye el ID solo si existe
        };

        console.log('Event data:', event);

        var method = eventId ? 'PUT' : 'POST'; // Usa 'PUT' para actualizar y 'POST' para crear
        var url = `./calendar_api.php${eventId ? '?id=' + eventId : ''}`;

        var requestOptions = {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(event)
        };

        fetch(url, requestOptions)
            .then(response => response.text())
            .then(text => {
                console.log('Response Text:', text);
                try {
                    const json = JSON.parse(text);
                    console.log('Parsed JSON:', json);
                    if (json.error) {
                        alert(json.error); // Muestra un popup de alerta con el error
                        return; // Termina la función si hay un error
                    }
                    calendar.refetchEvents();
                    eventModal.hide();
                    document.getElementById('eventForm').reset();
                } catch (error) {
                    alert('Error al procesar la respuesta.'); // Muestra un error genérico
                    console.error('Error parsing JSON:', error);
                }
            })
            .catch(error => {
                alert('Error al guardar el evento.'); // Muestra un error genérico
                console.error('Error saving event:', error);
            });
    });

    document.getElementById('deleteEvent').addEventListener('click', function(e) {
        e.preventDefault();
        var eventId = document.getElementById('eventId').value;
        if (!eventId) return; // No eliminar si no hay ID

        if (confirm('¿Estás seguro de eliminar este evento?')) {
            fetch(`./calendar_api.php?id=${eventId}`, { method: 'DELETE' })
                .then(response => response.text())
                .then(data => {
                    console.log('Event deleted:', data);
                    calendar.refetchEvents();
                    eventModal.hide();
                })
                .catch(error => console.error('Error deleting event:', error));
        }
    });
    
});



