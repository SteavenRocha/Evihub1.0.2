<?php
include "Views/Modules/header.php";
include "Views/Modules/sidebar.php";
?>

<!--=============== MAIN ===============-->
<main class="main">

    <div class="contenido" id="main">
        <section class="table__header">
            <div class="button-titulo">
                <div class="form-titulo">
                    <span>Archivos</span>
                    <div class="form-logo-glow"></div>
                </div>

                <button title="Agregar" id="btnUpload" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0" />
                    </svg></button>
            </div>
        </section>

        <section class="table__body__card">

            <!-- <div class="table__sub_header">
                    <h3>
                        <span class="label">EMPLEADO:</span>
                        <span class="data"><?php echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'Usuario'; ?></span>
                    </h3>
                    <h3 class="mx-5">
                        <span class="label">SUCURSAL:</span>
                        <span class="data"><?php echo isset($_SESSION['nombre_sucursal']) ? $_SESSION['nombre_sucursal'] : 'Sucursal'; ?></span>
                    </h3>
                </div> -->

            <form method="post" class="filtros" id="filtros">

                <label class="titulo-filtro">Filtro de busqueda</label>

                <div class="filtro-sucursal <?php echo (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) ? 'bloqueado' : ''; ?>">
                    <label for="sucursal_filtro">Sucursal:</label>
                    <select
                        name="sucursal_filtro"
                        class="form-select"
                        id="sucursal_filtro"
                        aria-label="Seleccione sucursal"
                        <?php echo (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) ? 'disabled title="Opción deshabilitada"' : ''; ?>>
                        <!-- Opciones dinámicas -->
                    </select>
                </div>

                <div class="filtro-empleado <?php echo (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) ? 'bloqueado' : ''; ?>">
                    <label for="empleado_filtro">Empleado:</label>
                    <select
                        name="empleado_filtro"
                        class="form-select"
                        id="empleado_filtro"
                        aria-label="Seleccione empleado"
                        <?php echo (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) ? 'disabled title="Opción deshabilitada"' : ''; ?>>
                        <!-- Opciones dinámicas -->
                    </select>
                </div>

                <div class="fecha-desde">
                    <label for="desde">Desde:</label>
                    <input type="datetime-local" id="desde" name="desde">
                </div>

                <div class="fecha-hasta">
                    <label for="hasta">Hasta:</label>
                    <input type="datetime-local" id="hasta" name="hasta">
                </div>

                <div class="contenedor-btn-filter">
                    <button type="button" class="filter-btn" id="filter-btn" title="Aplicar filtros">
                        <span class="bar bar1"></span>
                        <span class="bar bar2"></span>
                        <span class="bar bar1"></span>
                    </button>

                    <button type="button" class="limpiar-btn" id="limpiar-btn" title="Limpiar filtros">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-brush" viewBox="0 0 16 16">
                            <path d="M15.825.14a.5.5 0 0 1 .034.705l-9.01 9.89c-.288.318-.352.75-.293 1.132.057.377.213.784.465 1.036a2.5 2.5 0 1 1-3.536 0c.252-.252.659-.408 1.036-.465.382-.059.814.005 1.132.293l9.89-9.01a.5.5 0 0 1 .705.034z" />
                        </svg>
                    </button>

                    <button type="button" class="descargar-btn" id="download-zip-btn" title="Descargar archivos">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1z" />
                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708z" />
                        </svg>
                    </button>
                </div>

            </form>

            <div class="loader" id="loader">
            </div>

            <div class="sub-titulo" id="sub-titulo">
                <p class="filtro-subtitulo" id="filtro-subtitulo">
                    <?php if ($_SESSION['id_rol'] == 1): ?>
                        Se muestran: <span id="cantidad-archivos"></span> archivo(s)
                    <?php else: ?>
                        Se muestran: <span id="cantidad-archivos"></span> archivo(s) - Subido(s) por: <span id="empleado-archivos"></span>
                    <?php endif; ?>
                </p>
                <p class="filtros-aplicados" id="filtros-aplicados"></p>
            </div>

            <div id="cards-container">

            </div>

        </section>
    </div>

    <!-- Modal subir archivo -->
    <div class="modal fade" id="upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">

                    <label class="custum-file-upload" for="file">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                                <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="text">
                            <span>Subir archivo o tomar foto</span>
                        </div>
                        <input type="file" id="file" name="file" accept=".webp,.jpg,.jpeg,.png,.csv,.xlsx,.xls,.pdf">

                    </label>

                    <!--  <label class="custum-file-upload">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                                <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                            </svg>
                        </div>
                        <div class="text">
                            <span>Tomar foto</span>
                        </div>
                        <input type="button" id="openCamera">
                    </label> -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal foto-->
    <!--     <div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tomar foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="foto">
                        <video id="camera" autoplay playsinline></video>

                        <div id="takePhoto" class="button-foto"></div>

                        <canvas id="photoCanvas" class="mostrar-foto"></canvas>
                    </div>
                </div>
                <div class="modal-footer acciones-foto" id="acciones-foto">

                    <button id="again" type="button" class="button-add again"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>Volver a tomar foto</button>

                    <button id="savePhoto" type="button" class="button-add save"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
                            <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z" />
                            <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z" />
                        </svg>Guardar foto</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal detalles archivo-->
    <div class="modal fade" id="modalDetallesArchivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles del archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center modal-body-details">
                    <div id="previewContainer" class="previewContainer">
                        <!-- Aquí se cargará dinámicamente la vista previa -->
                    </div>

                    <div class="column">
                        <div class="details">
                            <table class="table table-bordered tabla-detalles-archivo">
                                <tbody>
                                    <tr>
                                        <th>Nombre del archivo:</th>
                                        <td id="nombreArchivo"></td>
                                    </tr>
                                    <tr>
                                        <th>Tipo archivo:</th>
                                        <td id="tipoArchivo"></td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de subida:</th>
                                        <td id="fechaSubida"></td>
                                    </tr>
                                    <tr>
                                        <th>Tamaño del archivo:</th>
                                        <td id="tamanoArchivo"></td>
                                    </tr>
                                    <tr>
                                        <th>DNI del empleado:</th>
                                        <td id="dniEmpleado"></td>
                                    </tr>
                                    <tr>
                                        <th>Empleado:</th>
                                        <td id="nombresEmpleado"></td>
                                    </tr>
                                    <tr>
                                        <th>Nombre de la sucursal:</th>
                                        <td id="nombreSucursal"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="descargar-details" id="descargar-details">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php
include "Views/Modules/footer.php";
?>