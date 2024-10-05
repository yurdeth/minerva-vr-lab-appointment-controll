import {showErrorAlert, showSuccessAlert} from './utils/alert.js';
import {apiRequest} from "./utils/api.js";

const remoteApiURL = process.env.REMOTE_API_URL;
const adminEmail = process.env.ADMIN_EMAIL;
const secret = process.env.API_COMMON_SECRET;

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'x-api-key': secret
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        const body = {
            email: email,
            password: password
        };

        if (email === adminEmail) {
            login(body);
        }else{
            fetch(`${remoteApiURL}/verifyUser?email=${email}&password=${password}`, {
                method: 'GET',
                headers: headers
            })
                .then(response => {
                    response.json().then(data => {

                        if (data.enabled){
                            login(body);
                            return;
                        }

                        if (!data.success) {
                            showErrorAlert('Error', data.message);
                            return;
                        }

                        showSuccessAlert('Éxito', 'Usuario activado').then(() => {
                            localStorage.setItem('user_id', data.user.id);
                            window.location.href = '/actualizar-informacion';
                        });
                    });
                })
                .catch(error => {
                    console.error('Error: ', error);
                });
        }

    });
});

const login = (body) => {
    apiRequest('/signin', 'POST', body, headers)
        .then(function (response) {
            response.json().then(data => {
                if (!data.success) {
                    showErrorAlert('Error', data.message);
                    return;
                }

                localStorage.setItem('token', data.token);
                window.location.href = data.redirectTo;

            });
        })
        .catch(function (error) {
            console.error('Error: ', error);
        });
};
