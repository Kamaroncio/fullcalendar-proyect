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

// Inicialmente ocultar las filas adicionales
window.onload = function() {
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

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td")[columnaIndex];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

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
