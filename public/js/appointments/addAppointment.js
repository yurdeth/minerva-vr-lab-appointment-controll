import {showSuccessAlert, showErrorAlert} from '../utils/alert.js'
import {apiRequest} from "../utils/api.js";

const min_limit = 1;
const max_limit = 150;

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

        if (number_of_assistants < min_limit) {
            showErrorAlert('Oops...', `El número de asistentes no puede ser menor a ${min_limit}.`);
            return;
        }

        if (number_of_assistants > max_limit) {
            showErrorAlert('Oops...', `El número de asistentes no puede ser mayor a ${max_limit}.`);
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

        let startTime = document.getElementById('start-time');
        let endTime = document.getElementById('end-time');

        if (startTime.value < '08:00' || endTime.value > '17:00') {
            showErrorAlert('Oops...', 'Solo puedes agendar citas entre las 8:00 AM y las 5:00 PM');
            return;
        }

        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        const body = {
            date: date.value,
            start_time: startTime.value,
            end_time: endTime.value,
            number_of_assistants: number_of_assistants.value
        };

        apiRequest('/api/appointments', 'POST', body, headers)
            .then(response => response.json().then(data => {
                console.log(data);

                    if (data.error) {
                        console.error(data.error);

                        if (data.error.time && data.error.time[0].includes("cita registrada")) {
                            showErrorAlert('Oops...',
                                'Ya existe una cita registrada en esta fecha y hora, o en el rango de una hora (cada sesión dura una hora)');
                            return;
                        }

                        if (data.error.date && data.error.date[0].includes("after today")) {
                            showErrorAlert('Oops...',
                                'La cita debe ser una fecha posterior a hoy');
                            return;
                        }

                        if (data.error.end_time && data.error.end_time[0].includes("rango de horario")) {
                            showErrorAlert('Oops...',
                                'Ya existe una cita registrada en este rango de horario');
                            return;
                        }
                    }

                    showSuccessAlert('¡Listo!', 'Tu cita ha sido registrada exitosamente.').then(() => {
                        stop();
                        window.location.href = data.redirect_to;
                    }).catch(error => console.error(error));
                })
            )
            .catch(error => {
                console.log('Error:', error);
            })
    });
});
