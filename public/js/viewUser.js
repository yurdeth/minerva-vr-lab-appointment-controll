import {getResponse} from './getResponsePromise.js';

function handleEdit(id) {
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const department = document.getElementById("department");
    const career = document.getElementById("career");
    const password = document.getElementById("password");
    const password_confirmation = document.getElementById("password_confirmation");

    if (!name.value) {
        showAlert('error', 'Por favor, ingrese su nombre.');
        return;
    }


    if (!email.value) {
        showAlert('error', 'Por favor, ingrese su correo electrónico.');
        return;
    }


    if (password.value === '' || password_confirmation.value === '') {
        showAlert('error', 'Por favor, ingrese la contraseña.');
        return;
    }

    if (password.value !== password_confirmation.value) {
        showAlert('error', 'Las contraseñas no coinciden.');
        return;
    }

    if (password.value.length < 8) {
        showAlert('error', 'La contraseña debe tener al menos 8 caracteres.');
        return;
    }

    if (password_confirmation.value.length < 8) {
        showAlert('error', 'La confirmación de la contraseña debe tener al menos 8 caracteres.');
        return;
    }

    const editUrl = `/users/editar/${id}`;

    fetch(editUrl, {
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
            department: department.value,
            career: career.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        })
    })
        .then(response => {
            // Dice que da error, pero actualiza. Mierda más rancia ;-;

            // console.log(response.json());

            Swal.fire({
                icon: 'success',
                iconColor: '#046620',
                title: '¡Perfil actualizado!',
                text: 'El perfil se ha sido actualizado.',
                confirmButtonColor: '#046620',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/usuarios';
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
    }).then(() => {
        if (text.includes('cita')) {
            window.location.href = '/citas';
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    getResponse(`/users/ver/${id}`)
        .then(response => {
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
                        <form id="deleteForm-${item.id}" action="/users/eliminar/${item.id}" method="post">
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
