import {getResponse} from './getResponsePromise.js';

/*async function getAppointments(){
    const url = "http://127.0.0.1:8000/appointments";

    try{
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if(!response.ok){
            throw new Error('Error al obtener las citas');
        }

        return await response.json();
    } catch (error) {
        console.log(error);
    }
}*/

document.addEventListener('DOMContentLoaded', function () {

    getResponse('/appointments')
        .then(response => {
            let appointments = response.data;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            appointments.forEach(item => {
                const table = document.getElementById('appointmentsTable');
                const row = table.insertRow(-1);

                const code = row.insertCell(0);
                const name = row.insertCell(1);
                const department = row.insertCell(2);
                const career = row.insertCell(3);
                const date = row.insertCell(4);
                const time = row.insertCell(5);
                const number_of_participants = row.insertCell(6);
                const actions = row.insertCell(7);

                code.innerHTML = item.id;
                name.innerHTML = item.name;
                department.innerHTML = item.department_name;
                career.innerHTML = item.career_name;
                date.innerHTML = item.date;
                time.innerHTML = item.time;
                number_of_participants.innerHTML = item.number_of_participants;

                actions.innerHTML = `
                    <a href="http://127.0.0.1:8000/citas/ver/${item.id}" class="btn btn-primary">Editar</a>
                    <form id="deleteForm-${item.id}" action="http://127.0.0.1:8000/appointments/eliminar/${item.id}" method="post" style="display: inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete(${item.id})">Eliminar</button>
                    </form>
                `;
            });

        })
        .catch(error => {
            console.log(error);
        });
});

function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`deleteForm-${id}`).submit();
        }
        else{
            Swal.fire(
                'Cancelado',
                'La cita no ha sido eliminada.',
                'success'
            );
        }
    });
}
