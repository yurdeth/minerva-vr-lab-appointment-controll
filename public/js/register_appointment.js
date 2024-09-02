document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault();

        let form = event.target;
        let formData = new FormData(form);

        let department = formData.get('department');
        let career = formData.get('career');
        let number_of_assistants = formData.get('number_of_assistants');

        if (department === 'Seleccionar departamento' || career === 'Seleccionar carrera') {
            showSwal('error', '#660D04', 'Oops...', 'Por favor selecciona un departamento y una carrera.', '#660D04');
            return;
        }

        if (number_of_assistants < 1) {
            showSwal('error', '#660D04', 'Oops...', 'El número de asistentes no puede ser menor a 1.', '#660D04');
            return;
        }

        if (number_of_assistants > 20) {
            showSwal('error', '#660D04', 'Oops...', 'El número de asistentes no puede ser mayor a 20.', '#660D04');
            return;
        }

        let selectedDate = new Date(formData.get('date'));
        let today = new Date();

        // Establecer la hora de hoy a las 00:00:00 para comparar solo las fechas
        today.setHours(0, 0, 0, 0);

        if (selectedDate <= today) {
            showSwal('error', '#660D04', 'Oops...', 'La fecha debe ser posterior a hoy.', '#660D04');
            return;
        }

        // Validar que el periodo de horas sea entre las 8:00 y las 16:00
        let selectedTime = formData.get('time');
        if (selectedTime < '08:00' || selectedTime > '15:30') {
            showSwal('error', '#660D04', 'Oops...', 'Solo puedes agendar citas entre las 8:00 AM y las 3:30 PM', '#660D04');
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

                    showSwal('error', '#660D04', 'Oops...', errorMessages.join(', '), '#660D04');

                    return;
                }

                showSwal('success', '#660D04', '¡Listo!', 'Tu cita ha sido registrada exitosamente.', '#660D04');

                // Redirigir a la página de inicio después de 2 segundos
                setTimeout(() => {
                    window.location.href = data.redirect_to;
                }, 1000);


            })
            .catch(error => {
                console.error('Error:', error);
                showSwal('error', '#660D04', 'Oops...', 'Ocurrió un error al registrar tu cita. Por favor intenta de nuevo.', '#660D04');
            })
    });
});

// Función para mostrar swal dinamicamente
function showSwal(icon, iconColor, title, text, confirmButtonColor) {
    Swal.fire({
        icon: icon,
        iconColor: iconColor,
        title: title,
        text: text,
        confirmButtonColor: confirmButtonColor,
    });
}
