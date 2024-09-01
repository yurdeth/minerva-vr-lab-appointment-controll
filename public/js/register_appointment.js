document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault();

        let form = event.target;
        let formData = new FormData(form);

        let department = formData.get('department');
        let career = formData.get('career');
        let number_of_assistants = formData.get('number_of_assistants');

        if (department === 'Seleccionar departamento' || career === 'Seleccionar carrera') {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'Por favor selecciona un departamento y una carrera.',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        if (number_of_assistants < 1) {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'El número de asistentes no puede ser menor a 1.',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        if (number_of_assistants > 20) {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'El número de asistentes no puede ser mayor a 20.',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        let selectedDate = new Date(formData.get('date'));
        let today = new Date();

        // Establecer la hora de hoy a las 00:00:00 para comparar solo las fechas
        today.setHours(0, 0, 0, 0);

        if (selectedDate <= today) {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'La fecha debe ser posterior a hoy.',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        // Validar que el periodo de horas sea entre las 8:00 y las 16:00
        let selectedTime = formData.get('time');
        let selectedHour = parseInt(selectedTime.split(':')[0]);
        if (selectedHour < 8 || selectedHour > 16) {
            Swal.fire({
                icon: 'error',
                iconColor: '#660D04',
                title: 'Oops...',
                text: 'El horario de atención es de 8:00 AM a 04:00 PM',
                confirmButtonColor: '#660D04',
            });
            return;
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    let errorMessages = [];

                    if (data.error.time) {
                        data.error.time.forEach(message => {
                            if (message.includes("Ya tienes una cita registrada en esta fecha y hora, o en el rango de una hora")) {
                                errorMessages.push('Ya existe una cita registrada en esta fecha y hora, o en el rango de una hora');
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

                Swal.fire({
                    icon: 'success',
                    iconColor: '#046620',
                    title: '¡Registro exitoso!',
                    text: 'Tu cita ha sido registrada exitosamente.',
                    confirmButtonColor: '#046620',
                });

                // Redirigir a la página de inicio después de 2 segundos
                setTimeout(() => {
                    window.location.href = data.redirect_to;
                }, 1000);


            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'Ocurrió un error al registrar tu cita. Por favor intenta de nuevo.',
                    confirmButtonColor: '#660D04',
                });
            })
    });
});
