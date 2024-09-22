import {apiRequest} from './utils/api.js'
import {showErrorAlert, showSuccessAlert} from './utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        const body = {
            email: email,
            password: password
        };

        apiRequest('/signin', 'POST', body, headers)
            .then(function(response) {
                response.json().then(data => {
                    console.log(data);

                    if(!data.success){
                        showErrorAlert('Error', data.message);
                        return;
                    }

                    localStorage.setItem('token', data.token);
                    window.location.href = data.redirectTo;

                });
            })
            .catch(function(error) {
                console.error('Error: ', error);
            });
    });
});
