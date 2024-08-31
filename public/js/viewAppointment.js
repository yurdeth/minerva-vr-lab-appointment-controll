async function getAppointments(id) {
    const url = `/appointments/ver/${id}`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        if (!response.ok) {
            throw new Error('Error al obtener las citas');
        }

        return await response.json();
    } catch (error) {
        console.error(error);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParts = window.location.pathname.split('/');
    const id = urlParts[urlParts.length - 1];

    if (id) {
        getAppointments(id)
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

                if (name) name.value = item.name;
                date.value = item.date;
                time.value = item.time;
                number_of_participants.value = item.number_of_participants;

                const actionsButtons = document.getElementById('actionsButtons');
                actionsButtons.classList.add('row');

                actionsButtons.innerHTML = `
                    <form id="editForm-${item.id}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-primary" onclick="handleEdit(${item.id})">Actualizar</button>
                    </form>

                    <form id="deleteForm-${item.id}" action="/appointments/eliminar/${item.id}" method="post">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-danger" onclick="handleDelete(${item.id})">Eliminar</button>
                    </form>
                `;
            })
            .catch(error => {
                console.error(error);
            });
    }
});

function handleEdit(id) {
    const date = document.getElementById("date");
    const time = document.getElementById("time");
    const number_of_assistants = document.getElementById("number_of_assistants");

    const numAssistants = parseInt(number_of_assistants.value, 10);
    const selectedDate = new Date(date.value);
    const today = new Date();

    today.setHours(0, 0, 0, 0);

    if (isNaN(numAssistants) || numAssistants < 1) {
        showAlert('error', 'El número de asistentes no puede ser menor a 1.');
        return;
    }

    if (numAssistants > 20) {
        showAlert('error', 'El número de asistentes no puede ser mayor a 20.');
        return;
    }

    if (selectedDate <= today) {
        showAlert('error', 'La fecha debe ser posterior a hoy.');
        return;
    }

    const selectedTime = time.value;
    const selectedHour = parseInt(selectedTime.split(':')[0], 10);
    if (selectedHour < 8 || selectedHour > 16) {
        showAlert('error', 'El horario de atención es de 8:00 AM a 04:00 PM');
        return;
    }

    const editUrl = `/appointments/editar/${id}`;

    fetch(editUrl, {
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
        .then(response => {
            // Esta mierda dice que da error, pero en realidad sí actualiza la data de la cita ;-;

            console.log(response);

            Swal.fire({
                icon: 'success',
                iconColor: '#046620',
                title: '¡Cita actualizada exitosamente!',
                text: 'Tu cita ha sido actualizada exitosamente.',
                confirmButtonColor: '#046620',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/citas';
            });

        })
        .catch(error => {
            console.error(error);
        });
}

function handleDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás deshacer esta acción!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`deleteForm-${id}`).submit();
        }
    });
}

function showAlert(icon, text) {
    Swal.fire({
        icon,
        iconColor: '#660D04',
        title: 'Oops...',
        text,
        confirmButtonColor: '#660D04'
    });
}
