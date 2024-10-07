import {apiRequest} from '../utils/api.js';
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js';
import {envVars} from '../envVars.js';

const remoteApiURL = envVars().REMOTE_API_URL;
const secret = envVars().API_COMMON_SECRET;

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

const getCredentials = async (email) => {
    try {
        const response = await fetch(`${remoteApiURL}/findByMail?email=${email}`, {
            method: 'GET',
            headers: {...headers, 'x-api-key': secret}
        });
        const data = await response.json();

        if (!data.success) {
            showAlert('Error', data.message);
            return null;
        }

        return data.password;
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
};

const handleSendMail = async (id, email, subject) => {
    const password = await getCredentials(email);

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
                fetch(`/api/notifications/editar/${id}`, {
                    method: 'PUT',
                    headers: headers,
                    body: JSON.stringify({ read: true })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al actualizar la notificación');
                        }
                        return response.json();
                    })
                    .then(data => console.log(data))
                    .catch(error => console.error('Error:', error));
            });
            window.location.href = '/dashboard/notificaciones';
        }))
        .catch(error => console.error('Error:', error));
};

document.addEventListener('DOMContentLoaded', function () {
    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    apiRequest(`/api/notifications/ver/${id}`, 'GET', null, headers)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
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
                    <form id="editForm-${notification.id}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" id="id-${notification.id}" value="${notification.id}">
                        <button type="button" id="btnUpdate-${notification.id}" class="btn btn-primary">${buttonMessage}</button>
                    </form>
                    <form id="deleteForm-${notification.id}" method="post">
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
                        handleSendMail(notification.id, notification.from, "Recuperación de contraseña");
                        break;
                    case 2:
                        handleSendMail(notification.id, notification.from, "Solicitud de contraseña por defecto");
                        break;
                    default:
                        buttonMessage = 'Marcar como leída';
                        break;
                }
            });

            document.getElementById(`btnDestroy-${notification.id}`).addEventListener('click', function () {
                window.location.href = '/dashboard/notificaciones';
            });
        })
        .catch(error => console.error("Error: " + error));
});
