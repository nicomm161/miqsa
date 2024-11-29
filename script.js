

document.addEventListener('DOMContentLoaded', function() {
    validarFormularioRegistro();
    validarFormularioLogin();
    validarFormularioServicios();
    carruselFotos();
});






























function carruselFotos() {
    let boton_izquierda = document.getElementById('boton-carrusel-izquierda')
    let boton_derecha = document.getElementById('boton-carrusel-derecha')
    const imagenes = ['img/aire-acondicionado.jpg', 'img/fontanero.jpg', 'img/gasero.jpg', 'img/tecnico-agua.jpg', 'img/calderas.jpg', 'img/telecomunicacion.jpg', 'pci.jpg', 'img/electricista.jpg']
    let posicionActual = 0;

    boton_izquierda.addEventListener('mouseover', function retrocederFoto() {
        if (posicionActual <= 0) {
            posicionActual = imagenes.length - 1;
        } else {
            posicionActual--;
        }
        actualizarImagen()
        comprobarPosicion()
             
    })

    boton_derecha.addEventListener('mouseover', function avanzarFoto() {
        if (posicionActual>=imagenes.length -1) {
            posicionActual = 0;
        } else {
            posicionActual++;
        }
        actualizarImagen()
        comprobarPosicion()
    })

    function actualizarImagen() {
        let imagen = document.getElementById('imagen-carrusel');

        if (imagen) {
            imagen.src = imagenes[posicionActual];
        }
    }

    function comprobarPosicion() {

        if (posicionActual === imagenes.length - 1) {
            boton_derecha.disabled = true;
        } else {
            boton_derecha.disabled = false;
        }

        if (posicionActual === 0) {
            boton_izquierda.disabled = true;
        } else {
            boton_izquierda.disabled = false;
        }
        
    }

    comprobarPosicion()
}


// Función para validar el formulario de registro
function validarFormularioRegistro() {
    const formRegistro = document.getElementById('formulario-registro');
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(event) {
            event.preventDefault();

            let todosLosCamposVacios = document.getElementById('todos-los-campos-vacios');

            // Validar nombre
            let nombreEntrada = document.getElementById('nombre-registro');
            let errorNombre = document.getElementById('error-nombre-registro');

            if (nombreEntrada.value.trim() === '') {
                errorNombre.textContent = '¡El nombre es obligatorio!';
                errorNombre.classList.add('error-message-registro');
                errorNombre.hidden = false;
            } else {
                errorNombre.textContent = '';
                errorNombre.classList.remove('error-message-registro');
                errorNombre.hidden = true;
            }

            // Validar email
            let emailEntrada = document.getElementById('email-registro');
            let errorEmail = document.getElementById('error-email-registro');
            const emailPatron = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

            if (!emailPatron.test(emailEntrada.value.trim())) {
                errorEmail.textContent = '¡El email no es correcto!';
                errorEmail.classList.add('error-message-registro');
                errorEmail.hidden = false;
            } else {
                errorEmail.textContent = '';
                errorEmail.classList.remove('error-message-registro');
                errorEmail.hidden = true;
            }

            // Validar contrasena
            let contrasenaEntrada = document.getElementById('contrasena-registro');
            let errorContrasena = document.getElementById('error-contrasena-registro');
            const contrasenaPatron = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            if (!contrasenaPatron.test(contrasenaEntrada.value.trim())) {
                errorContrasena.textContent = '¡La contraseña no es correcta, debe tener entre 8 y 16 caracteres, al menos un número, una letra mayúscula y una letra minúscula!';
                errorContrasena.classList.add('error-message-registro');
                errorContrasena.hidden = false;
            } else {
                errorContrasena.textContent = '';
                errorContrasena.classList.remove('error-message-registro');
                errorContrasena.hidden = true;
            }

            // Validar telefono
            let telefonoEntrada = document.getElementById('telefono-registro');
            let errorTelefono = document.getElementById('error-telefono-registro');
            const telefonoPatron = /^\d{3}-\d{3}-\d{3}$/;

            if (!telefonoPatron.test(telefonoEntrada.value.trim())) {
                errorTelefono.textContent = '¡El teléfono no sigue el patrón 3 dígitos - 3 dígitos - 3 dígitos!';
                errorTelefono.classList.add('error-message-registro');
                errorTelefono.hidden = false;
            } else {
                errorTelefono.textContent = '';
                errorTelefono.classList.remove('error-message-registro');
                errorTelefono.hidden = true;
            }

            // Validar si estan vacios los campos
            if (emailEntrada.value.trim() === '') {
                errorEmail.textContent = '¡El email es obligatorio de rellenar!';
                errorEmail.classList.add('error-message-registro');
                errorEmail.hidden = false;
            } else {
                errorEmail.textContent = '';
                errorEmail.classList.remove('error-message-registro');
                errorEmail.hidden = true;
            }

            if (contrasenaEntrada.value.trim() === '') {
                errorContrasena.textContent = '¡La contraseña es obligatoria de rellenar!';
                errorContrasena.classList.add('error-message-registro');
                errorContrasena.hidden = false;
            } else {
                errorContrasena.textContent = '';
                errorContrasena.classList.remove('error-message-registro');
                errorContrasena.hidden = true;
            }

            if (telefonoEntrada.value.trim() === '') {
                errorTelefono.textContent = '¡El teléfono es obligatorio de rellenar!';
                errorTelefono.classList.add('error-message-registro');
                errorTelefono.hidden = false;
            } else {
                errorTelefono.textContent = '';
                errorTelefono.classList.remove('error-message-registro');
                errorTelefono.hidden = true;
            }

            if (nombreEntrada.value.trim() === '' && emailEntrada.value.trim() === '' && contrasenaEntrada.value.trim() === '' && telefonoEntrada.value.trim() === '') {
                todosLosCamposVacios.textContent = '¡Todos los campos son obligatorios de rellenar!';
                todosLosCamposVacios.classList.add('error-message-registro');
                todosLosCamposVacios.hidden = false;
            } else {
                todosLosCamposVacios.textContent = '';
                todosLosCamposVacios.classList.remove('error-message-registro');
                todosLosCamposVacios.hidden = true;
            }

            // Si todos los campos estan correctos, enviar el formulario
            if (errorNombre.hidden && errorEmail.hidden && errorContrasena.hidden && errorTelefono.hidden && todosLosCamposVacios.hidden) {
                document.getElementById('formulario-registro').submit();
                document.getElementById('formulario-registro').reset();
            }

        });
    }
}

// Función para validar el formulario de inicio de sesión
function validarFormularioLogin() {
    const formLogin = document.getElementById('formulario-login');
    if (formLogin) {
        formLogin.addEventListener('submit', function (event) {
            event.preventDefault();

            let emailEntrada = document.getElementById('email-login');
            let errorEmail = document.getElementById('error-email-login');
            let contrasenaEntrada = document.getElementById('contrasena-login');
            let errorContrasena = document.getElementById('error-contrasena-login');

            // Validar email
            if (emailEntrada.value.trim() === '') {
                errorEmail.textContent = 'Debes rellenar el email para iniciar sesión';
                errorEmail.classList.add('error-message-login');
                errorEmail.hidden = false;
            } else {
                errorEmail.textContent = '';
                errorEmail.classList.remove('error-message-login');
                errorEmail.hidden = true;
            }

            // Validar contrasena
            if (contrasenaEntrada.value.trim() === '') {
                errorContrasena.textContent = 'Debes rellenar la contraseña para iniciar sesión';
                errorContrasena.classList.add('error-message-login');
                errorContrasena.hidden = false;
            } else {
                errorContrasena.textContent = '';
                errorContrasena.classList.remove('error-message-login');
                errorContrasena.hidden = true;
            }

            // Si todos los campos estan correctos, enviar el formulario
            if (errorEmail.hidden && errorContrasena.hidden) {
                document.getElementById('formulario-login').submit();
                document.getElementById('formulario-login').reset();
            }
        });
    }
}

// Función para validar el formulario de servicios
function validarFormularioServicios() {
    const formServicios = document.getElementById('formulario-servicios');
    if (formServicios) {
        formServicios.addEventListener('submit', function (event) {
            event.preventDefault();

            let textAreaEntrada = document.getElementById('textarea-servicios'); 
            let errorTextArea = document.getElementById('textarea-error');

            // Validar textarea
            if (textAreaEntrada.value.trim() === '') {
                errorTextArea.textContent = 'Debes rellenar el campo de texto para notificar bien a nuestros técnicos';
                errorTextArea.classList.add('error-message-servicios');
                errorTextArea.hidden = false;
            } else {
                errorTextArea.textContent = '';
                errorTextArea.classList.remove('error-message-servicios');
                errorTextArea.hidden = true;
            }

            // Enviar el formulario si no hay errores
            if (errorTextArea.hidden) {
                document.getElementById('formulario-servicios').submit();
                document.getElementById('formulario-servicios').reset();
            }
        });
    }
}
