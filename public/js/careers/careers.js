import {apiRequest} from '../utils/api.js'
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

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
                apiRequest(`/api/careers/eliminar/${id}`, 'DELETE', null, headers)
                    .then(response => {
                        response.json().then(data => {
                            console.log(data);
                            if (!data.success) {
                                showErrorAlert('Error', data.message);
                            }

                            showSuccessAlert('Operación completada', 'La carrera se ha eliminado del sistema')
                                .then(() => {
                                    window.location.reload();
                                });
                        })
                    }).catch(error => console.error(error));
            } else {
                showErrorAlert('Cancelado', 'Operación cancelada');
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    apiRequest('/api/careers/', 'GET', null, headers)
        .then(response => {
            response.json().then(data => {
                data.forEach(career => {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const table = document.getElementById('careersTable');
                    const row = table.insertRow(-1);

                    const careerName = row.insertCell(0);
                    const departmentName = row.insertCell(1);
                    const actions = row.insertCell(2);

                    careerName.innerHTML = career.career_name;
                    departmentName.innerHTML = career.department_name;
                    actions.innerHTML = `
                    <a href="/carreras/ver/${career.id}" class="btn btn-primary">Editar</a>
                    <form id="deleteForm-${career.id}" method="post" style="display: inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_id" id="id-${career.id}" value="${career.id}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" id="btnDelete-${career.id}" class="btn btn-danger">Eliminar</button>
                    </form>
                `;

                    document.getElementById(`btnDelete-${career.id}`).addEventListener('click', () => {
                        confirmDelete(career.id);
                    });
                });
            })
        })
        .catch(error => console.error(error));
});
