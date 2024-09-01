import {getResponse} from './getResponsePromise.js';

document.addEventListener('DOMContentLoaded', function () {
    /**
     * Función para obtener el valor de un parámetro de consulta por su nombre.
     *
     * @param {string} name - El nombre del parámetro de consulta.
     * @returns {string|null} - El valor del parámetro de consulta o null si no se encuentra.
     */
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Extraer el id de la cadena de consulta o de la ruta URL
    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    // Obtener y mostrar los datos del usuario
    getResponse(`/users/ver/${id}`)
        .then(response => {
            response.forEach(item => {
                const name = document.getElementById('name');
                const email = document.getElementById('email');
                const department_name = document.getElementById('department');
                const career = document.getElementById('career');

                name.value = item.name;
                email.value = item.email;
                department_name.value = item.department_name;
                career.value = item.career_name;
                department_name.selectedIndex = item.department_id;
                career.selectedIndex = item.career_id;
            });
        })
        .catch(error => {
            console.log(error);
        });
});
