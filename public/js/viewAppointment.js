async function getAppointments(){
    const id = 2;
    const url = "http://127.0.0.1:8000/appointments/ver/" + id;

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

                // console.log(item);
                const name = document.getElementById('name');
                const date = document.getElementById('date');
                const time = document.getElementById('time');
                const number_of_participants = document.getElementById('number_of_assistants');

                date.value = item.date;
                time.value = item.time;
                number_of_participants.value = item.number_of_participants;

                if(name){
                    name.value = item.name;
                }
            });

        })
        .catch(error => {
            console.log(error);
        });
});
