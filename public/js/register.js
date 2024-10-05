import {apiRequest} from './utils/api.js'
import {showErrorAlert, showSuccessAlert} from './utils/alert.js'

const remoteApiURL = process.env.REMOTE_API_URL;
const secret = process.env.API_COMMON_SECRET;

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'x-api-key': secret
};

const name = document.getElementById('name');
const email = document.getElementById('email');
const department = document.getElementById('department');
const career = document.getElementById('career');
const password = document.getElementById('password');
const passwordConfirmation = document.getElementById('password_confirmation');

document.addEventListener('DOMContentLoaded', function () {

    loadInfo();

    document.getElementById('updateButton').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma convencional

        const body = {
            name: name.value,
            email: email.value,
            career: career.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value
        };

        apiRequest('/signup', 'POST', body, headers)
            .then(function (response) {
                response.json().then(data => {
                    if(!data.success){
                        if (data.error) {
                            if (data.error.career) {
                                if (data.error.career[0].includes("required")) {
                                    showErrorAlert('Error', 'Por favor, seleccione una carrera');
                                    return;
                                }

                                showErrorAlert('Error', 'Error al procesar la carrera');
                            }

                            if (data.error.email) {
                                if (data.error.email[0].includes("required")) {
                                    showErrorAlert('Error', 'Por favor, ingrese su correo electrónico');
                                    return;
                                }

                                if (data.error.email[0].includes("email has already")) {
                                    showErrorAlert('Error', 'Este correo ya está registrado. Por favor intenta con otro');
                                    return;
                                }

                                if (data.error.email[0].includes("correos universitarios")) {
                                    showErrorAlert('Error', 'Solo se permiten correos universitarios');
                                    return;
                                }

                                showErrorAlert('Error', 'Error al procesar el correo electrónico');
                            }

                            if(data.error.name) {
                                if (data.error.name[0].includes("required")) {
                                    showErrorAlert('Error', 'Por favor, ingrese su nombre');
                                    return;
                                }

                                showErrorAlert('Error', 'Error al procesar el nombre');
                            }

                            if(data.error.password) {
                                if (data.error.password[0].includes("required")) {
                                    showErrorAlert('Error', 'Por favor, ingrese una contraseña');
                                    return;
                                }

                                if (data.error.password[0].includes("The password field must be at least 8 characters")) {
                                    showErrorAlert('Error', 'La contraseña debe tener al menos 8 caracteres');
                                    return;
                                }

                                showErrorAlert('Error', 'Error al procesar la contraseña');
                            }
                        } else{
                            showErrorAlert('Error', data.message);
                        }
                        return;
                    }

                    showSuccessAlert('Éxito', "Usuario registrado correctamente")
                        .then(() => {
                            changeStatus();
                            localStorage.setItem('token', data.token);
                            localStorage.removeItem('user_id');
                            window.location.href = data.redirect_to;
                        });
                });
            })
            .catch(function (error) {
                // Manejar el error de la solicitud
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonColor: '#660D04',
                });
            });
    });
});

const loadInfo = () => {
    const id = localStorage.getItem('user_id');

    apiRequest(`${remoteApiURL}/users/${id}`, 'GET', null, headers)
        .then(response => response.json().then(data => {
            console.log(data);

            if (!data || !data.success) {
                showErrorAlert('Oops...', 'No se encontraron datos del usuario.')
                    .then(() => {
                        window.location.href = data.redirectTo;
                    });
                return;
            }

            let user = data.data;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const department_name = document.getElementById('department');
            const career = document.getElementById('career');

            name.value = user.name;
            email.value = user.email;
            department_name.value = user.department_id;
            career.innerHTML = '';

            let option = document.createElement('option');
            option.value = '';
            option.text = 'Seleccione una carrera';
            career.appendChild(option);

            apiRequest(`/api/careers/${user.department_id}`, 'GET', null, headers)
                .then(response => response.json())
                .then(data => {
                    data.forEach(d => {
                        let option = document.createElement('option');
                        option.value = d.id;
                        option.text = d.career_name;
                        career.appendChild(option);
                    });
                    career.value = user.career_id;
                })
                .catch((error) => {
                    console.error('Error:', error);
                });

        }))
        .catch(error => {
            console.log(error);
        });

}

const changeStatus = () => {
    const id = localStorage.getItem('user_id');

    apiRequest(`${remoteApiURL}/users/${id}`, 'PUT', null, headers)
        .then(response => response.json().then(data => {

            if(!data.success){
                showErrorAlert('Error', data.message);
            }
        }))
        .catch(error => {
            console.log(error);
        });

}
