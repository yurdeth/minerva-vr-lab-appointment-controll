import {showSuccessAlert, showErrorAlert} from '../utils/alert.js'
import {apiRequest} from '../utils/api.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

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
            apiRequest(`/api/appointments/eliminar/${id}`, 'DELETE', null, headers)
                .then(response => {
                    response.json().then(() => {
                        showSuccessAlert('Operación completada', 'La cita se ha eliminado correctamente')
                            .then(() => {
                                window.location.reload();
                            });
                    })
                }).catch(error => console.error(error));
        } else {
            showErrorAlert('Cancelado', 'La cita no ha sido eliminada.');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {

    /**
     * Realiza una solicitud para obtener las citas y las muestra en una tabla.
     */
    apiRequest('/api/appointments', 'GET', null, headers)
        .then(response => response.json())
        .then(data => {
            data.data.forEach(item => {
                const table = document.getElementById('appointmentsTable');
                const row = table.insertRow(-1);

                const code = row.insertCell(0);
                const name = row.insertCell(1);
                const department = row.insertCell(2);
                const career = row.insertCell(3);
                const date = row.insertCell(4);
                const time = row.insertCell(5);
                const number_of_assistants = row.insertCell(6);
                const actions = row.insertCell(7);

                // Sumar un dia a la fecha:
                const dateParts = item.date.split('-');
                const formatedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                formatedDate.setDate(formatedDate.getDate());

                // Mostrar fecha de manera textual:
                const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                const monthName = months[formatedDate.getMonth()];
                const dateText = `${formatedDate.getDate()} de ${monthName} de ${formatedDate.getFullYear()}`;

                code.innerHTML = item.id;
                name.innerHTML = item.name;
                department.innerHTML = item.department_name;
                career.innerHTML = item.career_name;
                date.innerHTML = dateText;
                time.innerHTML = item.time;
                number_of_assistants.innerHTML = item.number_of_assistants;

                actions.innerHTML = `
                        <a href="/citas/ver/${item.id}" class="btn btn-primary">Editar</a>
                        <form id="deleteForm-${item.id}" method="post" style="display: inline;">
                            <input type="hidden" name="_token" value="${headers['X-CSRF-TOKEN']}">
                            <input type="hidden" name="_id" id="id-${item.id}" value="${item.id}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" id="btnDelete-${item.id}" class="btn btn-danger">Eliminar</button>
                        </form>
                    `;

                document.getElementById(`btnDelete-${item.id}`).addEventListener('click', function () {
                    confirmDelete(item.id);
                });
            });
        })
        .catch(error => {
            console.error("Error: " + error);
        });
});
