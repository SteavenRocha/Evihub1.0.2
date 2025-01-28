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
                    <span>Sucursales</span>
                    <div class="form-logo-glow"></div>
                </div>

                <button title="Agregar" id="btn_abrir_modal_sucursal" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
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
                    id="filtroSucursales"
                    class="input"
                    type="search"
                    placeholder="Buscar sucursal..."
                    name="searchbar" />
            </div>
        </section>

        <section class="table__body">
            <div class="cuerpo">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Sucursal</th>
                            <th>Estado</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableSucursal">

                    </tbody>
                </table>
            </div>

        </section>
    </div>

    <!-- Modal -->
    <!-- class="modal-dialog modal-dialog-centered" -->
    <div class="modal fade" id="nueva_sucursal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo_modal_sucursal">Agregar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frmSucursal" class="needs-validation" novalidate>
                        <input type="hidden" id="id_sucursal" name="id_sucursal"></input>

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="nombre_sucursal" class="form-label">Nombre de la sucursal</label>
                                <input
                                    id="nombre_sucursal"
                                    class="form-control"
                                    type="text"
                                    name="nombre_sucursal"
                                    placeholder="Ingrese nombre de la sucursal"
                                    required
                                    pattern="[A-Za-z\s]+">
                                <div class="invalid-feedback" id="feedbackNombreSucursal">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn_accion_registrar_sucursal" type="button" class="btn btn-primary">Registrar</button>
                    <button id="btn_accion_editar_sucursal" type="button" class="btn btn-primary">Modificar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "Views/Modules/footer.php";
?>