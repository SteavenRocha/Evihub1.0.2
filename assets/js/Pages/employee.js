// Obtener la tabla
const tableBodyEmployee = document.getElementById('tableEmployees');

// Función para cargar los datos de usuarios
function loadEmployees() {
    // URL que devuelve los datos
    const url = BASE_URL + "Employee/listar";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            tableBodyEmployee.innerHTML = "";

            // Verificar si hay datos en el array `data`
            if (data.length === 0) {
                const emptyRow = `
                    <tr>
                        <td colspan="7" class="tabla-vacia">
                            No existen empleados registrados
                        </td>
                    </tr>
                `;
                tableBodyEmployee.innerHTML = emptyRow;
            } else {
                // Si hay datos, agregar las filas normalmente
                data.forEach(user => {
                    const statusClass = user.estado_empleado == 1 ? 'estado-activo' : 'estado-inactivo';
                    const row =
                        `
                    <tr>
                    <td>${user.id_empleado}</td>
                    <td>${user.dni}</td>
                    <td>${user.nombre_completo}</td>
                    <td>${user.n_celular}</td>
                    <td>${user.nombre_sucursal}</td>
                    <td>
                        <span class="estado-usuario ${statusClass}">
                            ${user.estado_empleado == 1 ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td>${user.fecha_registro}</td>
                    <td>

                    <div class="acciones">
                        <!-- Botón Editar -->
                        <button class="texto-emergente btn-editar" onclick="btnEditarEmpleado(${user.id_empleado});">
                            <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                            <span class="tooltiptext">editar</span>
                        </button>
                        
                        <!-- Botón Deshabilitar: Solo visible si el usuario está activo -->
                        ${user.estado_empleado == 1 ? `
                            <button class="texto-emergente btn-deshabilitar" onclick="btnDeshabilitarEmpleado(${user.id_empleado});">
                                <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                                <span class="tooltiptext">deshabilitar</span>
                            </button>
                        ` : ''}

                        <!-- Botón Habilitar: Solo visible si el usuario está inactivo -->
                        ${user.estado_empleado == 0 ? `
                            <button class="texto-emergente btn-habilitar" onclick="btnHabilitarEmpleado(${user.id_empleado});">
                                <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                </svg>  
                                <span class="tooltiptext">habilitar</span>
                            </button>
                        ` : ''}
                    </div>
                    </td>
                    </tr>
                    `;
                    tableBodyEmployee.innerHTML += row;
                });
            }
        })
        .catch(error => console.error('Error al cargar los empleados:', error));
}

const currentPathEmployee = window.location.pathname;
if (currentPathEmployee.includes("/Employee")) {
    // Llama a la función deseada
    document.addEventListener('DOMContentLoaded', loadEmployees);

    document.getElementById('filtroEmpleados').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tableEmployees tr');

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

// Validar DNI
function handleDniValidation(event) {
    const dniInput = event.target;
    const feedback = document.getElementById("feedbackDNI");

    if (!/^\d*$/.test(dniInput.value)) {
        feedback.textContent = "Por favor, solo ingresar números.";
        dniInput.classList.add("is-invalid");
        dniInput.classList.remove("is-valid");
    } else if (dniInput.value.length === 0 || dniInput.value.length < 8) {
        feedback.textContent = "";
        dniInput.classList.remove("is-invalid", "is-valid");
    } else if (dniInput.value.length === 8) {
        feedback.textContent = "";
        dniInput.classList.remove("is-invalid");
        dniInput.classList.add("is-valid");
    } else {
        dniInput.classList.remove("is-invalid", "is-valid");
        feedback.textContent = "";
    }
}

// Validar Nombres
function handleNombresValidation(event) {
    const nombresInput = event.target;
    const feedback = document.getElementById("feedbackNombres");
    const value = nombresInput.value.trim();
    const words = value.split(/\s+/);

    if (value.length === 0 || words.length < 3) {
        feedback.textContent = "";
        nombresInput.classList.remove("is-invalid", "is-valid");
    } else {
        feedback.textContent = "";
        nombresInput.classList.remove("is-invalid");
        nombresInput.classList.add("is-valid");
    }
}

// Validar Celular
function handleCelularValidation(event) {
    const celularInput = event.target;
    const feedback = document.getElementById("feedbackCelular");
    const value = celularInput.value.trim();

    if (value.length === 0) {
        feedback.textContent = "";
        celularInput.classList.remove("is-invalid", "is-valid");
    } else if (!/^\d*$/.test(value)) {
        feedback.textContent = "Por favor, solo ingresar números.";
        celularInput.classList.add("is-invalid");
        celularInput.classList.remove("is-valid");
    } else if (value.length < 9) {
        feedback.textContent = "El Celular debe tener mínimo 9 dígitos.";
        celularInput.classList.add("is-invalid");
        celularInput.classList.remove("is-valid");
    } else {
        feedback.textContent = "";
        celularInput.classList.remove("is-invalid");
        celularInput.classList.add("is-valid");
    }
}

// Validar Sucursal
function handleSucursalValidation(event) {
    const selectSucursal = event.target;
    const feedback = document.getElementById("feedbackSucursal");

    if (selectSucursal.value === "") {
        feedback.textContent = "Por favor, seleccione una sucursal.";
        selectSucursal.classList.add("is-invalid");
        selectSucursal.classList.remove("is-valid");
    } else {
        feedback.textContent = "";
        selectSucursal.classList.remove("is-invalid");
        selectSucursal.classList.add("is-valid");
    }
}

// Validar Campos al Registrar Empleado
function validateEmpleadoRegistrar(dniInput, nombresInput, celularInput, selectSucursal, feedbackDNI, feedbackNombres, feedbackCelular, feedbackSucursal) {
    let valid = true;

    // Validar DNI
    if (!/^\d*$/.test(dniInput.value) || dniInput.value.trim() === "" || dniInput.value.length !== 8) {
        feedbackDNI.textContent = "El DNI debe tener exactamente 8 dígitos.";
        dniInput.classList.add("is-invalid");
        dniInput.classList.remove("is-valid");
        valid = false;
    } else {
        feedbackDNI.textContent = "";
        dniInput.classList.remove("is-invalid");
        dniInput.classList.add("is-valid");
    }

    // Validar Nombres
    const nombresValue = nombresInput.value.trim();
    const words = nombresValue.split(/\s+/);
    if (nombresValue === "" || words.length < 3) {
        feedbackNombres.textContent = "Por favor ingresar mínimo 1 nombre y 2 apellidos";
        nombresInput.classList.add("is-invalid");
        nombresInput.classList.remove("is-valid");
        valid = false;
    } else {
        feedbackNombres.textContent = "";
        nombresInput.classList.remove("is-invalid");
        nombresInput.classList.add("is-valid");
    }

    // Validar Celular
    const celularValue = celularInput.value.trim();
    if (!/^\d*$/.test(celularValue) || celularValue.length < 9) {
        feedbackCelular.textContent = "El Celular debe tener mínimo 9 dígitos.";
        celularInput.classList.add("is-invalid");
        celularInput.classList.remove("is-valid");
        valid = false;
    } else {
        feedbackCelular.textContent = "";
        celularInput.classList.remove("is-invalid");
        celularInput.classList.add("is-valid");
    }

    // Validar Sucursal
    if (selectSucursal.value === "") {
        feedbackSucursal.textContent = "Por favor, seleccione una sucursal.";
        selectSucursal.classList.add("is-invalid");
        selectSucursal.classList.remove("is-valid");
        valid = false;
    } else {
        feedbackSucursal.textContent = "";
        selectSucursal.classList.remove("is-invalid");
        selectSucursal.classList.add("is-valid");
    }

    return valid;
}

// Asignar Event Listeners
function asignarEventListeners() {
    const dniInput = document.getElementById("dni");
    const nombresInput = document.getElementById("nombres_empleado");
    const celularInput = document.getElementById("celular");
    const selectSucursal = document.getElementById("id_sucursal_empleado");
    const btnRegistrar = document.getElementById("btn_accion_registrar_empleado");
    const btnModificar = document.getElementById("btn_accion_editar_empleado");

    // Evento de validación en tiempo real
    dniInput.addEventListener("input", handleDniValidation);
    nombresInput.addEventListener("input", handleNombresValidation);
    celularInput.addEventListener("input", handleCelularValidation);
    selectSucursal.addEventListener("change", handleSucursalValidation);

    // Validación al Registrar
    btnRegistrar.addEventListener("click", (e) => {
        e.preventDefault();
        const feedbackDNI = document.getElementById("feedbackDNI");
        const feedbackNombres = document.getElementById("feedbackNombres");
        const feedbackCelular = document.getElementById("feedbackCelular");
        const feedbackSucursal = document.getElementById("feedbackSucursal");

        if (validateEmpleadoRegistrar(dniInput, nombresInput, celularInput, selectSucursal, feedbackDNI, feedbackNombres, feedbackCelular, feedbackSucursal)) {
            // Aquí va la lógica para registrar el empleado
            console.log("Empleado registrado correctamente");
        }
    });

    // Validación al Modificar
    btnModificar.addEventListener("click", (e) => {
        e.preventDefault();
        const feedbackDNI = document.getElementById("feedbackDNI");
        const feedbackNombres = document.getElementById("feedbackNombres");
        const feedbackCelular = document.getElementById("feedbackCelular");
        const feedbackSucursal = document.getElementById("feedbackSucursal");

        if (validateEmpleadoRegistrar(dniInput, nombresInput, celularInput, selectSucursal, feedbackDNI, feedbackNombres, feedbackCelular, feedbackSucursal)) {
            // Aquí va la lógica para modificar el empleado
            console.log("Empleado modificado correctamente");
        }
    });
}

function registrarEmpleado(e) {
    e.preventDefault();

    // Verificar si todos los campos tienen la clase "is-valid"
    const campos = [
        document.getElementById("dni"),
        document.getElementById("nombres_empleado"),
        document.getElementById("celular"),
        document.getElementById("id_sucursal_empleado"),
    ];

    const isValid = campos.every(campo => campo.classList.contains("is-valid"));
    const dniInput = document.getElementById("dni");
    const feedbackDni = document.getElementById("feedbackDNI");
    if (isValid) {
        const url = BASE_URL + "Employee/registrar";
        const frm = $("#frmEmpleado");

        $.ajax({
            url: url,
            type: "POST",
            data: frm.serialize(), // Serializa los datos del formulario
            success: function (response) {
                try {
                    const res = JSON.parse(response);

                    if (res == "si") {
                        notyf.success('Empleado registrado con éxito');
                        cerrarModalEmpleado();
                        loadEmployees();
                    } else if (res == "invalido") {
                        notyf.error('Formato de nombre inválido');
                    } else if (res == "existe") {
                        /*  notyf.error('Empleado ya existe'); */
                        feedbackDni.textContent = "Empleado ya existe";
                        dniInput.classList.add("is-invalid");
                        dniInput.classList.remove("is-valid");
                        /* notyf.error('Empleado ya existe'); */
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

/* MODAL */
$(document).ready(function () {

    $("#btn_abrir_modal_empleado").click(function () {
        frmEmpleado();
        limpiarFormularioEmpleado();
        asignarEventListeners();
    });

    $("#btn_accion_registrar_empleado").click(function (e) {
        registrarEmpleado(e);
    });

    $("#btn_accion_editar_empleado").click(function (e) {
        modificarEmpleado(e);
    });
});

// Funcion para abrir el modal por AJAX
function frmEmpleado() {

    btnNuevoEmpleado();

    // AJAX para obtener sucursales
    $.ajax({
        url: BASE_URL + 'Employee/sucursales',
        type: 'POST',
        dataType: 'json',
        /*  data: {
            
        }, */
        success: function (data) {
            /* console.log(data);  */
            const selectSucursal = $("#id_sucursal_empleado");
            selectSucursal.empty();

            selectSucursal.append('<option value="">Seleccione sucursal</option>');

            data.forEach(function (sucursal) {
                selectSucursal.append(
                    `<option value="${sucursal.id_sucursal}">${sucursal.nombre_sucursal}</option>`
                );
            });
        },
        error: function () {
            alert('Error al obtener la sucursal');
        }
    });
}

function btnNuevoEmpleado() {
    document.getElementById("titulo_modal_empleado").innerHTML = "Agregar Nuevo Empleado";
    //document.getElementById("btn_accion").innerHTML = "Registrar";
    document.getElementById("btn_accion_registrar_empleado").classList.remove("d-none");
    document.getElementById("btn_accion_editar_empleado").classList.add("d-none");
    /*  document.getElementById("select_empleado").classList.remove("d-none");
     document.getElementById("select_empleado_editar").classList.add("d-none"); */

    document.getElementById("id_empleado").value = "";
    limpiarFormularioEmpleado();
    $("#nuevo_empleado").modal("show");
}

function limpiarFormularioEmpleado() {
    const frm = document.getElementById("frmEmpleado");
    frm.reset();
    // Limpiar las clases de validación
    document.getElementById("dni").classList.remove("is-invalid", "is-valid");
    document.getElementById("nombres_empleado").classList.remove("is-invalid", "is-valid");
    document.getElementById("celular").classList.remove("is-invalid", "is-valid");
    document.getElementById("id_sucursal_empleado").classList.remove("is-invalid", "is-valid");

    // Limpiar los campos de texto
    document.getElementById("dni").value = "";
    document.getElementById("nombres_empleado").value = "";
    document.getElementById("celular").value = "";
    document.getElementById("id_sucursal_empleado").value = "";
}

function cerrarModalEmpleado() {
    $("#nuevo_empleado").modal("hide");
}

function modificarEmpleado(e) {
    e.preventDefault();

    // Verificar si todos los campos tienen la clase "is-valid"
    const campos = [
        document.getElementById("dni"),
        document.getElementById("nombres_empleado"),
        document.getElementById("celular"),
        document.getElementById("id_sucursal_empleado"),
    ];

    const isValid = campos.every(campo => campo.classList.contains("is-valid"));

    const dniInput = document.getElementById("dni");
    const feedbackDni = document.getElementById("feedbackDNI");

    if (isValid) {

        const dni = document.getElementById("dni");
        const nombres_empleado = document.getElementById("nombres_empleado");
        const celular = document.getElementById("celular");
        const id_sucursal_empleado = document.getElementById("id_sucursal_empleado");

        if (dni.value == "" || nombres_empleado.value == "" || celular.value == "" || id_sucursal_empleado.value == "") {

            notyf.error('Todos los campos son necesarios');

        } else {
            const url = BASE_URL + "Employee/modificar";
            const frm = $("#frmEmpleado");

            $.ajax({
                url: url,
                type: "POST",
                data: frm.serialize(),  // Serializa los datos del formulario
                success: function (response) {

                    try {
                        const res = JSON.parse(response);

                        /*  console.log(res); */

                        if (res == "modificado") {

                            notyf.success('Empleado modificado con exito');
                            cerrarModalEmpleado();
                            loadEmployees();
                        } else {
                            /* notyf.error('Empleado ya existe'); */
                            feedbackDni.textContent = "Empleado ya existe";
                            dniInput.classList.add("is-invalid");
                            dniInput.classList.remove("is-valid");
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

function btnDatosEditarEmpleado() {
    document.getElementById("titulo_modal_empleado").innerHTML = "Actualizar Empleado";
    //document.getElementById("btn_accion").innerHTML = "Modificar";
    document.getElementById("btn_accion_registrar_empleado").classList.add("d-none");
    document.getElementById("btn_accion_editar_empleado").classList.remove("d-none");
    /*  document.getElementById("select_empleado").classList.add("d-none");
     document.getElementById("select_empleado_editar").classList.remove("d-none"); */
    limpiarFormularioEmpleado();
}

function btnEditarEmpleado(id_empleado) {
    asignarEventListeners();
    btnDatosEditarEmpleado();

    document.getElementById("dni").classList.add("is-valid");
    document.getElementById("nombres_empleado").classList.add("is-valid");
    document.getElementById("celular").classList.add("is-valid");
    document.getElementById("id_sucursal_empleado").classList.add("is-valid");

    const url = BASE_URL + "Employee/editar/" + id_empleado;

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            /* console.log("Respuesta del servidor:", response); */

            try {
                document.getElementById("dni").value = response.dni;
                const nombreCompleto = `${response.nombres} ${response.ape_paterno} ${response.ape_materno}`;
                document.getElementById("nombres_empleado").value = nombreCompleto;
                document.getElementById("celular").value = response.n_celular;
                document.getElementById("id_sucursal_empleado").value = response.id_sucursal;
                document.getElementById("id_empleado").value = response.id_empleado;

                // AJAX para obtener roles
                $.ajax({
                    url: BASE_URL + 'Employee/sucursales',
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        const selectRole = $("#id_sucursal_empleado");
                        selectRole.empty();
                        selectRole.append('<option value="">Seleccione Sucursal</option>');  // Agregar un placeholder al inicio

                        data.forEach(function (sucursal) {
                            selectRole.append(
                                `<option value="${sucursal.id_sucursal}" ${sucursal.id_sucursal === response.id_sucursal ? 'selected' : ''}>${sucursal.nombre_sucursal}</option>`
                            );
                        });
                    },
                    error: function () {
                        alert('Error al obtener los roles');
                    }
                });

                document.getElementById("id_sucursal_empleado").value = response.id_sucursal;

                $("#nuevo_empleado").modal("show");

            } catch (e) {
                console.error("Error al procesar los datos:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr.responseText + " - " + error);
        }
    });
}

function btnDeshabilitarEmpleado(id_empleado) {
    Swal.fire({
        title: "¿Estas seguro de querer deshabilitar este empleado?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "Employee/deshabilitar/" + id_empleado;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Empleado deshabilitado con exito');
                            loadEmployees();
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

function btnHabilitarEmpleado(id_empleado) {
    Swal.fire({
        title: "¿Estas seguro de querer habilitar este empleado?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "Employee/habilitar/" + id_empleado;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Empleado habilitado con exito');
                            loadEmployees();
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