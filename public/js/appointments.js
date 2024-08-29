// Implementar una promesa:

async function getAppointments(){
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
}

async function deleteAppointment(id){
    const url = "http://127.0.0.1:8000/citas/eliminar/" + id;

    try{
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if(!response.ok){
            throw new Error('Error al eliminar la cita');
        }

        return await response.json();
    } catch (error) {
        console.log(error);
    }
}

document.addEventListener('DOMContentLoaded', function () {

    getAppointments()
        // .then(response => response.json())
        .then(response => {
            // console.log(response.data);
            let appointments = response.data;
            appointments.forEach(item => {
                console.log(item.id);
                console.log(item.name);
                console.log(item.department_name);
                console.log(item.career_name);
                console.log(item.date);
                console.log(item.time);
                console.log(item.number_of_participants);

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
                actions.innerHTML = '' +
                    '<a href="http://127.0.0.1:8000/appointments/ver/' + item.id + '" class="btn btn-primary">Editar</a> ' +
                    '<a href="http://127.0.0.1:8000/citas/eliminar/' + item.id + '" class="btn btn-danger">Eliminar</a> ';
            });

        })
        .catch(error => {
            console.log(error);
        });
});
