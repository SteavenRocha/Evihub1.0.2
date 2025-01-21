// Obtener la tabla
const tableBodyBranch = document.getElementById('tableSucursal');

// Función para cargar los datos de usuarios
function loadBranch() {
    // URL que devuelve los datos
    const url = BASE_URL + "Branch/listar";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            tableBodyBranch.innerHTML = "";

            // Verificar si hay datos en el array `data`
            if (data.length === 0) {
                const emptyRow = `
                    <tr>
                        <td colspan="7" class="tabla-vacia">
                            No existen sucursales registrados
                        </td>
                    </tr>
                `;
                tableBodyBranch.innerHTML = emptyRow;
            } else {
                // Si hay datos, agregar las filas normalmente
                /* console.log(data); */
                data.forEach(branch => {
                    const statusClass = branch.estado_sucursal == 1 ? 'estado-activo' : 'estado-inactivo';
                    const row =
                        `
                    <tr>
                    <td>${branch.id_sucursal}</td>
                    <td>${branch.nombre_sucursal}</td>
                     <td>
                        <span class="estado-usuario ${statusClass}">
                            ${branch.estado_sucursal == 1 ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td>${branch.fecha_registro}</td>
                    <td>
                        <div class="acciones">
                            <!-- Botón Editar -->
                            <button class="texto-emergente btn-editar" onclick="btnEditarSucursal(${branch.id_sucursal});">
                                <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                </svg>
                                <span class="tooltiptext">editar</span>
                            </button>
                            
                            <!-- Botón Deshabilitar: Solo visible si el usuario está activo -->
                            ${branch.estado_sucursal == 1 ? `
                                <button class="texto-emergente btn-deshabilitar" onclick="btnDeshabilitarSucursal(${branch.id_sucursal});">
                                    <svg class="svgIcon-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                    </svg>
                                    <span class="tooltiptext">deshabilitar</span>
                                </button>
                            ` : ''}

                            <!-- Botón Habilitar: Solo visible si el usuario está inactivo -->
                            ${branch.estado_sucursal == 0 ? `
                                <button class="texto-emergente btn-habilitar" onclick="btnHabilitarSucursal(${branch.id_sucursal});">
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
                    tableBodyBranch.innerHTML += row;
                });
            }
        })
        .catch(error => console.error('Error al cargar las sucursales:', error));
}

const currentPathBranch = window.location.pathname;
if (currentPathBranch.includes("/Branch")) {
    // Llama a la función deseada
    document.addEventListener('DOMContentLoaded', loadBranch);

    document.getElementById('filtroSucursales').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tableSucursal tr');

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

function registrarSucursal(e) {
    e.preventDefault();

    // Verificar si todos los campos tienen la clase "is-valid"
    /*  const campos = [
         document.getElementById("dni"),
         document.getElementById("nombres_empleado"),
         document.getElementById("celular"),
         document.getElementById("id_sucursal_empleado"),
     ]; */

    /*   const isValid = campos.every(campo => campo.classList.contains("is-valid")); */

    /*  if (isValid) { */
    const url = BASE_URL + "Branch/registrar";
    const frm = $("#frmSucursal");

    $.ajax({
        url: url,
        type: "POST",
        data: frm.serialize(), // Serializa los datos del formulario
        success: function (response) {
            try {
                const res = JSON.parse(response);

                if (res == "si") {
                    notyf.success('Sucursal registrado con éxito');
                    cerrarModalSucursal();
                    loadBranch();
                } else if (res == "existe") {
                    notyf.error('Sucursal ya existe');
                }
            } catch (e) {
                console.log("Respuesta no es JSON: " + response);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr + " - " + error);
        }
    });
    /* } else {
        notyf.error('Por favor, corrija los errores antes de registrar.'); */
}
/* } */

/* MODAL */
$(document).ready(function () {

    $("#btn_abrir_modal_sucursal").click(function () {
        frmSucursal();
        limpiarFormularioSucursal();
        // asignarEventListeners();
    });

    $("#btn_accion_registrar_sucursal").click(function (e) {
        registrarSucursal(e);
    });

    $("#btn_accion_editar_sucursal").click(function (e) {
        modificarSucursal(e);
    });
});

// Funcion para abrir el modal por AJAX
function frmSucursal() {

    document.getElementById("titulo_modal_sucursal").innerHTML = "Agregar Nueva Sucursal";
    //document.getElementById("btn_accion").innerHTML = "Registrar";
    document.getElementById("btn_accion_registrar_sucursal").classList.remove("d-none");
    document.getElementById("btn_accion_editar_sucursal").classList.add("d-none");
    /*  document.getElementById("select_empleado").classList.remove("d-none");
     document.getElementById("select_empleado_editar").classList.add("d-none"); */

    document.getElementById("id_sucursal").value = "";
    limpiarFormularioSucursal();
    $("#nueva_sucursal").modal("show");
}

/* function btnNuevaSucursal() {
    
} */

function limpiarFormularioSucursal() {
    const frm = document.getElementById("frmSucursal");
    frm.reset();
    // Limpiar las clases de validación
    /*  document.getElementById("dni").classList.remove("is-invalid", "is-valid");
     document.getElementById("nombres_empleado").classList.remove("is-invalid", "is-valid");
     document.getElementById("celular").classList.remove("is-invalid", "is-valid");
     document.getElementById("id_sucursal_empleado").classList.remove("is-invalid", "is-valid"); */

    // Limpiar los campos de texto
    document.getElementById("nombre_sucursal").value = "";
}

function cerrarModalSucursal() {
    $("#nueva_sucursal").modal("hide");
}

function modificarSucursal(e) {
    e.preventDefault();

    // Verificar si todos los campos tienen la clase "is-valid"
    /*  const campos = [
         document.getElementById("dni"),
         document.getElementById("nombres_empleado"),
         document.getElementById("celular"),
         document.getElementById("id_sucursal_empleado"),
     ];
 
     const isValid = campos.every(campo => campo.classList.contains("is-valid")); */

    /*  if (isValid) { */

    const nombre_sucursal = document.getElementById("nombre_sucursal");

    if (nombre_sucursal.value == "") {

        notyf.error('Todos los campos son necesarios');

    } else {
        const url = BASE_URL + "Branch/modificar";
        const frm = $("#frmSucursal");

        $.ajax({
            url: url,
            type: "POST",
            data: frm.serialize(),  // Serializa los datos del formulario
            success: function (response) {

                try {
                    const res = JSON.parse(response);

                    /*  console.log(res); */

                    if (res == "modificado") {

                        notyf.success('Sucursal modificada con exito');
                        cerrarModalSucursal();
                        loadBranch();
                    } else {
                        notyf.error('Sucursal ya existe');
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
/* } */

function btnDatosEditarSucursal() {
    document.getElementById("titulo_modal_sucursal").innerHTML = "Actualizar Sucursal";
    //document.getElementById("btn_accion").innerHTML = "Modificar";
    document.getElementById("btn_accion_registrar_sucursal").classList.add("d-none");
    document.getElementById("btn_accion_editar_sucursal").classList.remove("d-none");
    /*  document.getElementById("select_empleado").classList.add("d-none");
     document.getElementById("select_empleado_editar").classList.remove("d-none"); */
    limpiarFormularioSucursal();
}

function btnEditarSucursal(id_sucursal) {
    // asignarEventListeners();
    btnDatosEditarSucursal();

    // document.getElementById("nombre_sucursal").classList.add("is-valid");

    const url = BASE_URL + "Branch/editar/" + id_sucursal;

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            /* console.log("Respuesta del servidor:", response); */

            try {
                document.getElementById("nombre_sucursal").value = response.nombre_sucursal;
                document.getElementById("id_sucursal").value = response.id_sucursal;

                $("#nueva_sucursal").modal("show");

            } catch (e) {
                console.error("Error al procesar los datos:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud: " + status + " - " + xhr.responseText + " - " + error);
        }
    });
}

function btnDeshabilitarSucursal(id_sucursal) {
    Swal.fire({
        title: "¿Estas seguro de querer deshabilitar esta sucursal?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "Branch/deshabilitar/" + id_sucursal;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Sucursal deshabilitado con exito');
                            loadBranch();
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

function btnHabilitarSucursal(id_sucursal) {
    Swal.fire({
        title: "¿Estas seguro de querer habilitar esta sucursal?",
        //text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            const url = BASE_URL + "Branch/habilitar/" + id_sucursal;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    //console.log("Respuesta del servidor:", response);
                    try {

                        if (response == "ok") {
                            notyf.success('Sucursal habilitado con exito');
                            loadBranch();
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