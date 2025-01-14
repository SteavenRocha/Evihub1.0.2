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
                            <button class="btn-accion" onclick="btnEditarEmpleado(${user.id_empleado});">
                                <span class="svgIcon-btn-editar">
                                    <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </span>
                            </button>
                            
                            <!-- Botón Deshabilitar: Solo visible si el usuario está activo -->
                            ${user.estado_empleado == 1 ? `
                                <button class="btn-accion" title="Deshabilitar" onclick="btnDeshabilitarEmpleado(${user.id_empleado});">
                                    <span class="svgIcon-btn-eliminar">
                                        <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                        </svg>
                                    </span>
                                </button>
                            ` : ''}

                            <!-- Botón Habilitar: Solo visible si el usuario está inactivo -->
                            ${user.estado_empleado == 0 ? `
                                <button class="btn-accion" title="Habilitar" onclick="btnHabilitarEmpleado(${user.id_empleado});">
                                    <span class="svgIcon-btn-habilitar">
                                        <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                        </svg>                          
                                    </span>
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
        .catch(error => console.error('Error al cargar los usuarios:', error));
}

const currentPathEmployee = window.location.pathname;
if (currentPathEmployee.includes("/Employee")) {
    // Llama a la función deseada
    document.addEventListener('DOMContentLoaded', loadEmployees);
}

/* MODAL */
$(document).ready(function () {

    $("#btn_abrir_modal_empleado").click(function () {
        frmEmpleado();
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

function registrarEmpleado(e) {
    e.preventDefault();

    const dni = document.getElementById("dni");
    const nombres_empleado = document.getElementById("nombres_empleado");
    const celular = document.getElementById("celular");
    const id_sucursal_empleado = document.getElementById("id_sucursal_empleado");
    
    if (dni.value == "" || nombres_empleado.value == "" || celular.value == "" || id_sucursal_empleado.value == "") {

        notyf.error('Todos los campos son necesarios');

    } else {
        const url = BASE_URL + "Employee/registrar";
        const frm = $("#frmEmpleado");

        $.ajax({
            url: url,
            type: "POST",
            data: frm.serialize(),  // Serializa los datos del formulario
            success: function (response) {

                try {
                    const res = JSON.parse(response);

                    if (res == "si") {

                        notyf.success('Empleado registrado con exito');
                        limpiarFormularioEmpleado();
                        cerrarModalEmpleado();

                        loadEmployees();
                    } else if ( res == "invalido") {
                        notyf.error('Formato de nombre invalido');
                    } else {
                        notyf.error('Empleado ya existe');
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
}

function cerrarModalEmpleado() {
    $("#nuevo_empleado").modal("hide");
}

function modificarEmpleado(e) {
    e.preventDefault();

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
                        limpiarFormularioEmpleado();
                        cerrarModalEmpleado();

                        loadEmployees();
                    } else {
                        notyf.error('Empleado ya existe');
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

function btnDatosEditarEmpleado() {
    document.getElementById("titulo_modal_empleado").innerHTML = "Actualizar Empleado";
    //document.getElementById("btn_accion").innerHTML = "Modificar";
    document.getElementById("btn_accion_registrar_empleado").classList.add("d-none");
    document.getElementById("btn_accion_editar_empleado").classList.remove("d-none");
   /*  document.getElementById("select_empleado").classList.add("d-none");
    document.getElementById("select_empleado_editar").classList.remove("d-none"); */
}

function btnEditarEmpleado(id_empleado) {

    btnDatosEditarEmpleado();

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
