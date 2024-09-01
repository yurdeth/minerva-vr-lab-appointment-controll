const url = "/citas/horarios-disponibles";
let loadAvailableSchedulesDiv = document.getElementById('loadAvailableSchedules');

// Eliminar todos los elementos del div
function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

/*dateInput.addEventListener('change', function() {
    showAvailableShedules(this.value);
});*/

function showAvailableShedules() {
    const dateInput = document.querySelector('input[name="date"]').value;
    removeAllChildNodes(loadAvailableSchedulesDiv);

    fetch(`${url}?date=${dateInput}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
        .then(response => response.json())
        .then(data => {
            /*console.log("Time: " + data[0].time);
            console.log("Date: " + data[0].date);
            console.log("ID: " + data[0].id);*/
            // console.log(data);

            if (data.length > 0) {
                let p = document.createElement('p');
                p.classList.add("text-start");
                p.innerHTML = "Horarios previamente seleccionados";

                let table = document.createElement('table');
                table.classList.add("table", "table-bordered", "text-center");
                data.forEach(element => {
                    let row = table.insertRow();
                    let cell1 = row.insertCell(0);
                    let cell2 = row.insertCell(1);

                    // Formatear la fecha de element.date de YYYY-MM-DD a DD/MM/YYYY
                    cell1.innerHTML = formatDate(element.date);
                    cell2.innerHTML = element.time;
                });

                loadAvailableSchedulesDiv.appendChild(p);
                loadAvailableSchedulesDiv.appendChild(table);
            } else {
                let p = document.createElement('p');
                p.classList.add("text-center");
                p.innerHTML = "Selecciona cualquier horario dentro del rango de 8:00 AM a 3:00 PM";
                loadAvailableSchedulesDiv.appendChild(p);
            }

        })
        .catch(error => console.error('Error:', error));
}

function formatDate(dateString) {
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}
