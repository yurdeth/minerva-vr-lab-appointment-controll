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
                        .then(() => {
                            console.log('Ok');
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                });
            }
        }).catch((error) => {
        console.error("Error:", error);
    });

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
                        .then(() => {
                            console.log('Ok');
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                });
            }
        }).catch((error) => {
        console.error("Error:", error);
    });

    //Status process
    getResponse("/api/statuses")
        .then(response => {
            console.log(response);
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
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                        });
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });

    //Resource Type process
    getResponse("/api/resourcesTypes")
        .then(response => response.json())
        .then(data => {
            console.log("Esta dentro");
            if (data.total === 0) {
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
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                        });
                });
            } else {
                console.log("Hola");
            }
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
            .then(response => response.json())
            .then(data => {
                let selectStatues = document.getElementById('status');

                data.statuses.forEach(status => {
                    let option = document.createElement('option');
                    option.value = status.id;
                    option.text = status.status;
                    selectStatues.appendChild(option);
                });
            });

        getResponse('/api/resourcesTypes').then(response => response.json())
            .then(data => {
                let selectResourceType = document.getElementById('resource_type');

                data.resourceTypes.forEach(resourceType => {
                    let option = document.createElement('option');
                    option.value = resourceType.id;
                    option.text = resourceType.resource_name;
                    selectResourceType.appendChild(option);
                });
            });
    }, 300);


    let formulario = document.getElementById("form_inventario");

    formulario.addEventListener("submit", function (event) {
        event.preventDefault();

        const resourceTypeId = document.getElementById("resource_type").value;
        const statusId = document.getElementById("status").value;
        const roomId = document.getElementById("room").value;

        const data = {
            resource_type_id: resourceTypeId,
            status_id: statusId,
            room_id: roomId
        };

        fetch("/resources/create", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
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
