import {getResponse} from './getResponsePromise.js';

document.addEventListener("DOMContentLoaded", event => {
    getResponse('/api/resources')
        .then(response => response) // Parse the JSON response
        .then(data => {
            console.log("Response: ", data);

            if (data.total === 0) {
                return;
            }

            let table = document.getElementById("inventoryTable").getElementsByTagName('tbody')[0];

            data.resources.forEach((resource, index) => {
                let row = table.insertRow();
                let cell1 = row.insertCell(0);
                let cell2 = row.insertCell(1);
                let cell3 = row.insertCell(2);
                let cell4 = row.insertCell(3);
                let cell5 = row.insertCell(4);
                let cell6 = row.insertCell(5);

                cell1.innerHTML = index + 1;
                cell2.innerHTML = resource.room.name;
                cell3.innerHTML = resource.resource_type.resource_name;
                cell4.innerHTML = resource.status.status;
                cell5.innerHTML = resource.fixed_asset_code;

                // Agregar botones de acciones
                cell6.innerHTML = `
                    <a href="" class="btn btn-primary">Editar</a>
                    <button type="button" class="btn btn-danger">Eliminar</button>   
                `;
            });
        })
        .catch(error => {
            console.error("Error fetching resources: ", error);
        });
});
