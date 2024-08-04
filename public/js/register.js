document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma convencional

        // Realizar la solicitud de registro de forma tradicional
        let form = event.target;
        let formData = new FormData(form);

        // Validar que las contraseñas coincidan
        let password = formData.get('password');
        let passwordConfirmation = formData.get('password_confirmation');
        // Validar que no haya campos vacíos
        if (formData.get('name') === '' || formData.get('email') === '' || password === '' || passwordConfirmation === ''){
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'Por favor llena todos los campos',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        if (password !== passwordConfirmation) {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'Las contraseñas no coinciden',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(data) {
                        throw new Error(data.message || 'Error en la solicitud');
                    });
                }
                return response.json();
            })
            .then(function(data) {
                if (data.error) {
                    let errorMessages = [];

                    console.log(data.error);

                    if (data.error.email) {
                        data.error.email.forEach(message => {
                            if (message.includes("email has already")) {
                                errorMessages.push('Este correo ya está registrado, por favor intenta con otro');
                            } else if (message.includes("Solo correos universitarios")) {
                                errorMessages.push('Solo se permiten correos universitarios');
                            }
                        });
                    }

                    if (data.error.password) {
                        data.error.password.forEach(message => {
                            if (message.includes("The password field must be at least 8 characters")) {
                                errorMessages.push('La contraseña debe tener al menos 8 caracteres');
                            }
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: errorMessages.join(', '),
                        confirmButtonColor: '#660D04',
                    });

                    return;
                }

                // Manejar la respuesta del servidor según sea necesario
                localStorage.setItem('token', data.token);

                // Redireccionar al usuario a la página de inicio
                window.location.href = data.redirect_to;
            })
            .catch(function(error) {
                // Manejar el error de la solicitud
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonColor: '#660D04',
                });
            });
    });
});
