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
                    <span>Empleados</span>
                    <div class="form-logo-glow"></div>
                </div>

                <div class="group">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                        <g>
                            <path
                                d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                        </g>
                    </svg>

                    <input
                        id="filtroEmpleados"
                        class="input"
                        type="search"
                        placeholder="Busca empleado..."
                        name="searchbar" />
                </div>

                <button title="Agregar" id="btn_abrir_modal_empleado" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                    </svg>AGREGAR</button>
            </section>

            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Celular</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableEmployees">

                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <!-- Modal -->
    <!-- class="modal-dialog modal-dialog-centered" -->
    <div class="modal fade" id="nuevo_empleado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo_modal_empleado">Agregar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" id="frmEmpleado">
                        <input type="hidden" id="id_empleado" name="id_empleado">

                        <!-- Campo DNI -->
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="dni"
                                    name="dni"
                                    placeholder="Ingrese su DNI"
                                    required
                                    minlength="8"
                                    maxlength="8"
                                    pattern="\d{8}"
                                    aria-label="DNI"
                                    aria-describedby="basic-addon1">
                                <div class="invalid-feedback" id="feedbackDNI">

                                </div>
                            </div>
                        </div>

                        <!-- Campo Nombres -->
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="nombres_empleado" class="form-label">Nombres</label>
                                <input
                                    id="nombres_empleado"
                                    class="form-control"
                                    type="text"
                                    name="nombres_empleado"
                                    placeholder="Ingrese sus nombres"
                                    required
                                    pattern="[A-Za-z\s]+">
                                <div class="invalid-feedback" id="feedbackNombres">

                                </div>
                            </div>
                        </div>

                        <!-- Campo Celular -->
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="celular" class="form-label">N° Celular</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="celular"
                                    name="celular"
                                    placeholder="Ingrese su número de celular"
                                    required
                                    minlength="9"
                                    maxlength="20"
                                    pattern="\d+">
                                <div class="invalid-feedback" id="feedbackCelular">

                                </div>
                            </div>
                        </div>

                        <!-- Campo Sucursal -->
                        <div class="form-group" id="select_sucursal">
                            <label for="id_sucursal_empleado" class="form-label">Sucursal</label>
                            <select
                                id="id_sucursal_empleado"
                                name="id_sucursal_empleado"
                                class="form-select"
                                required
                                aria-label="Seleccione Sucursal">
                                <option value="">Seleccione Sucursal</option>
                            </select>
                            <div class="invalid-feedback" id="feedbackSucursal">

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btn_accion_registrar_empleado" type="button" class="btn btn-primary">Registrar</button>
                    <button id="btn_accion_editar_empleado" type="button" class="btn btn-primary">Modificar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "Views/Modules/footer.php";
?>