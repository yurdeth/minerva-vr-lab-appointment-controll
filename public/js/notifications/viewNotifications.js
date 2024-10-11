import {apiRequest} from '../utils/api.js';
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js';
import {envVars} from '../envVars.js';

const remoteApiURL = envVars().REMOTE_API_URL;
const xKey = envVars().API_COMMON_SECRET;

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

document.addEventListener('DOMContentLoaded', function () {
    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    apiRequest(`/api/notifications/ver/${id}`, 'GET', null, headers)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showErrorAlert('Oops...', 'No se encontraron datos de la notificacion.')
                    .then(() => {
                        window.location.href = '/dashboard/notificaciones';
                    });
                return;
            }

            const notification = data.notifications[0];
            const email = document.getElementById('email');
            const asunto = document.getElementById('asunto');

            email.value = notification.from;
            asunto.value = notification.description || notification.type;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const actionsButtons = document.getElementById('actionsButtons');

            let buttonMessage;
            switch (notification.type_id) {
                case 1:
                    buttonMessage = 'Recuperar contraseña';
                    break;
                case 2:
                    buttonMessage = 'Enviar clave';
                    break;
                default:
                    buttonMessage = 'Marcar como leída';
                    break;
            }

            actionsButtons.innerHTML = `
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <form id="editForm-${notification.id}" class="d-inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" id="id-${notification.id}" value="${notification.id}">
                        <button type="button" id="btnUpdate-${notification.id}" class="btn btn-primary">${buttonMessage}</button>
                    </form>
                    <form id="deleteForm-${notification.id}" method="post" class="d-inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_id" id="id-${notification.id}" value="${notification.id}">
                        <button type="button" id="btnDestroy-${notification.id}" class="btn btn-danger">Cancelar</button>
                    </form>
                </div>
            `;

            document.getElementById(`btnUpdate-${notification.id}`).addEventListener('click', function () {
                switch (notification.type_id) {
                    case 1:
                        handleEditProfile(notification.id, notification.from, "Recuperación de contraseña");
                        break;
                    case 2:
                        getCredentials(notification.id, notification.from, "Solicitud de contraseña por defecto");
                        break;
                    default:
                        buttonMessage = 'Marcar como leída';
                        markAsread(notification.id);
                        break;
                }
            });

            document.getElementById(`btnDestroy-${notification.id}`).addEventListener('click', function () {
                window.location.href = '/dashboard/notificaciones';
            });
        })
        .catch(error => console.error("Error: " + error));
});

const getCredentials = async (notification_id = null, email, subject = null) => {
    try {
        let response = await fetch('/api/get-key', {
            method: 'GET',
            headers: headers
        });

        // Comprobar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        // Obtener el JSON de la respuesta
        let data = await response.json();
        console.log('Key sent: ' + data.xKey);

        response = await fetch(`${remoteApiURL}/findByMail?email=${email}`, {
            method: 'GET',
            headers: {...headers,
                'x-api-key': data.xKey,
            }
        });
        data = await response.json();

        if (!data.success) {
            showErrorAlert('Error', data.message);
            return null;
        }

        sendMail(notification_id, email, subject, data.password);
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
};

const handleEditProfile = async (id, email, subject) => {
    const passwordForms = document.getElementById('passwordForms');
    passwordForms.innerHTML = "";

    const newPassword = document.createElement('input');
    newPassword.type = 'password';
    newPassword.classList.add('form-control', 'mb-3');
    newPassword.placeholder = 'Ingresa la nueva contraseña';
    passwordForms.appendChild(newPassword);

    const repeatPassword = document.createElement('input');
    repeatPassword.type = 'password';
    repeatPassword.classList.add('form-control', 'mb-3');
    repeatPassword.placeholder = 'Repita la nueva contraseña';
    passwordForms.appendChild(repeatPassword);

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('d-flex', 'justify-content-between', 'gap-2');

    const recoverPasswordButton = document.createElement('button');
    recoverPasswordButton.type = 'submit';
    recoverPasswordButton.classList.add('btn', 'btn-success', 'w-50');
    recoverPasswordButton.textContent = "Actualizar";
    buttonContainer.appendChild(recoverPasswordButton);

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.classList.add('btn', 'btn-danger', 'w-50');
    cancelButton.textContent = "Cancelar";
    buttonContainer.appendChild(cancelButton);

    passwordForms.appendChild(buttonContainer);

    recoverPasswordButton.addEventListener('click', function () {
        apiRequest(`/api/users/ver/email/${email}`, 'GET', null, headers)
            .then(response => response.json().then(data => {
                console.log(data.user);

                if (!data.success) {
                    showErrorAlert('Error', 'Ocurrió un error al procesar la petición').then(() => {
                        window.location.href = '/dashboard/notificaciones'
                    });
                }

                let name = data.user.name;
                let career_id = data.user.career_id;

                validateNewPassword(id, newPassword.value, repeatPassword.value, name, career_id, email);
            })).catch(error => console.error(error));
    });
};

const validateNewPassword = async (notification_id, newPassword, repeatPassword, name, career_id, email) => {
    try {
        const response = await fetch(`/api/users/ver/email/${email}`, {
            method: 'GET',
            headers: headers
        });
        const data = await response.json();
        const id = data.user.id;

        console.log(id);

        const body = {
            id: id,
            name: name,
            email: email,
            career: career_id,
            password: newPassword,
            password_confirmation: repeatPassword
        };

        const updateResponse = await fetch(`/api/users/editar/${body.id}`, {
            method: 'PUT',
            body: JSON.stringify(body),
            headers: headers
        });
        const updateData = await updateResponse.json();
        if (!updateData.success) {
            showErrorAlert('Error', updateData.message);
            return;
        }

        showSuccessAlert('Operación completada', updateData.message).then((result) => {
            if (result.isConfirmed) {
                sendMail(notification_id, body.email, "Recuperación de contraseña", newPassword);
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const sendMail = async (notification_id, email, subject, password) => {

    const body = {
        subject: subject,
        email: email,
        password: password
    };

    if (!password) {
        showErrorAlert('Error', 'Usuario no encontrado.').then(() => {
            window.location.href = '/dashboard/notificaciones';
        });
        return;
    }

    fetch(`/api/sendmail/`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(body)
    })
        .then(response => response.json().then(data => {
            if (!data.success) {
                showErrorAlert('Error', data.message);
                return;
            }
            showSuccessAlert('Correo enviado', data.message).then(() => {
                markAsread(notification_id);
            });
        }))
        .catch(error => console.error('Error:', error));
};

const markAsread = (id) => {
    apiRequest(`/api/notifications/editar/${id}`, 'PUT', null, headers)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al actualizar la notificación');
            }
            return response.json().then(data => {
                if(!data.success){
                    showErrorAlert('Error', data.message);
                    return;
                }

                window.location.href = '/dashboard/notificaciones';
            })
        })
        .catch(error => console.error('Error:', error));
}
