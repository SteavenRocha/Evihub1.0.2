if (currentPath.includes("/Evidence")) {

    document.addEventListener('DOMContentLoaded', function () {

        if (idRol === 1) {
            loadRecentFiles();
            listarFiltros();
        } else if (idRol === 2) {
            loadRecentFilesEmpleado(idEmpleado);
            listarFiltros();
        }

    });

    // Llama a la función deseada
    const file = document.getElementById('file');

    $(document).ready(function () {

        $("#btnUpload").click(function () {
            btnNuevoArchivo();
        });

        $("#filter-btn").click(function (e) {
            if (idRol === 1) {
                busquedaPorFiltro(e);
            } else if (idRol === 2 && idEmpleado !== null) {
                busquedaFiltroEmpleado(idEmpleado);
            }
        });

        if (idRol === 1) {
            $("#limpiar-btn").click(function () {
                btnLimpiarFiltroYtitulo();
            });
        } else if (idRol === 2) {
            $("#limpiar-btn").click(function () {
                btnLimpiarTituloYfiltroEmpleado();
            });
        }

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
        const allowedTypes = [
            'image/webp',
            'image/jpeg',
            'image/png',
            'text/csv',
            'application/vnd.ms-excel', // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/pdf'
        ];
        if (!allowedTypes.includes(file.type)) {
            notyf.error("Archivo no soportado. Solo se permiten: WEBP, JPG, PNG, CSV, Excel (.xls, .xlsx) y PDF.");
            e.target.value = '';
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
                        if (idRol === 1) {
                            btnLimpiarFiltro();
                            btnLimpiarTitulo();
                            loadRecentFiles();
                        } else if (idRol === 2) {
                            btnLimpiarFiltroEmpleado();
                            btnLimpiarTituloEmpleado();
                            loadRecentFilesEmpleado(idEmpleado);
                        }
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

    document.getElementById('desde').addEventListener('focus', function () {
        const datetimeInput = document.getElementById('desde');
        if (!datetimeInput.value) {
            const now = new Date();
            now.setHours(0, 0, 0, 0);
            const localDate = now.toLocaleString('sv-SE');
            datetimeInput.value = localDate;
        }
    });

    document.getElementById('hasta').addEventListener('focus', function () {
        const datetimeInput = document.getElementById('hasta');
        if (!datetimeInput.value) {
            const now = new Date();
            now.setHours(23, 59, 0, 0);
            const localDate = now.toLocaleString('sv-SE');
            datetimeInput.value = localDate;
        }
    });

}

function loadCards(data) {
    const sub_titulo = document.getElementById('sub-titulo');
    sub_titulo.style.display = 'none';

    const container = document.getElementById('cards-container');
    container.innerHTML = '';

    const loader = document.getElementById('loader');
    loader.style.display = 'block';

    setTimeout(() => {
        if (data.length === 0) {
            // Mostrar mensaje si no hay archivos
            const noFilesMessage = document.createElement('p');
            noFilesMessage.textContent = "No existen archivos.";
            noFilesMessage.classList.add('no-files-message');
            container.appendChild(noFilesMessage);
        } else {
            data.forEach(file => {

                const formattedDate = timeAgo(file.fecha_subida);

                const sizeInKB = (file.size / 1024).toFixed(2);

                const fileIcons = {
                    'image/jpeg': BASE_URL + 'assets/img/yape-logo.jpg',
                    'image/png': BASE_URL + 'assets/img/yape-logo.jpg',
                    'image/webp': BASE_URL + 'assets/img/yape-logo.jpg',
                    'application/pdf': BASE_URL + 'assets/img/pdf.png',
                    'text/csv': BASE_URL + 'assets/img/excel.png',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': BASE_URL + 'assets/img/excel.png',
                    'application/vnd.ms-excel': BASE_URL + 'assets/img/excel.png',
                };

                const filePreview = fileIcons[file.tipo_archivo] || BASE_URL + 'assets/img/yape-logo.jpg';

                const card = document.createElement('div');
                card.classList.add('card');

                card.innerHTML = `
                    <!-- Sección para la imagen -->
                    <div class="card-img">
                        <img src="${filePreview}" alt="Vista previa del archivo">
                    </div>
    
                    <!-- Sección para el texto -->
                    <div class="card-text">
                      <div class="card-title" onclick="btnDetallesArchivo(${file.id_archivo});">${file.nombre_archivo}</div> <!-- Título del archivo -->
                      <p>${formattedDate}</p> <!-- Fecha de subida -->
                      <p>${sizeInKB} KB</p> <!-- Peso del archivo -->
                    </div>
    
                    <!-- Sección para el icono -->
                    <div class="card-icon" title="Descargar">
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
        loader.style.display = 'none';
        sub_titulo.style.display = 'flex';

        const downloadZipBtn = document.getElementById('download-zip-btn');
        if (downloadZipBtn) {

            downloadZipBtn.replaceWith(downloadZipBtn.cloneNode(true));
            const newDownloadZipBtn = document.getElementById('download-zip-btn');

            newDownloadZipBtn.addEventListener('click', () => {
                generarZip(data);
            });
        }

    }, 300);
}

function generarZip(data) {

    const cantidadArchivos = data.length;

    if (cantidadArchivos == 0) {
        notyf.error('No existen archivos');
    } else {
        // console.log(data);
        Swal.fire({
            title: `¿Estás seguro de querer descargar ${cantidadArchivos} archivo(s)?`,
            text: "Se descargara un archivo .zip",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "Cancelar"

        }).then((result) => {

            if (result.isConfirmed) {

                const url = BASE_URL + "Evidence/zip";

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => {
                        if (response.ok) {
                            return response.blob();
                        } else {
                            throw new Error('No se pudo generar el ZIP.');
                        }
                    })
                    .then(blob => {
                        // Crear un enlace temporal para descargar el archivo ZIP
                        const urlTemp = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = urlTemp;
                        a.download = 'archivos_seleccionados.zip';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    }
}

function loadRecentFiles() {
    const url = BASE_URL + "Evidence/listarRecent";

    fetch(url)
        .then(response => response.json())
        .then(data => {

            loadCards(data.archivos);

            const cantidadElement = document.getElementById('cantidad-archivos');

            if (cantidadElement) {
                const cantidadAMostrar = Math.min(data.total_archivos || 0, 50);
                cantidadElement.textContent = cantidadAMostrar;
            }
        })
        .catch(error => console.error('Error al cargar los archivos:', error));
}

function loadRecentFilesEmpleado(idEmpleado) {
    const url = BASE_URL + "Evidence/listarRecentEmpleado/" + idEmpleado;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            loadCards(data.archivos);

            const cantidadElement = document.getElementById('cantidad-archivos');
            const empleadoElement = document.getElementById('empleado-archivos');

            if (cantidadElement && empleadoElement) {
                const cantidadAMostrar = Math.min(data.total_archivos || 0, 50);
                cantidadElement.textContent = cantidadAMostrar;

                if (data.archivos && data.archivos.length > 0) {
                    const empleadoNombre = data.archivos[0].nombre_completo;
                    empleadoElement.textContent = empleadoNombre;
                } else {
                    empleadoElement.textContent = nombreCompletoUsuario;
                }
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

function busquedaFiltroEmpleado(idEmpleado) {

    const desde = document.getElementById('desde');
    const hasta = document.getElementById('hasta');

    if (!desde.value && hasta.value) {
        notyf.error('Debe completar una fecha "Desde"');
        return;
    }

    if (new Date(desde.value) > new Date(hasta.value)) {
        notyf.error('La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
        return;
    }
    
    const url = BASE_URL + "Evidence/filtrarEmpleado/" + idEmpleado;
    const frm = $("#filtros");

    $.ajax({
        url: url,
        type: "POST",
        data: frm.serialize(),
        success: function (response) {
            try {
                const data = JSON.parse(response);

                const filtrosParrafo = $("#filtros-aplicados");
                const subtituloDiv = $("#filtro-subtitulo");

                if (data.error === "No se aplicaron filtros") {
                    notyf.error('Ingrese al menos un filtro.');
                }
                else if (data.filtro && data.filtro.length > 0) {
                    /* console.log(data); */
                    // Actualiza el subtitulo con la cantidad de archivos
                    subtituloDiv.html(`Se muestran: <span id="cantidad-archivos">${data.total_archivos}</span> archivo(s) - Subido(s) por: <span id="empleado-archivos">${data.filtro[0].nombre_completo}</span> - Filtros aplicados:`);

                    let filtrosAplicadosTexto = "";
                    data.filtros_aplicados.forEach((filtro, index) => {

                        filtrosAplicadosTexto += `<span>${filtro}</span>`;

                        if (index < data.filtros_aplicados.length - 1) {
                            filtrosAplicadosTexto += " ";
                        }
                    });

                    filtrosParrafo.html(filtrosAplicadosTexto);
                    notyf.success('Archivos encontrados');
                    loadCards(data.filtro);
                }
                else {
                    notyf.error('No se encontraron resultados para los filtros aplicados.');
                    /*  subtituloDiv.text("Se muestran: Ultimos archivos subidos"); */
                    /*  filtrosParrafo.html(""); */

                    loadRecentFilesEmpleado(idEmpleado);
                    btnLimpiarFiltroEmpleado();
                }
            } catch (e) {
                console.error("Respuesta no válida:", response);
                notyf.error('Ocurrió un error al procesar la respuesta del servidor');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
            notyf.error('Ocurrió un error en la solicitud. Por favor, intente nuevamente.');
        }
    });
}

function busquedaPorFiltro(e) {
    e.preventDefault();

    const desde = document.getElementById('desde');
    const hasta = document.getElementById('hasta');

    if (!desde.value && hasta.value) {
        notyf.error('Debe completar una fecha "Desde"');
        return;
    }

    if (new Date(desde.value) > new Date(hasta.value)) {
        notyf.error('La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
        return;
    }

    const url = BASE_URL + "Evidence/filtrar";
    const frm = $("#filtros");

    $.ajax({
        url: url,
        type: "POST",
        data: frm.serialize(),
        success: function (response) {
            try {
                const data = JSON.parse(response);
                /* console.log(data); */
                const filtrosParrafo = $("#filtros-aplicados");
                const subtituloDiv = $("#filtro-subtitulo");

                if (data.error === "No se aplicaron filtros") {
                    notyf.error('Ingrese al menos un filtro.');
                }
                else if (data.archivos && data.archivos.length > 0) {

                    if (idRol === 1) {
                        // Actualiza el subtitulo con la cantidad de archivos
                        subtituloDiv.html(`Se muestran: <span id="cantidad-archivos">${data.total_archivos}</span> archivo(s) - Filtros aplicados:`);

                        let filtrosAplicadosTexto = "";
                        data.filtros_aplicados.forEach((archivos, index) => {
                            if (archivos.includes("Sucursal:")) {
                                const nombreSucursal = data.archivos[0].nombre_sucursal;
                                filtrosAplicadosTexto += `<span>${nombreSucursal}</span>`;
                            } else if (archivos.includes("Empleado:")) {
                                const nombreEmpleado = data.archivos[0].nombre_completo;
                                filtrosAplicadosTexto += `<span>${nombreEmpleado}</span>`;
                            } else {
                                filtrosAplicadosTexto += `<span>${archivos}</span>`;
                            }

                            if (index < data.filtros_aplicados.length - 1) {
                                filtrosAplicadosTexto += " ";
                            }
                        });
                        loadCards(data.archivos);

                        filtrosParrafo.html(filtrosAplicadosTexto);
                        notyf.success('Archivos encontrados');
                    } else if (idRol === 2) {
                        busquedaFiltroEmpleado(idEmpleado);
                    }
                }
                else {
                    notyf.error('No se encontraron resultados para los filtros aplicados.');
                    /*  subtituloDiv.html('Se muestran: <span id="cantidad-archivos">0</span> archivo(s)');
                     filtrosParrafo.html(""); */
                    if (idRol === 1) {
                        loadRecentFiles();
                    } else if (idRol === 2) {
                        loadRecentFilesEmpleado(idEmpleado);
                    }
                    btnLimpiarFiltro();
                }
            } catch (e) {
                console.error("Respuesta no válida:", response);
                notyf.error('Ocurrió un error al procesar la respuesta del servidor');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
            notyf.error('Ocurrió un error en la solicitud. Por favor, intente nuevamente.');
        }
    });
}

function btnDetallesArchivo(id) {
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
                    const filePath = BASE_URL + `assets/${data.ruta}`;

                    // Mostrar contenido dinámico según el tipo de archivo
                    const previewContainer = document.getElementById('previewContainer');
                    previewContainer.innerHTML = '';

                    if (data.tipo_archivo === 'application/pdf') {
                        const iframe = document.createElement('iframe');
                        iframe.src = filePath;
                        previewContainer.appendChild(iframe);
                    } else if (data.tipo_archivo.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = filePath;
                        previewContainer.appendChild(img);
                    } else {
                        previewContainer.textContent = 'Vista previa no disponible para este tipo de archivo.';
                    }

                    // Mostrar otros detalles del archivo
                    $("#nombreArchivo").text(data.nombre_archivo);

                    // Cambiar 'tipo_archivo' a 'Aplicación Excel' si es Excel
                    const tipoArchivo = data.tipo_archivo === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || data.tipo_archivo === 'application/vnd.ms-excel'
                        ? 'aplication/excel'
                        : data.tipo_archivo;

                    $("#tipoArchivo").text(tipoArchivo);  // Mostrar el tipo de archivo modificado

                    $("#fechaSubida").text(data.fecha_subida);
                    $("#tamanoArchivo").text(data.size + ' KB');
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

function btnLimpiarFiltroYtitulo() {
    const filtrosParrafo = $("#filtros-aplicados");
    const subtituloDiv = $("#filtro-subtitulo");

    subtituloDiv.html('Se muestran: <span id="cantidad-archivos">0</span> archivo(s)');
    filtrosParrafo.html("");

    $("#sucursal_filtro").val(null).trigger('change');
    $("#empleado_filtro").val(null).trigger('change');
    $("#desde").val("");
    $("#hasta").val("");

    loadRecentFiles();
}

function btnLimpiarTituloYfiltroEmpleado() {
    const filtrosParrafo = $("#filtros-aplicados");
    const subtituloDiv = $("#filtro-subtitulo");

    subtituloDiv.html(`Se muestran: <span id="cantidad-archivos"></span> archivo(s) - Subido(s) por: <span id="empleado-archivos"></span>`);
    filtrosParrafo.html("");

    $("#desde").val("");
    $("#hasta").val("");

    loadRecentFilesEmpleado(idEmpleado);
}

function btnLimpiarFiltro() {
    $("#sucursal_filtro").val(null).trigger('change');
    $("#empleado_filtro").val(null).trigger('change');
    $("#desde").val("");
    $("#hasta").val("");
}

function btnLimpiarFiltroEmpleado() {
    $("#desde").val("");
    $("#hasta").val("");
}

function btnLimpiarTitulo() {
    const filtrosParrafo = $("#filtros-aplicados");
    const subtituloDiv = $("#filtro-subtitulo");

    subtituloDiv.html(`Se muestran: <span id="cantidad-archivos"></span> archivo(s)`);
    filtrosParrafo.html("");
};

function btnLimpiarTituloEmpleado() {
    const filtrosParrafo = $("#filtros-aplicados");
    const subtituloDiv = $("#filtro-subtitulo");

    subtituloDiv.html(`Se muestran: <span id="cantidad-archivos"></span> archivo(s) - Subido(s) por: <span id="empleado-archivos"></span>`);
    filtrosParrafo.html("");
};