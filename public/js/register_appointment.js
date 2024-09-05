import {showAlert, showSuccessAlert, showErrorAlert} from './utils/alert.js'

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault();

        let form = event.target;
        let formData = new FormData(form);

        let department = formData.get('department');
        let career = formData.get('career');
        let number_of_assistants = document.getElementById('number_of_assistants');
        let date = document.getElementById('date');

        if (department === 'Seleccionar departamento' || career === 'Seleccionar carrera') {
            showErrorAlert('Oops...', 'Por favor selecciona un departamento y una carrera.');
            return;
        }

        if (number_of_assistants < 1) {
            showErrorAlert('Oops...', 'El número de asistentes no puede ser menor a 1.');
            return;
        }

        if (number_of_assistants > 20) {
            showErrorAlert('Oops...', 'El número de asistentes no puede ser mayor a 20.');
            return;
        }

        let selectedDate = new Date(date);
        let today = new Date();

        // Establecer la hora de hoy a las 00:00:00 para comparar solo las fechas
        today.setHours(0, 0, 0, 0);

        if (selectedDate <= today) {
            showErrorAlert('Oops...', 'La fecha debe ser posterior a hoy.');
            return;
        }

        // Validar que el periodo de horas sea entre las 8:00 y las 16:00
        let selectedTime = formData.get('time');
        if (selectedTime < '08:00' || selectedTime > '15:30') {
            showErrorAlert('Oops...', 'Solo puedes agendar citas entre las 8:00 AM y las 3:30 PM');
            return;
        }

        fetch('/api/appointments', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                date: date.value,
                time: selectedTime,
                number_of_assistants: number_of_assistants.value
            }),
        })
            .then(response => {
                response.json().then(data => {
                    if(data.error){
                        if (data.error.time[0].includes("cita registrada")) {
                            showErrorAlert('Oops...',
                                'Ya existe una cita registrada en esta fecha y hora, o en el rango de una hora (cada sesión dura una hora)');
                            return;
                        }
                    }

                    showSuccessAlert('¡Listo!', 'Tu cita ha sido registrada exitosamente.');
                    if (response.ok) {
                        setTimeout(() => {
                            window.location.href = '/citas';
                        }, 1000);
                    }
                })
            })
            .catch(error => {
                console.log('Error:', error);
                showErrorAlert('Oops...', 'Ocurrió un error al registrar tu cita. Por favor intenta de nuevo.');
            })
    });
});
