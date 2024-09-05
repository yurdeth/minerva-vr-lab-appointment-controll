import {showAlert, showSuccessAlert, showErrorAlert, showWarningAlert} from './utils/alert.js'
import {getResponse} from './getResponsePromise.js';

/**
 * Maneja la edición de una cita.
 * @param {number} id - El ID de la cita a editar.
 */
function handleEdit(id) {
    const date = document.getElementById("date");
    const time = document.getElementById("time");
    const number_of_assistants = document.getElementById("number_of_assistants");

    const numAssistants = parseInt(number_of_assistants.value, 10);
    const selectedDate = new Date(date.value);
    const today = new Date();

    today.setHours(0, 0, 0, 0);

    if (isNaN(numAssistants) || numAssistants < 1) {
        showWarningAlert('Oops...', 'El número de asistentes no puede ser menor a 1.');
        return;
    }

    if (numAssistants > 20) {
        showWarningAlert('Oops...', 'El número de asistentes no puede ser mayor a 20.');
        return;
    }

    if (selectedDate < today) {
        showWarningAlert('Oops...', 'La fecha debe ser posterior a hoy.');
        return;
    }

    // Validar que el periodo de horas sea entre las 8:00 y las 16:00
    if (time.value < '08:00' || time.value > '15:30') {
        showWarningAlert('Oops...', 'Solo puedes agendar citas entre las 8:00 AM y las 3:30 PM');
        return;
    }

    fetch(`/api/appointments/editar/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            date: date.value,
            time: time.value,
            number_of_assistants: number_of_assistants.value
        })
    })
        .then(() => {
            showSuccessAlert(
                'Éxito',
                '¡Cita actualizada exitosamente!')
                .then(() => {
                    window.location.href = '/citas';
                });

        })
        .catch(error => {
            console.error(error);
        });
}

/**
 * Maneja la eliminación de una cita.
 * @param {number} id - El ID de la cita a eliminar.
 */
function handleDelete(id) {
    showAlert(
        'warning',
        '¡Cuidado!',
        '¿Estás seguro de que deseas eliminar esta cita?',
        true,
        'Sí, eliminar',
        'Cancelar')
        .then(result => {
            if (result.isConfirmed) {
                showSuccessAlert('Eliminando...', 'La cita será eliminada.')
                    .then(() => {
                        fetch(`/api/appointments/eliminar/${id}`, {
                            method: "DELETE",
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(() => {
                            window.location.href = '/citas';
                        }).catch(error => console.error(error));
                    });
            }else{
                showErrorAlert('Cancelado', 'Operación cancelada');
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParts = window.location.pathname.split('/');
    const id = urlParts[urlParts.length - 1];

    if (id) {
        getResponse(`/api/appointments/ver/${id}`)
            .then(response => {
                if (!response || response.length === 0) {
                    console.error('No appointment data found.');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const item = response[0];

                const name = document.getElementById('name');
                const date = document.getElementById('date');
                const time = document.getElementById('time');
                const number_of_participants = document.getElementById('number_of_assistants');

                if (!item) {
                    showErrorAlert('Oops...', 'No se encontraron datos de la cita.')
                        .then(() => {
                            window.location.href = '/citas';
                        })
                    return;
                }

                if (name) name.value = item.name;
                date.value = item.date;
                time.value = item.time;
                number_of_participants.value = item.number_of_participants;

                const actionsButtons = document.getElementById('actionsButtons');
                actionsButtons.classList.add('row');

                actionsButtons.innerHTML = `
                    <div class="d-flex justify-content-center gap-3 mt-3">

                        <form id="editForm-${item.id}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_id" id="id-${item.id}" value="${item.id}">
                            <button type="button" id="btnUpdate-${item.id}" class="btn btn-primary">Actualizar</button>
                        </form>

                        <form id="deleteForm-${item.id}" method="post">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_id" id="id-${item.id}" value="${item.id}">
                            <button type="button" id="btnDestroy-${item.id}" class="btn btn-danger">Eliminar</button>
                        </form>

                    <div>
                `;

                document.getElementById(`btnUpdate-${item.id}`).addEventListener('click', function () {
                    handleEdit(item.id);
                });

                document.getElementById(`btnDestroy-${item.id}`).addEventListener('click', function () {
                    handleDelete(item.id);
                });

            })
            .catch(error => {
                console.error(error);
            });
    }
});
