document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma convencional

        // Realizar la solicitud de inicio de sesión de forma tradicional
        let form = event.target;
        let formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData
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
                if (data.success) {
                    // Manejar la respuesta del servidor según sea necesario
                    localStorage.setItem('token', data.token);

                    // Redireccionar al usuario a la página de inicio
                    window.location.href = data.redirect_to;
                } else {
                    throw new Error(data.message || 'Error en la solicitud');
                }
            })
            .catch(function(error) {
                // Manejar el error de la solicitud
                console.error('Error: ', error);

                if(error.message === 'Credenciales erróneas') {
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: "Credenciales erróneas",
                        confirmButtonColor: '#660D04',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: error.message,
                        confirmButtonColor: '#660D04',
                    });
                }
            });
    });
});
