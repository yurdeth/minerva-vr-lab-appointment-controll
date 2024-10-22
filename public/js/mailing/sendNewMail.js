import {apiRequest} from '../utils/api.js'
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js'
import {envVars} from "../envVars.js";

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const remoteApiURL = envVars().REMOTE_API_URL;

document.addEventListener('DOMContentLoaded', async function () {

    const users = await fetchMails();
    const sendMailButton = document.getElementById('sendMailButton');
    const sendPasswordButton = document.getElementById('findUserButton');

    if (sendMailButton) {
        const emailInput = document.getElementById('email'); // Referencia al input
        const subject = document.getElementById('subject');
        const message = document.getElementById('message');
        const datalist = document.getElementById('emails');

        // Llenar el datalist con las opciones
        users.forEach(user => {
            user.forEach(email => {
                const option = document.createElement('option');
                option.value = email.email;
                datalist.appendChild(option);
            });
        });

        sendMailButton.addEventListener('click', async function () {
            const selectedEmail = emailInput.value;

            if (!selectedEmail || selectedEmail === '') {
                showErrorAlert('Error', 'Por favor, introduce un correo electrónico.');
                return;
            }
            if (!subject.value || subject.value === '') {
                showErrorAlert('Error', 'Por favor, introduce un asunto.');
                return;
            }
            if (!message.value || message.value === '') {
                showErrorAlert('Error', 'Por favor, introduce un mensaje.');
                return;
            }

            await sendMail(selectedEmail, subject.value, message.value, '');
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

const fetchKey = async () => {
    let response = await fetch('/api/get-key', {
        method: 'GET',
        headers: headers
    });

    // Comprobar si la respuesta es exitosa
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }

    return await response.json();
}

const requestPassword = async (email) => {
    try {

        // Obtener el JSON de la respuesta
        let data = await fetchKey();

        let response = await fetch(`${remoteApiURL}/findByMail?email=${email}`, {
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

const fetchMails = async () => {
    try {
        // Obtener el JSON de la respuesta
        let data = await fetchKey();

        const response = await fetch(`${remoteApiURL}/getMails`, {
            method: 'GET',
            headers: {
                ...headers,
                'x-api-key': data.xKey,
            }
        });

        let dataArray = [];

        const responseData = await response.json();

        if (!responseData.success) {
            showErrorAlert('Error', responseData.message);
            return null;
        }

        dataArray.push(responseData.data);

        return dataArray;
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}
