import {showErrorAlert, showSuccessAlert, showWarningAlert} from './utils/alert.js';

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};

document.addEventListener('DOMContentLoaded', function (event) {
    event.preventDefault();

    const submitButton = document.getElementById('submitButton');
    const cancelButton = document.getElementById('cancelButton');
    const selected = document.getElementById("tipo");
    let thereIsTextArea = false;

    selected.addEventListener('change', function () {
        if (selected.value === "3") {
            const otherOption = document.getElementById('other-option');

            const textArea = document.createElement('textarea');
            textArea.classList.add('form-control');
            textArea.placeholder = 'Escribe tu mensaje aquí (máximo 100 caracteres)';
            textArea.id = 'description';
            textArea.maxLength = 100;
            otherOption.appendChild(textArea);
            thereIsTextArea = true;

            textArea.addEventListener('input', function () {
                if (textArea.value.length === 100) {
                    showWarningAlert('Advertencia', 'Has llegado al límite de caracteres permitidos.');
                }
            });

            return;
        }

        const otherOption = document.getElementById('other-option');
        thereIsTextArea = false;
        otherOption.innerHTML = '';
    });

    cancelButton.addEventListener('click', function () {
        window.location.href = '/inicio';
    });

    submitButton.addEventListener('click', function (event) {
        event.preventDefault();

        const from = document.getElementById("email").value;
        const selectedValue = selected.value;

        const body = {
            from: from,
            type_id: selectedValue,
            description: thereIsTextArea ? document.getElementById("description").value : null
        };

        fetch('/enviar-solicitud', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(body)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (!data.success) {
                    if (data.error.from) {
                        showErrorAlert('Error', "Por favor, ingresa tu correo electrónico.");
                        return;
                    }
                    if (data.error.type_id) {
                        showErrorAlert('Error', "Por favor, selecciona un motivo de contacto.");
                        return;
                    }
                    if (data.error.description) {
                        showErrorAlert('Error', "Por favor, agrega una descripción del problema.");
                    }

                    showErrorAlert('Error', 'Hubo un error al enviar el mensaje al administrador.');
                } else {
                    showSuccessAlert('Éxito', 'Mensaje enviado correctamente al administrador.').then(() => {
                        window.location.href = '/inicio';
                    });
                }
            })
            .catch(error => {
                showErrorAlert('error', error);
            });
    });
});
