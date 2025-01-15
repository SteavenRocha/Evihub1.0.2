<?php
include "Views/Modules/header.php";
include "Views/Modules/sidebar.php";
?>

<!--=============== MAIN ===============-->
<main class="main">

    <div class="contenido" id="main">
        <div class="cuerpo-tabla">
            <section class="table__header">

                <div class="form-titulo">
                    <span>Archivos</span>
                    <div class="form-logo-glow"></div>
                </div>

                <button title="Agregar" id="btnUpload" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                    </svg>AGREGAR</button>
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

                    <div class="filtro-sucursal">
                        <label for="sucursal_filtro">Sucursal:</label>
                        <select name="sucursal_filtro" class="form-select" id="sucursal_filtro" aria-label="Seleccione sucursal">
                            <!-- <option selected>Seleccione sucursal</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option> -->
                        </select>
                    </div>

                    <div class="filtro-empleado">
                        <label for="empleado_filtro">Empleado:</label>
                        <select name="empleado_filtro" class="form-select" id="empleado_filtro" aria-label="Seleccione empleado">
                            <!--  <option selected>Seleccione empleado</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option> -->
                        </select>
                    </div>

                    <div class="fecha-desde">
                        <label for="desde">Desde:</label>
                        <input type="date" id="desde" name="desde">
                    </div>

                    <div class="fecha-hasta">
                        <label for="hasta">Hasta:</label>
                        <input type="date" id="hasta" name="hasta">
                    </div>

                    <div class="contenedor-btn-filter" title="Aplicar filtros">
                        <button type="button" class="filter-btn" id="filter-btn">
                            <span class="bar bar1"></span>
                            <span class="bar bar2"></span>
                            <span class="bar bar1"></span>
                        </button>
                    </div>
                </form>

                <div class="sub-titulo">
                    Archivos recientes
                </div>

                <div id="cards-container">

                </div>
            </section>
        </div>
    </div>

    <!-- Modal -->
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
                            <span>Click to upload image</span>
                        </div>
                        <input type="file" id="file" name="file">
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "Views/Modules/footer.php";
?>