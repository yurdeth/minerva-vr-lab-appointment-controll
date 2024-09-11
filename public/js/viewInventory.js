import {apiRequest} from './utils/api.js'
import {showSuccessAlert, showErrorAlert, showAlert} from './utils/alert.js'

const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const room = document.getElementById('room');
const resource_type = document.getElementById('resource_type');
const status = document.getElementById('status');
const fixed_asset_code = document.getElementById('fixed_asset_code');
const submitUpdate = document.getElementById('submitUpdate');
const submitDelete = document.getElementById('submitDelete');

function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

document.addEventListener("DOMContentLoaded", function () {
    //Dar un tiempo para que se cree la sala
    setTimeout(function () {
        apiRequest('/api/room', 'GET', null, headers)
            .then(response => {
                response.json().then(data => {
                    data.rooms.forEach(room => {
                        let selectRoom = document.getElementById('room');
                        let option = document.createElement('option');
                        option.value = room.id;
                        option.text = room.name;
                        selectRoom.appendChild(option);
                    });
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });

        apiRequest('/api/resourcesTypes', 'GET', null, headers)
            .then(response => {
                response.json().then(data => {
                    data.resourceTypes.forEach(resourceType => {
                        let selectResourceType = document.getElementById('resource_type');
                        let option = document.createElement('option');
                        option.value = resourceType.id;
                        option.text = resourceType.resource_name;
                        selectResourceType.appendChild(option);
                    });
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });

        apiRequest('/api/statuses', 'GET', null, headers)
            .then(response => {
                response.json().then(data => {
                    data.statuses.forEach(status => {
                        let selectStatus = document.getElementById('status');
                        let option = document.createElement('option');
                        option.value = status.id;
                        option.text = status.status;
                        selectStatus.appendChild(option);
                    });
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }, 100);

    let id = getQueryParam('id');
    if (!id) {
        let urlParts = window.location.pathname.split('/');
        id = urlParts[urlParts.length - 1];
    }

    apiRequest(`/api/resources/ver/${id}`, 'GET', null, headers)
        .then(response => {
            response.json().then(data => {

                if (data.message === 'Resource not found') {
                    showErrorAlert('Oops...', 'No se encontraron datos del recurso.')
                        .then(() => {
                            window.location.href = '/dashboard/inventario';
                        });
                    return;
                }

                fixed_asset_code.value = data.resource.fixed_asset_code;
                status.value = data.resource.status.id;
                resource_type.value = data.resource.resource_type.id;
                room.value = data.resource.room.id;
            })
        })
        .catch(error => {
            console.error('Error:', error);
        });

    submitDelete.addEventListener('click', function () {
        showAlert(
            'warning',
            'Eliminar recurso',
            '¿Está seguro de que desea eliminar el recurso?',
            true,
            'Sí, eliminar',
            'Cancelar'
        ).then((result) => {
            if (result.isConfirmed) {
                apiRequest(`/api/resources/eliminar/${id}`, 'DELETE', null, headers)
                    .then(response => {
                        response.json().then(data => {
                            if (data.success) {
                                showSuccessAlert('Operación completada', 'El recurso se ha eliminado correctamente')
                                    .then(() => {
                                        window.location.href = '/dashboard/inventario';
                                    });
                            } else {
                                showErrorAlert('Error', 'No se pudo eliminar el recurso');
                            }
                        })
                    }).catch(error => console.error(error));
            } else {
                showErrorAlert('Cancelado', 'Operación cancelada');
            }
        });
    });

    submitUpdate.addEventListener('click', function () {
        const resourceTypeId = resource_type.value;
        const statusId = status.value;
        const roomId = room.value;
        const fixedAssetCode = fixed_asset_code.value;

        if (fixedAssetCode === '') {
            showErrorAlert('Error', 'Ingrese el código de activo fijo').then(() => {
                fixed_asset_code.focus();
            });
            return;
        }

        const data = {
            resource_type_id: resourceTypeId,
            status_id: statusId,
            room_id: roomId,
            fixed_asset_code: fixedAssetCode
        };

        apiRequest(`/api/resources/editar/${id}`, 'PUT', data, headers)
            .then(response => {
                response.json().then(data => {
                    if (!data.success){
                        if (data.errors.room_id) {
                            showErrorAlert('Error', 'Seleccione la sala a la que pertenece el artículo').then(() => {
                                room.focus();
                            });
                            return;
                        }

                        if (data.errors.fixed_asset_code) {
                            showErrorAlert('Error', 'El código de activo fijo ya ha sido tomado').then(() => {
                                fixed_asset_code.focus();
                            });
                            return;
                        }

                        if (data.errors.resource_type_id) {
                            showErrorAlert('Error', 'Seleccione el tipo de recurso').then(() => {
                                resource_type.focus();
                            });
                            return;
                        }

                        if (data.errors.status_id) {
                            showErrorAlert('Error', 'Seleccione el estado en que se encuentra del artículo').then(() => {
                                status.focus();
                            });
                            return;
                        }

                        else{
                            showErrorAlert('Error', 'No se pudo actualizar el recurso');
                            return;
                        }
                    }

                    showSuccessAlert('Recurso actualizado', 'El artículo se ha actualizado correctamente').then(() => {
                        window.location.href = '/dashboard/inventario';
                    });
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
