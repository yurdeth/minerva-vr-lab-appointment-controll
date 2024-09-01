import {getResponse} from './getResponsePromise.js';

/**
 * Muestra un mensaje de confirmación para eliminar una cita.
 *
 * @param {number} id - El ID de la cita a eliminar.
 */
function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Eliminando...',
                'La cita será eliminada.',
                'success'
            );
            setTimeout(() => {
                document.getElementById(`deleteForm-${id}`).submit();
            }, 1000);
        } else {
            Swal.fire(
                'Cancelado',
                'La cita no ha sido eliminada.',
                'success'
            );
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {

    /**
     * Realiza una solicitud para obtener las citas y las muestra en una tabla.
     */
    getResponse('/appointments')
        .then(response => {
            let appointments = response.data;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            appointments.forEach(item => {
                const table = document.getElementById('appointmentsTable');
                const row = table.insertRow(-1);

                const code = row.insertCell(0);
                const name = row.insertCell(1);
                const department = row.insertCell(2);
                const career = row.insertCell(3);
                const date = row.insertCell(4);
                const time = row.insertCell(5);
                const number_of_participants = row.insertCell(6);
                const actions = row.insertCell(7);

                code.innerHTML = item.id;
                name.innerHTML = item.name;
                department.innerHTML = item.department_name;
                career.innerHTML = item.career_name;
                date.innerHTML = item.date;
                time.innerHTML = item.time;
                number_of_participants.innerHTML = item.number_of_participants;

                // Agregar botones de acciones
                actions.innerHTML = `
                    <a href="/citas/ver/${item.id}" class="btn btn-primary">Editar</a>
                    <form id="deleteForm-${item.id}" action="/appointments/eliminar/${item.id}" method="post" style="display: inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_id" id="id-${item.id}" value="${item.id}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" id="btnDelete-${item.id}" class="btn btn-danger">Eliminar</button>
                    </form>
                `;

                // Add event listener to the delete button
                document.getElementById(`btnDelete-${item.id}`).addEventListener('click', function() {
                    confirmDelete(item.id);
                });
            });

        })
        .catch(error => {
            console.log(error);
        });
});
