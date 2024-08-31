async function getAppointments(){
    const url = "http://127.0.0.1:8000/users";

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

document.addEventListener('DOMContentLoaded', function () {

    getAppointments()
        // .then(response => response.json())
        .then(response => {
            // console.log(response);
            response.forEach(item => {

                const table = document.getElementById('usersTable');
                const row = table.insertRow(-1);

                const code = row.insertCell(0);
                const name = row.insertCell(1);
                const email = row.insertCell(2);
                const department_name = row.insertCell(3);
                const career_name = row.insertCell(4);
                const actions = row.insertCell(5);

                code.innerHTML = item.id;
                name.innerHTML = item.name;
                email.innerHTML = item.email;
                department_name.innerHTML = item.department_name;
                career_name.innerHTML = item.career_name;
                actions.innerHTML = '' +
                    '<a href="http://127.0.0.1:8000/usuarios/ver/' + item.id + '" class="btn btn-primary">Editar</a> ';
            });

        })
        .catch(error => {
            console.log(error);
        });
});
