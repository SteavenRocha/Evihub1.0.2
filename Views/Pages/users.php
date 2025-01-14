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
                    <span>Usuarios</span>
                    <div class="form-logo-glow"></div>
                </div>

                <button title="Agregar" id="btn_abrir_modal" type="button" class="button-add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                    </svg>AGREGAR</button>
            </section>

            <section class="table__body">
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
            </section>
        </div>
    </div>

    <!-- Modal -->
    <!-- class="modal-dialog modal-dialog-centered" -->
    <div class="modal fade" id="nuevo_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog">
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
                            <select id="id_empleado" name="id_empleado" class="form-select" aria-label="Seleccione Empleado">
                            </select>
                        </div>

                        <div class="form-group" id="select_empleado_editar">
                            <div class="mb-3">
                                <label for="empleado_editar" class="form-label">Empleado</label>
                                <input id="empleado_editar" class="form-control" type="text" name="empleado_editar" placeholder="Usuario">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_rol" class="form-label">Rol</label>
                            <select id="id_rol" name="id_rol" class="form-select" aria-label="Seleccione Rol">

                            </select>
                        </div>

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario">
                            </div>
                        </div>
                        <div class="row" id="claves">
                            <div class="col md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="contraseña" class="form-label">Contraseña</label>
                                        <input id="contraseña" class="form-control" type="password" name="contraseña" placeholder="Contraseña">
                                    </div>
                                </div>
                            </div>
                            <div class="col md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                                        <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar contraseña">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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