import {apiRequest} from '../utils/api.js'
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const submitButton = document.getElementById('submitButton');

submitButton.addEventListener('click', function (event) {
    event.preventDefault();

    const department_name = document.getElementById('department_name').value;

    if (department_name.trim() === '') {
        showErrorAlert('Error', 'El nombre del departamento es requerido');
        return;
    }

    const body = {
        department_name: department_name
    };

    apiRequest('/api/departments/nuevo', 'POST', body, headers)
        .then(response => {
            response.json().then(data => {
                if (!data.success) {
                    showErrorAlert('Error', data.message);
                }

                showSuccessAlert('Operación completada', 'El departamento se ha creado correctamente')
                    .then(() => {
                        window.location.href = '/dashboard/carreras';
                    });
            })
        }).catch(error => console.error(error));


});
