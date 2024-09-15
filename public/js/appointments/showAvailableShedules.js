const url = "/api/appointments/available/";
let loadAvailableSchedulesDiv = document.getElementById('loadAvailableSchedules');

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

// Eliminar todos los elementos del div
function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

function showAvailableShedules() {
    const dateInput = document.querySelector('input[name="date"]').value;
    removeAllChildNodes(loadAvailableSchedulesDiv);

    if(!dateInput){
        return;
    }

    fetch(`${url}?date=${dateInput}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            response.json().then(data => {
                if (data.length > 0) {
                    let p = document.createElement('p');
                    p.classList.add("text-start");
                    p.innerHTML = "Horarios previamente seleccionados por otros usuarios para este día: ";

                    let table = document.createElement('table');
                    table.classList.add("table", "table-bordered", "text-center");
                    data.forEach(element => {
                        const dateParts = element.date.split('-');
                        const formatedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                        formatedDate.setDate(formatedDate.getDate());

                        // Mostrar fecha de manera textual:
                        const months = [
                            'Enero',
                            'Febrero',
                            'Marzo',
                            'Abril',
                            'Mayo',
                            'Junio',
                            'Julio',
                            'Agosto',
                            'Septiembre',
                            'Octubre',
                            'Noviembre',
                            'Diciembre'
                        ];
                        const monthName = months[formatedDate.getMonth()];
                        const dateText = `${formatedDate.getDate()} de ${monthName} de ${formatedDate.getFullYear()}`;

                        // Formatear la hora en formato de 12 horas
                        let timeParts = element.time.split(':');
                        let hours = timeParts[0];
                        let minutes = timeParts[1];
                        let ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;
                        let formatedTime = hours + ':' + minutes + ' ' + ampm;

                        let row = table.insertRow();
                        let cell1 = row.insertCell(0);
                        let cell2 = row.insertCell(1);

                        // Formatear la fecha de element.date de YYYY-MM-DD a DD/MM/YYYY
                        cell1.innerHTML = dateText;
                        cell2.innerHTML = formatedTime;
                    });

                    loadAvailableSchedulesDiv.appendChild(p);
                    loadAvailableSchedulesDiv.appendChild(table);
                } else {
                    let p = document.createElement('p');
                    p.classList.add("text-center");
                    p.innerHTML = "Selecciona cualquier horario dentro del rango de 8:00 AM a 3:00 PM";
                    loadAvailableSchedulesDiv.appendChild(p);
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

function formatDate(dateString) {
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}
