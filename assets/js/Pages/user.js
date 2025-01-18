// Obtener la tabla
const tableBody = document.getElementById('tableUsers');

// Función para cargar los datos de usuarios
function loadUsers() {
    // URL que devuelve los datos
    const url = BASE_URL + "User/listar";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = "";

            // Verificar si hay datos en el array `data`
            if (data.length === 0) {
                const emptyRow = `
                    <tr>
                        <td colspan="7" class="tabla-vacia">
                            No existen usuarios registrados
                        </td>
                    </tr>
                `;
                tableBody.innerHTML = emptyRow;
            } else {
                // Si hay datos, agregar las filas normalmente
                data.forEach(user => {
                    const statusClass = user.estado_usuario == 1 ? 'estado-activo' : 'estado-inactivo';
                    const row =
                        `
                    <tr>
                    <td>${user.id_usuario}</td>
                    <td>${user.nombre_completo}</td>
                    <td>${user.nombre_rol}</td>
                    <td>${user.usuario}</td>
                     <td>
                        <span class="estado-usuario ${statusClass}">
                            ${user.estado_usuario == 1 ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td>${user.fecha_registro}</td>
                    <td>
                        <div class="acciones">
                            <!-- Mostrar texto de Administrador si el rol es 1 (Administrador) -->
                            ${user.id_rol == 1 ? `
                                <span class="estado-usuario estado-admin">
                                    Admin
                                </span>
                            ` : `
                                <!-- Botón Editar -->
                                <button class="btn-accion" onclick="btnEditarUser(${user.id_usuario});">
                                    <span class="svgIcon-btn-editar">
                                        <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </span>
                                </button>
                                
                                <!-- Botón Deshabilitar: Solo visible si el usuario está activo -->
                                ${user.estado_usuario == 1 ? `
                                    <button class="btn-accion" title="Deshabilitar" onclick="btnDeshabilitarUser(${user.id_usuario});">
                                        <span class="svgIcon-btn-eliminar">
                                            <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                            </svg>
                                        </span>
                                    </button>
                                ` : ''}

                                <!-- Botón Habilitar: Solo visible si el usuario está inactivo -->
                                ${user.estado_usuario == 0 ? `
                                    <button class="btn-accion" title="Habilitar" onclick="btnHabilitarUser(${user.id_usuario});">
                                        <span class="svgIcon-btn-habilitar">
                                            <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                            </svg>                          
                                        </span>
                                    </button>
                                ` : ''}
                            `}
                        </div>
                    </td>
                    </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }
        })
        .catch(error => console.error('Error al cargar los usuarios:', error));
}

const currentPath = window.location.pathname;
if (currentPath.includes("/User")) {
    // Llama a la función deseada
    document.addEventListener('DOMContentLoaded', loadUsers);


    document.getElementById('filtroUsuarios').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tableUsers tr');
    
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            if (textoFila.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
}

// Función para validar select genéricos
function handleSelectValidation(event, feedbackId, errorMessage) {
    const selectElement = event.target || event;
    const feedback = document.getElementById(feedbackId);

    if (selectElement.value === "") {
        feedback.textContent = errorMessage;
        selectElement.classList.add("is-invalid");
        selectElement.classList.remove("is-valid");
    } else {
        feedback.textContent = "";
        selectElement.classList.remove("is-invalid");
        selectElement.classList.add("is-valid");
    }
}

// Función para validar inputs genéricos
function handleInputValidation(event, feedbackId, minLength, errorMessage) {
    const inputElement = event.target;
    const feedback = document.getElementById(feedbackId);
    const value = inputElement.value.trim();

    if (value.length < minLength) {
        feedback.textContent = errorMessage;
        inputElement.classList.add("is-invalid");
        inputElement.classList.remove("is-valid");
    } else {
        feedback.textContent = "";
        inputElement.classList.remove("is-invalid");
        inputElement.classList.add("is-valid");
    }
}

// Función para validar el usuario (correo electrónico)
function handleUsuarioValidation(event, feedbackId) {
    const inputElement = event.target;
    const feedback = document.getElementById(feedbackId);
    const value = inputElement.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex para validar formato de email

    if (value === "") {
        feedback.textContent = "Por favor ingresar Usuario.";
        inputElement.classList.add("is-invalid");
        inputElement.classList.remove("is-valid");
    } else if (!emailRegex.test(value)) {
        feedback.textContent = "Por favor, utilice un correo con formato válido.";
        inputElement.classList.add("is-invalid");
        inputElement.classList.remove("is-valid");
    } else {
        feedback.textContent = "";
        inputElement.classList.remove("is-invalid");
        inputElement.classList.add("is-valid");
    }
}

// Validación específica para contraseñas
function handlePasswordValidation(event) {
    const contraseñaInput = event.target;
    const contraseña = document.getElementById("contraseña").value;
    const feedbackContraseña = document.getElementById("feedbackContraseña");

    if (contraseña.length === 0) {
        feedbackContraseña.textContent = "Ingresar Contraseña";
        contraseñaInput.classList.add("is-invalid");
        contraseñaInput.classList.remove("is-valid");
    } else if (contraseña.length < 6) {
        feedbackContraseña.textContent = "La contraseña debe tener como mínimo 6 dígitos";
        contraseñaInput.classList.add("is-invalid");
        contraseñaInput.classList.remove("is-valid");
    } else {
        feedbackContraseña.textContent = "";
        contraseñaInput.classList.remove("is-invalid");
        contraseñaInput.classList.add("is-valid");
    }
}

// Validación para confirmación de contraseñas
function handlePasswordConfirmationValidation(event) {
    const confirmarInput = event.target;
    const contraseña = document.getElementById("contraseña").value;
    const confirmar = document.getElementById("confirmar").value;
    const feedbackConfirmar = document.getElementById("feedbackConfirmar");

    if (confirmar.length === 0) {
        feedbackConfirmar.textContent = "Confirmar Contraseña";
        confirmarInput.classList.add("is-invalid");
        confirmarInput.classList.remove("is-valid");
    } else if (contraseña !== confirmar) {
        feedbackConfirmar.textContent = "Las contraseñas no coinciden";
        confirmarInput.classList.add("is-invalid");
        confirmarInput.classList.remove("is-valid");
    } else {
        feedbackConfirmar.textContent = "";
        confirmarInput.classList.remove("is-invalid");
        confirmarInput.classList.add("is-valid");
    }
}

// Función para ejecutar validaciones al intentar registrar o editar
function validacionUsuario() {
    const empleadoSelect = document.getElementById("id_empleado");
    const rolSelect = document.getElementById("id_rol");
    const usuarioInput = document.getElementById("usuario");
    const contraseñaInput = document.getElementById("contraseña");
    const confirmarInput = document.getElementById("confirmar");
    const btnRegistrar = document.getElementById("btn_accion_registrar");
    const btnEditar = document.getElementById("btn_accion_editar");

    // Validación en tiempo real
    empleadoSelect.addEventListener("change", (e) => handleSelectValidation(e, "feedbackEmpleado", "Por favor, seleccione un empleado."));
    rolSelect.addEventListener("change", (e) => handleSelectValidation(e, "feedbackRol", "Por favor, seleccione un rol."));

    usuarioInput.addEventListener("input", (e) => handleUsuarioValidation(e, "feedbackUsuario"));

    // Botón registrar
    btnRegistrar.addEventListener("click", (e) => {
        e.preventDefault();

        // Validar empleado
        handleSelectValidation(empleadoSelect, "feedbackEmpleado", "Por favor, seleccione un empleado.");

        // Validar rol
        handleSelectValidation(rolSelect, "feedbackRol", "Por favor, seleccione un rol.");

        // Validar usuario
        handleUsuarioValidation({ target: usuarioInput }, "feedbackUsuario");

        // Validar contraseñas
        handlePasswordValidation({ target: contraseñaInput });
        handlePasswordConfirmationValidation({ target: confirmarInput });
    });

    // Botón editar
    btnEditar.addEventListener("click", (e) => {
        e.preventDefault();

        // Validar empleado
        handleSelectValidation(empleadoSelect, "feedbackEmpleado", "Por favor, seleccione un empleado.");

        // Validar rol
        handleSelectValidation(rolSelect, "feedbackRol", "Por favor, seleccione un rol.");

        // Validar usuario
        handleUsuarioValidation({ target: usuarioInput }, "feedbackUsuario");

        // Validar contraseñas
        handlePasswordValidation({ target: contraseñaInput });
        handlePasswordConfirmationValidation({ target: confirmarInput });
    });

    // Validación específica de contraseñas
    contraseñaInput.addEventListener("input", handlePasswordValidation);
    confirmarInput.addEventListener("input", handlePasswordConfirmationValidation);
}

/* let tableUsers;

// Cargar los usuarios al cargar la página
if (window.location.pathname.includes("User")) {
    document.addEventListener('DOMContentLoaded', function () {
        tableUsers = $('#tableUsers').DataTable({
            language: {
                url: BASE_URL + '/assets/json/spanish.json',
            },
            ajax: {
                url: BASE_URL + "User/listar",
                dataSrc: ''
            },
            columns: [
                {
                    'data': 'id_usuario'
                },
                {
                    'data': 'nombre_completo'
                },
                {
                    'data': 'estado_usuario',
                    render: function (data) {
                        // Determinar el estado del usuario
                        const statusClass = data == 1 ? 'estado-activo' : 'estado-inactivo';
                        return `<span class="estado-usuario ${statusClass}">
                                    ${data == 1 ? 'Activo' : 'Inactivo'}
                                </span>`;
                    }
                },
                {
                    'data': 'nombre_rol'
                },
                {
                    'data': 'usuario'
                },
                {
                    'data': 'fecha_registro'
                },
                {
                    // Agregar las acciones
                    data: null,
                    render: function (data, type, row) {
                        const isAdmin = row.id_rol == 1;
                        const isActive = row.estado_usuario == 1;

                        let actions = `
                        <div class="acciones">
                            ${isAdmin ? `
                                <span class="estado-usuario estado-administrador admin">
                                    Admin
                                </span>
                            ` : `
                                <!-- Botón Editar -->
                                <button class="btn-accion" title="Editar" onclick="btnEditarUser(${row.id_usuario});">
                                    <span class="svgIcon-btn-editar">
                                        <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </span>
                                </button>
                                <!-- Botón Deshabilitar -->
                                ${isActive ? `
                                    <button class="btn-accion" title="Deshabilitar" onclick="btnDeshabilitarUser(${row.id_usuario});">
                                        <span class="svgIcon-btn-eliminar">
                                            <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                            </svg>
                                        </span>
                                    </button>
                                ` : `
                                    <!-- Botón Habilitar -->
                                    <button class="btn-accion" title="Habilitar" onclick="btnHabilitarUser(${row.id_usuario});">
                                        <span class="svgIcon-btn-habilitar">
                                            <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                            </svg>
                                        </span>
                                    </button>
                                `}
                            `}
                        </div>
                        `;
                        return actions;
                    }
                },
            ],
        });
    });
} */

/* MODAL */
$(document).ready(function () {

    $("#btn_abrir_modal").click(function () {
        frmUsuario();
        validacionUsuario();
        limpiarFormulario();
    });

    $("#btn_accion_registrar").click(function (e) {
        registrarUser(e);
    });

    $("#btn_accion_editar").click(function (e) {
        modificarUser(e);
    });
});

// Funcion para abrir el modal y mostrar los empelados y roles por AJAX
function frmUsuario() {

    btnNuevoUser();

    // AJAX para obtener empelados
    $.ajax({
        url: BASE_URL + 'User/empleados',
        type: 'POST',
        dataType: 'json',
        /*  data: {
            
        }, */
        success: function (data) {
            const selectEmpleado = $("#id_empleado");
            selectEmpleado.empty();

            selectEmpleado.append('<option value="">Seleccione empleado</option>');

            data.forEach(function (empleado) {
                selectEmpleado.append(
                    `<option value="${empleado.id_empleado}">${empleado.nombres} ${empleado.ape_paterno} ${empleado.ape_materno}</option>`
                );
            });
        },
        error: function () {
            alert('Error al obtener los empleados');
        }
    });

    // AJAX para obtener roles
    $.ajax({
        url: BASE_URL + 'User/roles',
        type: 'POST',
        dataType: 'json',
        /*  data: {
            
        }, */
        success: function (data) {
            const selectRole = $("#id_rol");
            selectRole.empty();

            selectRole.append('<option value="">Seleccione Rol</option>');

            data.forEach(function (rol) {
                selectRole.append(
                    `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`
                );
            });
        },
        error: function () {
            alert('Error al obtener los roles');
        }
    });
}

// Create an instance of Notyf
const notyf = new Notyf({
    position: {
        x: 'right',
        y: 'top',
    },
    //Para cambair el color de los toast
    /*types: [
        {
            type: 'success',
            background: 'green',
        },
        {
            type: 'error',
            background: 'indianred',
        }
    ]*/
});

/* Funcion para registrar nuevos usuarios */
function registrarUser(e) {
    e.preventDefault();

    const campos = [
        document.getElementById("id_empleado"),
        document.getElementById("id_rol"),
        document.getElementById("usuario"),
        document.getElementById("contraseña"),
        document.getElementById("confirmar"),
    ];

    // Verificar si todos los campos tienen la clase "is-valid"
    const isValid = campos.every(campo => campo.classList.contains("is-valid"));

    if (isValid) {

        const id_empleado = document.getElementById("id_empleado");
        const id_rol = document.getElementById("id_rol");
        const usuario = document.getElementById("usuario");
        const contraseña = document.getElementById("contraseña");
        const confirmar = document.getElementById("confirmar");

        if (id_empleado.value == "" || id_rol.value == "" || usuario.value == "" || contraseña.value == "" || confirmar.value == "") {

            notyf.error('Todos los campos son necesarios');

        } else if (contraseña.value != confirmar.value) {

            notyf.error('Las contraseñas no coinciden');

        } else {
            const url = BASE_URL + "User/registrar";
            const frm = $("#frmUsuario");

            $.ajax({
                url: url,
                type: "POST",
                data: frm.serialize(),  // Serializa los datos del formulario
                success: function (response) {

                    try {
                        const res = JSON.parse(response);

                        if (res == "si") {

                            notyf.success('Usuario registrado con exito');
                            cerrarModal();
                            loadUsers();
                        } else {
                            notyf.error('Usuario ya existe');
                        }
                    } catch (e) {
                        console.log("Respuesta no es JSON: " + response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
                }
            });
        }
    }
}

function modificarUser(e) {
    e.preventDefault();

    const campos = [
        document.getElementById("id_rol"),
        document.getElementById("usuario"),
    ];

    // Verificar si todos los campos tienen la clase "is-valid"
    const isValid = campos.every(campo => campo.classList.contains("is-valid"));

    if (isValid) {

        const id_rol = document.getElementById("id_rol");
        const usuario = document.getElementById("usuario");
        /* const contraseña = document.getElementById("contraseña");
        const confirmar = document.getElementById("confirmar"); */

        if (id_rol.value == "" || usuario.value == "") {

            notyf.error('Todos los campos son necesarios');

        } else {
            const url = BASE_URL + "User/modificar";
            const frm = $("#frmUsuario");

            $.ajax({
                url: url,
                type: "POST",
                data: frm.serialize(),  // Serializa los datos del formulario
                success: function (response) {

                    try {
                        const res = JSON.parse(response);

                        if (res == "modificado") {

                            notyf.success('Usuario modificado con exito');
                            cerrarModal();
                            loadUsers();
                        } else {
                            notyf.error('Usuario ya existe');
                        }
                    } catch (e) {
                        console.log("Respuesta no es JSON: " + response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
                }
            });
        }
    }
}

function cerrarModal() {
    $("#nuevo_usuario").modal("hide");
}

function limpiarFormulario() {
    const frm = document.getElementById("frmUsuario");
    frm.reset();

    const inputs = frm.querySelectorAll(".form-control");
    inputs.forEach(input => {
        input.classList.remove("is-invalid", "is-valid");
    });

    const selects = frm.querySelectorAll("select");
    selects.forEach(select => {
        select.classList.remove("is-invalid", "is-valid");
        select.selectedIndex = 0; 
    });
}

function btnNuevoUser() {
    document.getElementById("titulo_modal").innerHTML = "Agregar Nuevo Usuario";
    //document.getElementById("btn_accion").innerHTML = "Registrar";
    document.getElementById("btn_accion_registrar").classList.remove("d-none");
    document.getElementById("btn_accion_editar").classList.add("d-none");
    document.getElementById("select_empleado").classList.remove("d-none");
    document.getElementById("select_empleado_editar").classList.add("d-none");
    document.getElementById("claves").classList.remove("d-none");

    document.getElementById("id").value = "";
    $("#nuevo_usuario").modal("show");
}

function btnDatosEditarUser() {
    document.getElementById("titulo_modal").innerHTML = "Actualizar Usuario";
    //document.getElementById("btn_accion").innerHTML = "Modificar";
    document.getElementById("btn_accion_registrar").classList.add("d-none");
    document.getElementById("btn_accion_editar").classList.remove("d-none");
    document.getElementById("select_empleado").classList.add("d-none");
    document.getElementById("select_empleado_editar").classList.remove("d-none");
    document.getElementById("claves").classList.add("d-none");
}

function btnEditarUser(id) {
    validacionUsuario();
    btnDatosEditarUser();
    limpiarFormulario();
    document.getElementById("id_rol").classList.add("is-valid");
    document.getElementById("usuario").classList.add("is-valid");

    const url = BASE_URL + "User/editar/" + id;

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            //console.log("Respuesta del servidor:", response);

            try {
                document.getElementById("usuario").value = response.usuario;
                const nombreCompleto = `${response.nombres} ${response.ape_paterno} ${response.ape_materno}`;
                document.getElementById("empleado_editar").value = nombreCompleto;
                document.getElementById("id").value = response.id_usuario;

                // AJAX para obtener roles
                $.ajax({
                    url: BASE_URL + 'User/roles',
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        const selectRole = $("#id_rol");
                        selectRole.empty();
                        selectRole.append('<option value="">Seleccione Rol</option>');  // Agregar un placeholder al inicio

                        data.forEach(function (rol) {
                            selectRole.append(
                                `<option value="${rol.id_rol}" ${rol.id_rol === response.id_rol ? 'selected' : ''}>${rol.nombre_rol}</option>`
                            );
                        });
                    },
                    error: function () {
                        alert('Error al obtener los roles');
                    }
                });

                document.getElementById("id_rol").value = response.id_rol;

                $("#nuevo_usuario").modal("show");

            } catch (e) {
                console.error("Error al procesar los datos:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr.responseText + " - " + error);
        }
    });
}

function btnDeshabilitarUser(id) {
    Swal.fire({
        title: "¿Estas seguro de querer deshabilitar este usuario?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "User/deshabilitar/" + id;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Usuario deshabilitado con exito');
                            loadUsers();
                        } else {
                            notyf.success('Error al deshabilitar los datos');
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
    });
}

function btnHabilitarUser(id) {
    Swal.fire({
        title: "¿Estas seguro de querer habilitar este usuario?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "User/habilitar/" + id;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Usuario habilitado con exito');
                            loadUsers();
                        } else {
                            notyf.success('Error al deshabilitar los datos');
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
    });
}