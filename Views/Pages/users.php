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
                    <span>Usuarios</span>
                    <div class="form-logo-glow"></div>
                </div>

                <button title="Agregar" id="btn_abrir_modal" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0" />
                    </svg></button>

            </div>

            <div class="group">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                    <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                    </g>
                </svg>

                <input
                    id="filtroUsuarios"
                    class="input"
                    type="search"
                    placeholder="Buscar usuario..."
                    name="searchbar" />
            </div>
        </section>

        <section class="table__body">
            <div class="cuerpo">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empleado</th>
                            <th>Rol</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableUsers">

                    </tbody>
                </table>
            </div>

            <div class="paginacion">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-right" id="pagination">
                        
                    </ul>
                </nav>
            </div>
        </section>

    </div>

    <script>
    const rowsPerPage = 5;
    const rows = document.querySelectorAll("table tbody tr");
    const pagination = document.querySelector(".pagination");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    let currentPage = 1;

    // Función para mostrar las filas de la página actual
    function showPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        // Actualizar el estado de los botones de paginación
        pagination.querySelectorAll(".page-item").forEach((item, index) => {
            if (index === 0) {
                item.classList.toggle("disabled", page === 1);
            } else if (index === pagination.children.length - 1) {
                item.classList.toggle("disabled", page === totalPages);
            } else {
                item.classList.toggle("active", index === page);
            }
        });
    }

    // Mostrar la página inicial
    showPage(currentPage);

    // Control de botones de paginación
    pagination.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("page-link")) {
            const page = parseInt(target.textContent);
            if (isNaN(page)) {
                if (target.textContent === "Anterior" && currentPage > 1) {
                    currentPage--;
                } else if (target.textContent === "Siguiente" && currentPage < totalPages) {
                    currentPage++;
                }
            } else {
                currentPage = page;
            }
            showPage(currentPage);
        }
    });
</script>


    <!-- Modal -->
    <!-- class="modal-dialog modal-dialog-centered" -->
    <div class="modal fade" id="nuevo_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo_modal">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frmUsuario">
                        <input type="hidden" id="id" name="id"></input>

                        <div class="form-group" id="select_empleado">
                            <label for="id_empleado" class="form-label">Empleado</label>
                            <select id="id_empleado" name="id_empleado" class="form-select" required aria-label="Seleccione Empleado">
                                <option value="">Seleccione Empleado</option>
                            </select>
                            <div class="invalid-feedback" id="feedbackEmpleado"><!-- Por favor, seleccione un empleado -->.</div>
                        </div>

                        <div class="form-group" id="select_empleado_editar">
                            <div class="mb-3">
                                <label for="empleado_editar" class="form-label">Empleado</label>
                                <input id="empleado_editar" class="form-control" type="text" name="empleado_editar" placeholder="Empleado" disabled />
                                <div class="invalid-feedback" id="feedbackEmpleadoEditar"><!-- Por favor, ingrese el nombre del empleado -->.</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_rol" class="form-label">Rol</label>
                            <select id="id_rol" name="id_rol" class="form-select" required aria-label="Seleccione Rol">
                                <option value="">Seleccione Rol</option>
                            </select>
                            <div class="invalid-feedback" id="feedbackRol"><!-- Por favor, seleccione un rol -->.</div>
                        </div>

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input id="usuario" class="form-control" type="email" name="usuario" placeholder="Usuario" required />
                                <div class="invalid-feedback" id="feedbackUsuario"><!-- Por favor, ingrese un usuario válido -->.</div>
                            </div>
                        </div>

                        <div class="row" id="claves">
                            <div class="col md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="contraseña" class="form-label">Contraseña</label>
                                        <input id="contraseña" class="form-control" type="password" name="contraseña" placeholder="Contraseña" required />
                                        <div class="invalid-feedback" id="feedbackContraseña"><!-- Por favor, ingrese una contraseña. --></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                                        <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar contraseña" required />
                                        <div class="invalid-feedback" id="feedbackConfirmar"><!-- Por favor, confirme su contraseña. --></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn_accion_registrar" type="button" class="btn btn-primary">Registrar</button>
                    <button id="btn_accion_editar" type="button" class="btn btn-primary">Modificar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "Views/Modules/footer.php";
?>