import { apiRequest } from "../utils/api.js";

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

document.addEventListener('DOMContentLoaded', function () {
    apiRequest('/api/notifications', 'GET', null, headers)
        .then(response => response.json().then(data => {
            console.log(data.notifications);
            const notificationsTable = document.getElementById('notificationsTable');

            const tbody = document.createElement('tbody');
            data.notifications.forEach(item => {
                const row = document.createElement('tr');

                ['from', 'type', 'description'].forEach(key => {
                    const td = document.createElement('td');
                    td.textContent = item[key] || '-';
                    row.appendChild(td);
                });

                const actionTd = document.createElement('td');
                // Usando innerHTML para crear el botón
                actionTd.innerHTML = `<a class="btn btn-primary" href="/dashboard/notificaciones/ver/${item.id}" ">Ver</a>`;

                row.appendChild(actionTd);
                tbody.appendChild(row);
            });

            notificationsTable.appendChild(tbody);
        }))
        .catch(error => console.error('Error al obtener las notificaciones:', error));
});
