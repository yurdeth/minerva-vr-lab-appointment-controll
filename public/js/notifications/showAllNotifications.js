import {apiRequest} from "../utils/api.js";
import {showErrorAlert, showSuccessAlert} from "../utils/alert.js";

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

document.addEventListener('DOMContentLoaded', function () {
    apiRequest('/api/notifications', 'GET', null, headers)
        .then(response => response.json().then(data => {
            const notificationsTable = document.getElementById('notificationsTable');

            const tbody = document.createElement('tbody');
            data.notifications.forEach((item, index) => {
                const row = document.createElement('tr');

                // Agregando la numeración
                const numberTd = document.createElement('td');
                numberTd.textContent = index + 1; // La numeración comienza en 1
                row.appendChild(numberTd);

                ['from', 'type', 'description'].forEach(key => {
                    const td = document.createElement('td');
                    td.textContent = item[key] || '-';
                    row.appendChild(td);
                });

                const actionTd = document.createElement('td');
                // Botón para ver la notificación
                actionTd.innerHTML = `<a class="btn btn-primary" href="/dashboard/notificaciones/ver/${item.id}">Ver</a>`;
                // Botón para eliminar la notificación
                actionTd.innerHTML += ` <button class="btn btn-danger">Eliminar</button>`;

                row.appendChild(actionTd);
                tbody.appendChild(row);
            });

            notificationsTable.appendChild(tbody);
        }))
        .catch(error => console.error('Error al obtener las notificaciones:', error));
});
