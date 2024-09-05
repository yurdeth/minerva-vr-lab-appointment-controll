import {showAlert, showSuccessAlert, showErrorAlert, showWarningAlert} from './utils/alert.js'
import {getResponse} from './getResponsePromise.js';

/**
 * Maneja la edición de un usuario.
 * @param {number} id - El ID del usuario a editar.
 */
function handleEdit(id) {
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const career = document.getElementById("career");
    const password = document.getElementById("password");
    const password_confirmation = document.getElementById("password_confirmation");

    if (!name.value) {
        showWarningAlert('error', 'Por favor, ingrese su nombre.');
        return;
    }

    if (!email.value) {
        showWarningAlert('error', 'Por favor, ingrese su correo electrónico.');
        return;
    }

    if (password.value === '' || password_confirmation.value === '') {
        showWarningAlert('error', 'Por favor, ingrese la contraseña.');
        return;
    }

    if (password.value !== password_confirmation.value) {
        showWarningAlert('error', 'Las contraseñas no coinciden.');
        return;
    }

    if (password.value.length < 8) {
        showWarningAlert('error', 'La contraseña debe tener al menos 8 caracteres.');
        return;
    }

    if (password_confirmation.value.length < 8) {
        showWarningAlert('error', 'La confirmación de la contraseña debe tener al menos 8 caracteres.');
        return;
    }

    fetch(`/api/users/editar/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name.value,
            email: email.value,
            career: career.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        })
    })
        .then(() => {
            showSuccessAlert(
                '¡Perfil actualizado!',
                'El perfil se ha sido actualizado.')
                .then(() => {
                    window.location.href = '/usuarios';
                });

        })
        .catch(error => {
            console.error(error);
        });
}

/**
 * Maneja la eliminación de un usuario.
 * @param {number} id - El ID del usuario a eliminar.
 */
function handleDelete(id) {
    showAlert(
        'warning',
        '¿Estás seguro?',
        '¡No podrás deshacer esta acción!',
        true,
        'Sí, eliminar',
        'Cancelar')
        .then(result => {
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
                            window.location.href = '/usuarios';
                        }).catch(error => console.error(error));
                    });
            } else {
                showErrorAlert('Cancelado', 'Operación cancelada');
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    /**
     * Obtiene el valor de un parámetro de la URL.
     * @param {string} name - El nombre del parámetro.
     * @returns {string|null} - El valor del parámetro o null si no se encuentra.
     */
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    getResponse(`/api/users/ver/${id}`)
        .then(response => {
            if (response.length === 0) {
                showErrorAlert('Oops...', 'No se encontraron datos del usuario.')
                    .then(() => {
                        window.location.href = '/usuarios';
                    })
                return;
            }

            response.forEach(item => {

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const name = document.getElementById('name');
                const email = document.getElementById('email');
                const department_name = document.getElementById('department');
                const career = document.getElementById('career');

                name.value = item.name;
                email.value = item.email;
                department_name.value = item.department_name;
                career.value = item.career_name;
                department_name.selectedIndex = item.department_id;
                career.selectedIndex = item.career_id;

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
                    </div>
                `;

                document.getElementById(`btnUpdate-${item.id}`).addEventListener('click', function () {
                    handleEdit(item.id);
                });

                document.getElementById(`btnDestroy-${item.id}`).addEventListener('click', function () {
                    handleDelete(item.id);
                });
            });
        })
        .catch(error => {
            console.log(error);
        });
});
