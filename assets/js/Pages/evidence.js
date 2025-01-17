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

        $("#empleado_filtro").select2();

        $("#sucursal_filtro").select2();
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

        // Validar el tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'text/csv'];
        if (!allowedTypes.includes(file.type)) {
            notyf.error("Archivo no soportado. Solo se permiten imágenes (JPG, PNG, GIF) y CSV.");
            e.target.value = ''; // Limpiar el campo de entrada de archivo
            return;
        }

        const formData = new FormData();
        formData.append("file", file);

        const url = BASE_URL + "Evidence/upload";

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
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

function loadCards(data) {
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
                <!-- Sección para la imagen -->
                <div class="card-img">
                  <img src="assets/img/yape-logo.jpg" alt="no hay imagen disponible">
                </div>

                <!-- Sección para el texto -->
                <div class="card-text">
                  <div class="card-title" onclick="btnDetallesArchivo(${file.id_archivo});">${file.nombre_archivo}</div> <!-- Título del archivo -->
                  <p>${formattedDate}</p> <!-- Fecha de subida -->
                  <p>${sizeInKB} KB</p> <!-- Peso de la imagen -->
                </div>

                <!-- Sección para el icono -->
                <div class="card-icon">
                    <a href="${BASE_URL}assets/${file.ruta}" download="${file.nombre_archivo}" target="_blank"> <!-- Envolvemos el SVG en un enlace -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1z" />
                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708z" />
                        </svg>
                    </a>
                </div>
            `;

            container.appendChild(card);
        });
    }
}

function loadRecentFiles() {
    const url = BASE_URL + "Evidence/listarRecent";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            loadCards(data);
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
    $.ajax({
        url: BASE_URL + 'Evidence/listarSucursales',
        type: 'POST',
        dataType: 'json',
        success: function (sucursales) {
            const selectSucursal = $("#sucursal_filtro");
            selectSucursal.empty();
            selectSucursal.append('<option value="">Seleccione sucursal</option>');

            sucursales.forEach(function (sucursal) {
                selectSucursal.append(
                    `<option value="${sucursal.id_sucursal}">${sucursal.nombre_sucursal}</option>`
                );
            });

            listarEmpleados(sucursales);

            selectSucursal.change(function () {
                const sucursalSeleccionada = $(this).val();
                if (sucursalSeleccionada === "") {
                    listarEmpleados(sucursales);
                } else {
                    listarEmpleados(sucursales, sucursalSeleccionada);
                }
            });
        },
        error: function () {
            alert('Error al obtener las sucursales');
        }
    });
}

function listarEmpleados(sucursales, sucursalSeleccionada = null) {
    $.ajax({
        url: BASE_URL + 'Evidence/listarEmpleados',
        type: 'POST',
        dataType: 'json',
        success: function (empleados) {
            const selectEmpleado = $("#empleado_filtro");
            selectEmpleado.empty();
            selectEmpleado.append('<option value="">Seleccione empleado</option>');

            const empleadosPorSucursal = {};

            empleados.forEach(function (empleado) {
                if (!empleadosPorSucursal[empleado.id_sucursal]) {
                    empleadosPorSucursal[empleado.id_sucursal] = [];
                }
                empleadosPorSucursal[empleado.id_sucursal].push(empleado);
            });

            if (sucursalSeleccionada === null || sucursalSeleccionada === "") {
                sucursales.forEach(function (sucursal) {
                    if (empleadosPorSucursal[sucursal.id_sucursal]) {
                        const grupo = $('<optgroup>').attr('label', sucursal.nombre_sucursal);
                        empleadosPorSucursal[sucursal.id_sucursal].forEach(function (empleado) {
                            grupo.append(
                                `<option value="${empleado.id_empleado}">${empleado.nombre_completo}</option>`
                            );
                        });
                        selectEmpleado.append(grupo);
                    }
                });
            } else {
                const sucursal = sucursales.find(s => s.id_sucursal == sucursalSeleccionada);
                if (empleadosPorSucursal[sucursalSeleccionada]) {
                    const grupo = $('<optgroup>').attr('label', sucursal.nombre_sucursal);
                    empleadosPorSucursal[sucursalSeleccionada].forEach(function (empleado) {
                        grupo.append(
                            `<option value="${empleado.id_empleado}">${empleado.nombre_completo}</option>`
                        );
                    });
                    selectEmpleado.append(grupo);
                }
            }
        },
        error: function () {
            alert('Error al obtener los empleados');
        }
    });
}

function busquedaPorFiltro(e) {
    e.preventDefault();

    const url = BASE_URL + "Evidence/filtrar";
    const frm = $("#filtros");
    /*  console.log(frm.serialize()); */
    $.ajax({
        url: url,
        type: "POST",
        data: frm.serialize(),
        success: function (response) {
            try {
                const data = JSON.parse(response);
                if (data === "datos vacios") {
                    notyf.error('Filtros necesarios');
                } else {
                    loadCards(data);
                }
            } catch (e) {
                console.error("Respuesta no válida:", response);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
        }
    });
}

function btnDetallesArchivo(id) {
    /*     console.log(id); */
    $("#modalDetallesArchivo").modal("show");

    const url = BASE_URL + "Evidence/detalles/" + id;

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            try {
                if (response.length > 0) {
                    const data = response[0];

                    const imagePath = BASE_URL + `assets/${data.ruta}`;
                    document.getElementById('imageModal').src = imagePath;

                    $("#nombreArchivo").text(data.nombre_archivo);
                    $("#tipoArchivo").text(data.tipo_archivo);
                    $("#fechaSubida").text(data.fecha_subida);
                    $("#tamanoArchivo").text(data.size);
                    $("#dniEmpleado").text(data.dni);
                    $("#nombresEmpleado").text(data.nombre_completo);
                    $("#nombreSucursal").text(data.nombre_sucursal);
                } else {
                    console.warn("La respuesta está vacía.");
                }
            } catch (e) {
                console.error("Error al procesar los datos:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr.responseText + " - " + error);
        }
    });
}

