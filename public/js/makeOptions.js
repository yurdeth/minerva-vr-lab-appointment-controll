let roomList = ["Sala 1", "Sala 2"];
let resourceTypelist = ["Lente", "Computadora"];
let statusList = ["Buen estado", "Mal estado", "En reparación"];

document.addEventListener("DOMContentLoaded", function () {
    const url = "http://127.0.0.1:8000/api/";


    //Room process
    fetch(url + "room", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Token: "Bearer " + localStorage.getItem("token")
        }
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.total === 0) {
                roomList.forEach((room, index) => {
                    fetch(url + "room/create", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Token: "Bearer " + localStorage.getItem("token")
                        },
                        body: JSON.stringify({
                            name: room,
                            id: index + 1
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                        });
                });
            } else {
                console.log(data);
            }
        }).catch((error) => {
        console.error("Error:", error);
    });

    //Status process
    fetch(url + "statuses", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Token: "Bearer " + localStorage.getItem("token")
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log("Esta dentro");
            if (data.total === 0) {
                console.log("No hay estados");
                statusList.forEach((status, index) => {
                    fetch(url + "statuses/create", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Token: "Bearer " + localStorage.getItem("token")
                        },
                        body: JSON.stringify({
                            status_name: status,
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

    //Resource Type process
    fetch(url + "resourcesTypes", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Token: "Bearer " + localStorage.getItem("token")
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log("Esta dentro");
            if (data.total === 0) {
                console.log("No hay tipos de recursos");
                resourceTypelist.forEach((resourceType, index) => {
                    fetch(url + "resourcesTypes/create", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Token: "Bearer " + localStorage.getItem("token")
                        },
                        body: JSON.stringify({
                            resource_type_name: resourceType,
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
        // Código a ejecutar después del retraso
        console.log("Este mensaje se mostrará después de 2 segundos");

        fetch(url + "room", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
                let selectRoom = document.getElementById('room');

                data.rooms.forEach(room => {
                    let option = document.createElement('option');
                    option.value = room.id;
                    option.text = room.name;
                    selectRoom.appendChild(option);
                });
            });

        fetch(url + "statuses", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
            .then(response => response.json())
            .then(data => {
                let selectStatues = document.getElementById('status_name');

                data.statuses.forEach(status => {
                    let option = document.createElement('option');
                    option.value = status.id;
                    option.text = status.status_name;
                    selectStatues.appendChild(option);
                });
            });

        fetch(url + "resourcesTypes", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
                let selectResourceType = document.getElementById('resource_type');

                data.resourceTypes.forEach(resourceType => {
                    let option = document.createElement('option');
                    option.value = resourceType.id;
                    option.text = resourceType.resource_type_name;
                    selectResourceType.appendChild(option);
                });
            });
    }, 300);


    let formulario = document.getElementById("form_inventario");

    formulario.addEventListener("submit", function (event) {
        event.preventDefault();

        const resourceTypeId = document.getElementById("resource_type").value;
        const statusId = document.getElementById("status_name").value;
        const roomId = document.getElementById("room").value;

        const data = {
            resource_type_id: resourceTypeId,
            status_id: statusId,
            room_id: roomId
        };

        fetch(url + "resources/create", {
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
