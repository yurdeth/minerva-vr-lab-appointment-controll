import {showAlert, showSuccessAlert, showErrorAlert} from './utils/alert.js'
import {getResponse} from './getResponsePromise.js';

/**
 * Muestra un mensaje de confirmación para eliminar una cita.
 *
 * @param {number} id - El ID de la cita a eliminar.
 */
function confirmDelete(id) {
    showAlert(
        'warning',
        '¿Estás seguro?',
        "¡No podrás revertir esto!",
        true,
        'Sí, eliminar',
        'Cancelar')
        .then((result) => {
            if (result.isConfirmed) {
                showSuccessAlert('Eliminando', 'El usuario será eliminado del sistema')
                    .then(() => {
                        fetch(`/api/users/eliminar/${id}`, {
                            method: "DELETE",
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(() => {
                            window.location.reload();
                        }).catch(error => console.error(error));
                    });

            } else {
                showErrorAlert('Cancelado', 'Operación cancelada');
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {

    getResponse('/api/users')
        // .then(response => response.json())
        .then(response => {
            // console.log(response);
            response.forEach(item => {

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const table = document.getElementById('usersTable');
                const row = table.insertRow(-1);

                const code = row.insertCell(0);
                const name = row.insertCell(1);
                const email = row.insertCell(2);
                const department_name = row.insertCell(3);
                const career_name = row.insertCell(4);
                const actions = row.insertCell(5);

                code.innerHTML = item.id;
                name.innerHTML = item.name;
                email.innerHTML = item.email;
                department_name.innerHTML = item.department_name;
                career_name.innerHTML = item.career_name;

                actions.innerHTML = `
                    <a href="/usuarios/ver/${item.id}" class="btn btn-primary">Editar</a>
                    <form id="deleteForm-${item.id}" method="post" style="display: inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
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
            console.log(error);
        });
});
