async function getAppointments(){
    const id = 0;
    const url = "http://127.0.0.1:8000/users/ver/" + id;

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
            response.forEach(item => {

                console.log(item);
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
