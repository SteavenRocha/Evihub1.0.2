if (currentPath.includes("/Evidence")) {
    document.addEventListener('DOMContentLoaded', loadRecentFiles);
    // Llama a la función deseada
    const file = document.getElementById('file');

    $(document).ready(function () {
        // Detectar clic en el botón "Agregar"
        $("#btnUpload").click(function () {
            btnNuevoArchivo();
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
            if (data.length === 0) {
                console.log("No hay archivos recientes.");
            } else {
                const container = document.getElementById('cards-container');
                container.innerHTML = '';

                data.forEach(file => {
                    console.log(file);

                    const card = document.createElement('div');
                    card.classList.add('card');

                    // Usamos la función timeAgo para formatear la fecha
                    const formattedDate = timeAgo(file.fecha_subida);

                    // Convertir el tamaño a KB
                    const sizeInKB = (file.size / 1024).toFixed(2); // Convertir a KB y redondear a 2 decimales

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
    const diffInSeconds = Math.floor((now - new Date(timestamp)) / 1000);

    const conditions = [
        { seconds: 60, label: 'segundo' },
        { seconds: 3600, label: 'minuto' },
        { seconds: 86400, label: 'hora' },
        { seconds: 2592000, label: 'día' },
        { seconds: 31536000, label: 'mes' },
        { seconds: 315360000, label: 'año' }
    ];

    for (let i = conditions.length - 1; i >= 0; i--) {
        const { seconds, label } = conditions[i];
        const timeDiff = Math.floor(diffInSeconds / seconds);

        if (timeDiff >= 1) {
            return `hace ${timeDiff} ${label}${timeDiff > 1 ? 's' : ''}`;
        }
    }

    return 'Justo ahora';
}
