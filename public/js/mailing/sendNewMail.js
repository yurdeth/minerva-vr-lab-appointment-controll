import {apiRequest} from '../utils/api.js'
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js'
import {envVars} from "../envVars.js";
import {send} from "vite";

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const remoteApiURL = envVars().REMOTE_API_URL;

document.addEventListener('DOMContentLoaded', function () {

    const sendMailButton = document.getElementById('sendMailButton');
    const sendPasswordButton = document.getElementById('findUserButton');

    if (sendMailButton) {
        sendMailButton.addEventListener('click', async function () {
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;

            if (!email || email === '') {
                showErrorAlert('Error', 'Por favor, introduce un correo electrónico.');
                return;
            }

            if (!subject || subject === '') {
                showErrorAlert('Error', 'Por favor, introduce un asunto.');
                return;
            }

            if (!message || message === '') {
                showErrorAlert('Error', 'Por favor, introduce un mensaje.');
                return;
            }

            await sendMail(email, subject, message, '');
        });
    }

    if (sendPasswordButton) {
        sendPasswordButton.addEventListener('click', async function () {
            const email = document.getElementById('email').value;

            if (!email || email === '') {
                showErrorAlert('Error', 'Por favor, introduce un correo electrónico.');
                return;
            }

            await requestPassword(email);
        });
    }
});

const requestPassword = async (email) => {
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

        response = await fetch(`${remoteApiURL}/findByMail?email=${email}`, {
            method: 'GET',
            headers: {
                ...headers,
                'x-api-key': data.xKey,
            }
        });
        data = await response.json();

        if (!data.success) {
            showErrorAlert('Error', data.message);
            return null;
        }

        const password = await fetchData(data.password);

        await sendMail(
            email,
            "Clave de acceso",
            "Tu clave de acceso por defecto es: ",
            password);
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}

const fetchData = async (value) => {
    const body = {
        value: value
    };

    try {
        const response = await fetch(`/api/get-decrypted/`, {
            method: 'POST',
            body: JSON.stringify(body),
            headers: headers
        });

        const data = await response.json();

        if (!data.success) {
            showErrorAlert('Error', data.message);
            return null;
        }

        return data.password;
    } catch (error) {
        console.error(error);
        return null;
    }
};

const sendMail = async (email, subject, message, password) => {

    const body = {
        subject: subject,
        email: email,
        message: message,
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
                window.location.href = '/dashboard/';
            });
        }))
        .catch(error => console.error('Error:', error));
};
