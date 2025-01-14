// Ejecuta mi función frmLogin
document.getElementById('loginButton').addEventListener('click', (e) => {
    frmLogin(e);
});

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

// Función general para remover y agregar clases de validación
function handleInputValidation(event) {
    const input = event.target;
    const feedback = document.getElementById(`${input.id}-feedback`);

    if (input.value.trim() === "") {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        feedback.textContent = input.id === 'usuario' ? "Por favor, ingrese su usuario." : "Por favor, ingrese su contraseña.";
    } else {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        feedback.textContent = "";
    }
}

// Obtiene los campos usuario y clave
usuario = document.getElementById('usuario');
clave = document.getElementById('clave');

// Añadir evento "input" para manejo general
usuario.addEventListener('input', handleInputValidation);
clave.addEventListener('input', handleInputValidation);

// Función que se ejcuta al dar click en el boton iniciar sesión
function frmLogin(e) {
    e.preventDefault();

    const fields = [
        { id: 'usuario', message: "Por favor, ingrese su usuario." },
        { id: 'clave', message: "Por favor, ingrese su contraseña." }
    ];

    let isValid = true;

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const feedback = document.getElementById(`${field.id}-feedback`);

        if (input.value.trim() === "") {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            feedback.textContent = field.message;
            if (isValid) { // Solo enfocar el primer campo inválido
                input.focus();
            }
            isValid = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            feedback.textContent = "";
        }
    });

    if (isValid) {
        const url = BASE_URL + "Login/validar";
        const frm = $("#frmLogin");

        $.ajax({
            url: url,
            type: "POST",
            data: frm.serialize(),  // Serializa los datos del formulario
            success: function (response) {

                try {
                    const res = JSON.parse(response);
                    if (res === "ok") {

                        window.location = BASE_URL + "Evidence";
                        //console.log(res);
                    } else if (res == "Usuario inactivo") {
                        usuario.value = "";
                        usuario.classList.remove('is-valid', 'is-invalid');
                        usuario.focus();
                        clave.value = "";
                        clave.classList.remove('is-valid', 'is-invalid');
                        notyf.error('Usuario inactivo');
                    } else {
                        usuario.value = "";
                        usuario.classList.remove('is-valid', 'is-invalid');
                        usuario.focus();
                        clave.value = "";
                        clave.classList.remove('is-valid', 'is-invalid');

                        // Display an error notification
                        notyf.error('Credenciales Incorrectos, Por favor intentelo denuevo');
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
/* document.getElementById('btn-cerrar-sesion').addEventListener('click', (e) => {
    salir();
}); */