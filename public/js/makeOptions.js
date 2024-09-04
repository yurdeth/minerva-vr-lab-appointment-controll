import {getResponse} from './getResponsePromise.js';

let roomList = ["Sala 1", "Sala 2"];
let resourceTypelist = ["Lente", "Computadora"];
let statusList = ["Buen estado", "Mal estado", "En reparación"];

document.addEventListener("DOMContentLoaded", function () {

    //Room process
    getResponse('/api/room')
        .then((response) => {

            if (response.total === 0) {
                roomList.forEach((room, index) => {
                    fetch("/room/create", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: room,
                            id: index + 1
                        })
                    })
                        .then(() => console.log('Ok'))
                        .catch(error => console.error("Error:", error));
                });
            }
        }).catch((error) => {
        console.error("Error:", error);
    });

    //Status process
    getResponse("/api/statuses")
        .then(response => {
            if (response.total === 0) {
                console.log("No hay estados");
                statusList.forEach((status, index) => {
                    fetch("/statuses/create", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status,
                            id: index + 1
                        })
                    })
                        .then(() => console.log('Ok'))
                        .catch(error => console.error("Error:", error));
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });

    //Resource Type process
    getResponse("/api/resourcesTypes")
        .then(response => {
            if (response.total === 0) {
                console.log("No hay tipos de recursos");
                resourceTypelist.forEach((resourceType, index) => {
                    fetch("/resourcesTypes/create", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            resource_name: resourceType,
                            id: index + 1
                        })
                    })
                        .then(() => console.log('Ok'))
                        .catch(error => console.error("Error: " + error));
                });
            }
        })
        .then(data => {

        }).catch((error) => {
        console.error("Error:", error);
    });


    //Dar un tiempo para que se cree la sala
    setTimeout(function () {

        getResponse('/api/room')
            .then(response => {
                response.rooms.forEach(room => {
                    let selectRoom = document.getElementById('room');
                    let option = document.createElement('option');
                    option.value = room.id;
                    option.text = room.name;
                    selectRoom.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });

        getResponse('/api/statuses')
            .then(response => {
                response.statuses.forEach(status => {
                    let selectStatus = document.getElementById('status');
                    let option = document.createElement('option');
                    option.value = status.id;
                    option.text = status.status;
                    selectStatus.appendChild(option);
                });
            })
            .catch((error) => {
                console.error("Error:", error);
            });

        getResponse('/api/resourcesTypes')
            .then(response => {
                let selectResourceType = document.getElementById('resource_type');

                response.resourceTypes.forEach(resourceType => {
                    let option = document.createElement('option');
                    option.value = resourceType.id;
                    option.text = resourceType.resource_name;
                    selectResourceType.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }, 100);


    let formulario = document.getElementById("form_inventario");

    formulario.addEventListener("submit", function (event) {
        event.preventDefault();

        const resourceTypeId = document.getElementById("resource_type").value;
        const statusId = document.getElementById("status").value;
        const roomId = document.getElementById("room").value;

        const data = {
            resource_type_id: resourceTypeId,
            status_id: statusId,
            room_id: roomId,
            fixed_asset_code: 'ghefgriuweruif'
        };

        fetch("/resources/create", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                fixed_asset_code: 'ghefgriuweruif',
                resource_type_id: resourceTypeId,
                status_id: statusId,
                room_id: roomId,
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status === 201) {

                    Swal.fire({
                        icon: 'success',
                        iconColor: '#660D04',
                        title: 'Recurso creado',
                        text: 'Recurso creado exitosamente',
                        confirmButtonColor: '#660D04',
                    });
                    window.location.href = event.target.action;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the resource');
            });
    });
});
