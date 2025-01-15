if (currentPath.includes("/Evidence")) {
    document.addEventListener('DOMContentLoaded', function () {
        loadRecentFiles();
        listarFiltros();
    });

    // Llama a la función deseada
    const file = document.getElementById('file');

    $(document).ready(function () {
        // Detectar clic en el botón "Agregar"
        $("#btnUpload").click(function () {
            btnNuevoArchivo();
        });

        $("#filter-btn").click(function (e) {
            busquedaPorFiltro(e);
        });
    });

    function btnNuevoArchivo() {
        // Muestra el modal
        $("#upload").modal("show");
    }

    function cerrarModalUpload() {
        $("#upload").modal("hide");
    }

    file.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("file", file);

        const url = BASE_URL + "Evidence/upload";

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false, // Importante: evita que jQuery procese automáticamente los datos
            contentType: false, // Importante: evita que jQuery configure automáticamente el tipo de contenido
            success: function (response) {
                /* console.log(response); */
                try {
                    const res = JSON.parse(response);

                    if (res == "si") {
                        e.target.value = '';
                        notyf.success("Archivo subido con éxito");
                        loadRecentFiles();
                        cerrarModalUpload();
                    } else {
                        notyf.error("Error en la respuesta del servidor.");
                    }
                } catch (e) {
                    console.error("Respuesta no es JSON: " + response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud: " + status + " - " + error);
            }
        });
    });
}

function loadRecentFiles() {
    const url = BASE_URL + "Evidence/listarRecent";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('cards-container');
            container.innerHTML = ''; // Limpiar el contenedor antes de agregar nuevos cards

            if (data.length === 0) {
                // Mostrar mensaje si no hay archivos
                const noFilesMessage = document.createElement('p');
                noFilesMessage.textContent = "No existen archivos recientes.";
                noFilesMessage.classList.add('no-files-message'); // Clase para personalizar el estilo si lo deseas
                container.appendChild(noFilesMessage);
            } else {
                data.forEach(file => {
                    // Usamos la función timeAgo para formatear la fecha
                    const formattedDate = timeAgo(file.fecha_subida);

                    // Convertir el tamaño a KB
                    const sizeInKB = (file.size / 1024).toFixed(2); // Convertir a KB y redondear a 2 decimales

                    const card = document.createElement('div');
                    card.classList.add('card');

                    card.innerHTML = `
                        <div class="no-image">
                            <svg class="icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1" d="m3 16 5-7 6 6.5m6.5 2.5L16 13l-4.286 6M14 10h.01M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"></path>
                            </svg>
                        </div>
                        <div class="content">
                            <p class="name">${file.nombre_archivo}</p>
                            <div class="datos">
                                <p class="size">${sizeInKB} KB</p> <!-- Mostrar el tamaño en KB -->
                                <p class="time">${formattedDate}</p> <!-- Mostrar la fecha formateada -->
                            </div>
                        </div>
                    `;

                    container.appendChild(card);
                });
            }
        })
        .catch(error => console.error('Error al cargar los archivos:', error));
}

function timeAgo(timestamp) {
    const now = new Date();
    const uploadedTime = new Date(timestamp); // Convierte el timestamp a un objeto Date
    const diffInSeconds = Math.floor((now - uploadedTime) / 1000);

    if (diffInSeconds < 1) {
        return 'Justo ahora';
    }

    const intervals = [
        { seconds: 31536000, label: 'año' },
        { seconds: 2592000, label: 'mes' },
        { seconds: 86400, label: 'día' },
        { seconds: 3600, label: 'hora' },
        { seconds: 60, label: 'minuto' },
        { seconds: 1, label: 'segundo' }
    ];

    for (const interval of intervals) {
        const count = Math.floor(diffInSeconds / interval.seconds);
        if (count >= 1) {
            return `hace ${count} ${interval.label}${count > 1 ? 's' : ''}`;
        }
    }
}

function listarFiltros() {
    // AJAX para obtener empleados
    $.ajax({
        url: BASE_URL + 'Evidence/listarEmpleados',
        type: 'POST',
        dataType: 'json',
        /*  data: {
            
        }, */
        success: function (data) {
            const selectEmpleado = $("#empleado_filtro");
            selectEmpleado.empty();

            selectEmpleado.append('<option value="">Seleccione empleado</option>');

            data.forEach(function (empleado) {
                selectEmpleado.append(
                    `<option value="${empleado.id_empleado}">${empleado.nombre_completo}</option>`
                );
            });
        },
        error: function () {
            alert('Error al obtener los empleados');
        }
    });

    // AJAX para obtener sucursales
    $.ajax({
        url: BASE_URL + 'Evidence/listarSucursales',
        type: 'POST',
        dataType: 'json',
        /*  data: {

        }, */
        success: function (data) {
            const selectRole = $("#sucursal_filtro");
            selectRole.empty();

            selectRole.append('<option value="">Seleccione sucursal</option>');

            data.forEach(function (sucursal) {
                selectRole.append(
                    `<option value="${sucursal.id_sucursal}">${sucursal.nombre_sucursal}</option>`
                );
            });
        },
        error: function () {
            alert('Error al obtener los roles');
        }
    });
}

function busquedaPorFiltro(e) {
    e.preventDefault();
    
    // Verificar los valores de los campos
    console.log($("#sucursal_filtro").val());
    console.log($("#empleado_filtro").val());
    console.log($("#desde").val());
    console.log($("#hasta").val());

    const url = BASE_URL + "Evidence/filtrar";
    const frm = $("#filtros");
    console.log(frm.serialize());
    $.ajax({
        url: url,
        type: "POST",
        data: frm.serialize(), 
        success: function (response) {
            console.log(response);
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
        }
    });
}

