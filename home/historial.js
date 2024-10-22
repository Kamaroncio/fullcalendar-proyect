document.querySelectorAll('.more-info-btn').forEach((button, index) => {
    button.addEventListener('click', function() {
        const hiddenRow = document.querySelectorAll('.oculto')[index];
        if (hiddenRow.style.display === 'none' || hiddenRow.style.display === '') {
            hiddenRow.style.display = 'table-row';
            button.textContent = 'Menos';
        } else {
            hiddenRow.style.display = 'none';
            button.textContent = 'Más';
        }
    });
});

// Inicialmente ocultar todas las filas adicionales
window.onload = function() {
    const filas = document.querySelectorAll('.fila-historial');
    for (let i = 50; i < filas.length; i++) {
        filas[i].style.display = 'none';
    }
    document.querySelectorAll('.oculto').forEach(row => {
        row.style.display = 'none';
    });
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
                tr[i].style.display = "";
                visibleCount++;
            } else {
                tr[i].style.display = "none";
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
        filas[i].style.display = 'none';
    }

    // Mostrar las primeras 50 filas
    for (let i = 0; i < Math.min(50, filas.length); i++) {
        filas[i].style.display = '';
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
        filas[i].style.display = '';
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
