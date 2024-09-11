import {apiRequest} from '../utils/api.js'
import {showErrorAlert, showSuccessAlert} from '../utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const department_name_input = document.getElementById('department_name');
const submitButton = document.getElementById('submitButton');

function submitForm() {
    const department_name = department_name_input.value;

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
                    if (data.error){
                        if(data.error.department_name[0].includes('has already been taken')){
                            showErrorAlert('Error', 'El nombre del departamento ya ha sido registrado');
                            return;
                        }

                        showErrorAlert('Error', "Ha ocurrido un error al intentar crear el departamento");
                        return;
                    }

                    showErrorAlert('Error', data.message);
                    return;
                }

                showSuccessAlert('Operación completada', 'El departamento se ha creado correctamente')
                    .then(() => {
                        window.location.href = '/dashboard/carreras';
                    });
            })
        }).catch(error => console.error(error));
}

submitButton.addEventListener('click', function (event) {
    event.preventDefault();
    submitForm();
});

department_name_input.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        submitForm();
    }
});
