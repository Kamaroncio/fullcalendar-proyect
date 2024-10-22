document.querySelectorAll('.more-info-btn').forEach((button, index) => {
    button.addEventListener('click', function() {
        const hiddenRow = document.querySelectorAll('.oculto')[index];
        const detailRows = hiddenRow.querySelectorAll('.detalle-tabla tr'); // Selecciona las filas dentro de la tabla de detalles
        
        if (hiddenRow.style.display === 'none' || hiddenRow.style.display === '') {
            hiddenRow.style.display = 'table-row'; // Muestra la fila oculta
            button.textContent = 'Menos';

            // Mostrar las filas de detalle
            detailRows.forEach(row => {
                row.style.display = ''; // Asegura que las filas de detalle sean visibles
            });
        } else {
            hiddenRow.style.display = 'none'; // Oculta la fila
            button.textContent = 'Más';

            // Ocultar las filas de detalle
            detailRows.forEach(row => {
                row.style.display = 'none'; // Oculta las filas de detalle
            });
        }

        // También asegurarse de que la fila padre se mantenga visible
        const parentRow = button.closest('.fila-historial'); // Obtiene la fila padre correspondiente
        parentRow.style.display = ''; // Asegura que la fila principal sea visible
    });
});

// Inicialmente ocultar todas las filas adicionales
window.onload = function() {
    reiniciarTabla(); // Llama a la función para iniciar la tabla correctamente
};

// Función para filtrar columnas
function filtrarColumna(inputId, columnaIndex) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById("tablahistorial");
    const tr = table.getElementsByTagName("tr");
    
    let visibleCount = 0;

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td")[columnaIndex];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = ""; // Mostrar fila
                visibleCount++;
            } else {
                tr[i].style.display = "none"; // Ocultar fila
            }
        }
    }

    // Ocultar el botón "Mostrar más" si hay texto en cualquier campo de búsqueda
    const searchFields = [
        document.getElementById("buscarID"),
        document.getElementById("buscarTitulo"),
        document.getElementById("buscarDescripcion"),
        document.getElementById("buscarInicio"),
        document.getElementById("buscarFin"),
        document.getElementById("buscarEmpleados"),
        document.getElementById("buscarClientes"),
    ];

    const hasText = searchFields.some(field => field.value.trim() !== '');
    
    document.getElementById('mostrarMas').style.display = hasText ? 'none' : (visibleCount > 50 ? 'block' : 'none');

    // Si no hay texto en ninguna barra de búsqueda, reiniciar la tabla
    if (!hasText) {
        reiniciarTabla();
    }
}

// Función para reiniciar la tabla
function reiniciarTabla() {
    const filas = document.querySelectorAll('.fila-historial');
    for (let i = 0; i < filas.length; i++) {
        filas[i].style.display = 'none'; // Ocultar todas las filas
    }

    // Mostrar las primeras 50 filas
    for (let i = 0; i < Math.min(50, filas.length); i++) {
        filas[i].style.display = ''; // Mostrar filas
    }

    // Ocultar el botón si ya se han mostrado todas las filas
    document.getElementById('mostrarMas').style.display = (filas.length > 50) ? 'block' : 'none';
}

// Mostrar más filas
document.getElementById('mostrarMas').addEventListener('click', function() {
    const filas = document.querySelectorAll('.fila-historial');
    let visibleRows = 0;

    for (let i = 0; i < filas.length; i++) {
        if (filas[i].style.display !== 'none') {
            visibleRows++;
        }
    }

    for (let i = visibleRows; i < visibleRows + 50 && i < filas.length; i++) {
        filas[i].style.display = ''; // Mostrar más filas
    }

    // Si ya se han mostrado todas las filas, ocultar el botón
    if (visibleRows + 50 >= filas.length) {
        this.style.display = 'none';
    }
});

// Asociar los inputs a las funciones de filtrado
document.getElementById("buscarID").addEventListener("keyup", function() {
    filtrarColumna("buscarID", 0);
});
document.getElementById("buscarTitulo").addEventListener("keyup", function() {
    filtrarColumna("buscarTitulo", 1);
});
document.getElementById("buscarDescripcion").addEventListener("keyup", function() {
    filtrarColumna("buscarDescripcion", 2);
});
document.getElementById("buscarInicio").addEventListener("keyup", function() {
    filtrarColumna("buscarInicio", 3);
});
document.getElementById("buscarFin").addEventListener("keyup", function() {
    filtrarColumna("buscarFin", 4);
});
document.getElementById("buscarEmpleados").addEventListener("keyup", function() {
    filtrarColumna("buscarEmpleados", 5);
});
document.getElementById("buscarClientes").addEventListener("keyup", function() {
    filtrarColumna("buscarClientes", 6);
});
