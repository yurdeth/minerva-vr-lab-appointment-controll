// import {getResponse} from './getResponsePromise.js';

// document.addEventListener("DOMContentLoaded", event => {
//     getResponse('/api/resources')
//         .then(response => response) // Parse the JSON response
//         .then(data => {
//             console.log("Response: ", data);

//             if (data.total === 0) {
//                 return;
//             }

//             let table = document.getElementById("inventoryTable").getElementsByTagName('tbody')[0];

//             data.resources.forEach((resource, index) => {
//                 let row = table.insertRow();
//                 let cell1 = row.insertCell(0);
//                 let cell2 = row.insertCell(1);
//                 let cell3 = row.insertCell(2);
//                 let cell4 = row.insertCell(3);
//                 let cell5 = row.insertCell(4);
//                 let cell6 = row.insertCell(5);

//                 cell1.innerHTML = index + 1;
//                 cell2.innerHTML = resource.room.name;
//                 cell3.innerHTML = resource.resource_type.resource_name;
//                 cell4.innerHTML = resource.status.status;
//                 cell5.innerHTML = resource.fixed_asset_code;

//                 // Agregar botones de acciones
//                 cell6.innerHTML = `
//                     <a href="" class="btn btn-primary">Editar</a>
//                     <button type="button" class="btn btn-danger">Eliminar</button>   
//                 `;
//             });
//         })
//         .catch(error => {
//             console.error("Error fetching resources: ", error);
//         });
// });


// import {getResponse} from './getResponsePromise.js';

// document.addEventListener("DOMContentLoaded", event => {
//     let currentPage = 1;
//     const rowsPerPage = 3;
//     let inventoryData = [];

//     // Función para obtener los datos y paginarlos
//     getResponse('/api/resources')
//         .then(response => response)
//         .then(data => {
//             console.log("Response: ", data);

//             if (data.total === 0) {
//                 return;
//             }

//             // Guardar los datos del inventario en una variable
//             inventoryData = data.resources;

//             // Mostrar la primera página
//             displayTable(currentPage);

//             // Configurar paginación
//             setupPagination();
//         })
//         .catch(error => {
//             console.error("Error fetching resources: ", error);
//         });

//     // Función para mostrar la tabla de acuerdo a la página actual
//     function displayTable(page) {
//         let table = document.getElementById("inventoryTable").getElementsByTagName('tbody')[0];
//         table.innerHTML = ''; // Limpiar la tabla antes de añadir nuevos datos

//         // Calcular el índice de los elementos a mostrar en la página actual
//         let start = (page - 1) * rowsPerPage;
//         let end = start + rowsPerPage;
//         let paginatedItems = inventoryData.slice(start, end);

//         // Agregar las filas a la tabla
//         paginatedItems.forEach((resource, index) => {
//             let row = table.insertRow();
//             let cell1 = row.insertCell(0);
//             let cell2 = row.insertCell(1);
//             let cell3 = row.insertCell(2);
//             let cell4 = row.insertCell(3);
//             let cell5 = row.insertCell(4);
//             let cell6 = row.insertCell(5);

//             cell1.innerHTML = start + index + 1; // Mostrar el número de fila correcto
//             cell2.innerHTML = resource.room.name;
//             cell3.innerHTML = resource.resource_type.resource_name;
//             cell4.innerHTML = resource.status.status;
//             cell5.innerHTML = resource.fixed_asset_code;

//             // Agregar botones de acciones
//             cell6.innerHTML = `
//                 <a href="" class="btn btn-primary">Editar</a>
//                 <button type="button" class="btn btn-danger">Eliminar</button>   
//             `;
//         });
//     }

//     // Función para crear los botones de paginación
//     function setupPagination() {
//         const paginationContainer = document.getElementById('pagination');
//         paginationContainer.innerHTML = ''; // Limpiar la paginación previa

//         const totalPages = Math.ceil(inventoryData.length / rowsPerPage);

//         // Crear los botones de paginación
//         for (let i = 1; i <= totalPages; i++) {
//             const paginationButton = document.createElement('button');
//             paginationButton.classList.add('btn', 'btn-primary', 'mx-1');
//             paginationButton.setAttribute('style', 'margin-left: 5px; margin-right: 5px;');
//             paginationButton.innerText = i;
//             paginationButton.addEventListener('click', function () {
//                 currentPage = i;
//                 displayTable(currentPage); // Mostrar la tabla correspondiente a la página seleccionada
//             });

//             paginationContainer.appendChild(paginationButton);
//         }
//     }
// });


import {getResponse} from './getResponsePromise.js';

document.addEventListener("DOMContentLoaded", event => {
    let currentPage = 1;
    const rowsPerPage = 6;
    let inventoryData = [];
    
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const currentPageSpan = document.getElementById('currentPage');

    // Función para obtener los datos y paginarlos
    getResponse('/api/resources')
        .then(response => response)
        .then(data => {
            console.log("Response: ", data);

            if (data.total === 0) {
                return;
            }

            // Guardar los datos del inventario en una variable
            inventoryData = data.resources;

            // Mostrar la primera página
            displayTable(currentPage);

            // Actualizar el estado de los botones
            updatePaginationButtons();
        })
        .catch(error => {
            console.error("Error fetching resources: ", error);
        });

    // Función para mostrar la tabla de acuerdo a la página actual
    function displayTable(page) {
        let table = document.getElementById("inventoryTable").getElementsByTagName('tbody')[0];
        table.innerHTML = ''; // Limpiar la tabla antes de añadir nuevos datos

        // Calcular el índice de los elementos a mostrar en la página actual
        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;
        let paginatedItems = inventoryData.slice(start, end);

        // Agregar las filas a la tabla
        paginatedItems.forEach((resource, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);
            let cell6 = row.insertCell(5);

            cell1.innerHTML = start + index + 1; // Mostrar el número de fila correcto
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

        // Actualizar el número de página mostrado
        currentPageSpan.innerText = currentPage;
    }

    // Actualizar el estado de los botones de paginación
    function updatePaginationButtons() {
        const totalPages = Math.ceil(inventoryData.length / rowsPerPage);

        // Habilitar o deshabilitar los botones de acuerdo a la página actual
        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages;
    }

    // Manejar el botón de página anterior
    prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayTable(currentPage);
            updatePaginationButtons();
        }
    });

    // Manejar el botón de página siguiente
    nextPageButton.addEventListener('click', () => {
        const totalPages = Math.ceil(inventoryData.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayTable(currentPage);
            updatePaginationButtons();
        }
    });
});
