import {apiRequest} from '../utils/api.js'
import {showAlert, showErrorAlert, showSuccessAlert} from '../utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const career = document.getElementById('career');
const department = document.getElementById('department');

/**
 * Obtiene el valor de un parámetro de la URL.
 * @param {string} name - El nombre del parámetro.
 * @returns {string|null} - El valor del parámetro o null si no se encuentra.
 */
function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

document.addEventListener('DOMContentLoaded', function (){
    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    apiRequest(`/api/careers/ver/${id}`, 'GET', null, headers)
        .then(response => {
            response.json().then(data => {
                career.value = data[0].career_name;

                department.value = data[0].department_id;

            })
        }).catch(error => console.error("Error: " + error));
});
