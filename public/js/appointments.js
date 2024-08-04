document.addEventListener('DOMContentLoaded', function () {
    const url = "http://127.0.0.1:8000/appointments";

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            // Obtener la referencia a la tabla
            const table = document.querySelector('table tbody');

            // Recorrer los datos y crear filas
            data.forEach((appointment, index) => {
                const row = document.createElement('tr');

                // Crear celdas y asignar valores
                const idCell = document.createElement('td');
                idCell.textContent = index + 1;
                row.appendChild(idCell);

                const userIdCell = document.createElement('td');
                userIdCell.textContent = appointment.name;
                row.appendChild(userIdCell);

                const departmentCell = document.createElement('td');
                departmentCell.textContent = appointment.department_name;
                row.appendChild(departmentCell);

                const careerCell = document.createElement('td');
                careerCell.textContent = appointment.career_name;
                row.appendChild(careerCell);

                const dateCell = document.createElement('td');
                dateCell.textContent = appointment.date;
                row.appendChild(dateCell);

                const timeCell = document.createElement('td');
                timeCell.textContent = appointment.time;
                row.appendChild(timeCell);

                const participantsCell = document.createElement('td');
                participantsCell.textContent = appointment.number_of_participants;
                row.appendChild(participantsCell);

                const statusSelect = document.createElement('select');
                statusSelect.classList.add('form-control', 'form-select');
                const statusOptions = ['Activa', 'Pendiente', 'Cancelada', 'Completada'];

                statusOptions.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status.toLowerCase();
                    option.textContent = status;

                    if (status.toLowerCase() === appointment.status.toLowerCase()) {
                        option.selected = true;
                    }

                    statusSelect.appendChild(option);
                });

                row.appendChild(statusSelect);

                const actionsCell = document.createElement('td');

                // Crear enlace de editar
                const editLink = document.createElement('a');
                editLink.href = '#';
                editLink.classList.add('btn', 'btn-primary', 'btn-sm');

                const editIcon = document.createElement('i');
                editIcon.classList.add('fas', 'fa-edit');
                editLink.appendChild(editIcon);

                editLink.addEventListener('click', () => {
                    // Lógica para editar la cita con el ID appointment.id
                    alert('Editar cita: ' + appointment.id);
                });
                actionsCell.appendChild(editLink);

                // Crear enlace de eliminar
                const deleteLink = document.createElement('a');
                deleteLink.href = '#';
                deleteLink.classList.add('btn', 'btn-danger', 'btn-sm', 'mr-2');

                const deleteIcon = document.createElement('i');
                deleteIcon.classList.add('fas', 'fa-trash-alt');
                deleteLink.appendChild(deleteIcon);

                deleteLink.addEventListener('click', () => {
                    alert('Eliminar cita: ' + appointment.id);
                });
                actionsCell.appendChild(deleteLink);

                row.appendChild(actionsCell);

                // Agregar la fila a la tabla
                table.appendChild(row);
            });
        })
        .catch(error => {
            console.log(error);
        });
});
