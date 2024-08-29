@extends('layouts.layout')

@section('content')

    <div class="container-sm" style="margin-top: 70px; margin-bottom: 70px; max-width: 950px;">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 bg-white p-5 text-center">
                <h2>Editar datos de la Cita</h2>

                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Fecha</p>
                        <input type="date" class="form-control" id="date" name="date" style="padding-left: 30px;">
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Hora</p>
                        <input type="time" class="form-control" id="time" name="time" style="padding-left: 30px;">
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Número de Asistentes</p>
                        <input type="number" class="form-control" id="number_of_assistants" name="number_of_assistants"
                               style="padding-left: 15px;" min="1" max="20">
                    </div>

                    @if(Auth::user()->roles_id == 1)
                        <div class="form-group position-relative p-2">
                            <p class="text-start">Responsable</p>
                            <input type="text" class="form-control" id="name"
                                   style="padding-left: 15px;" min="1" max="20" readonly>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary" onclick="showMessage(event)">Actualizar datos</button>
                </form>

                {{--<form id="deleteForm" action="{{ route('appointments.destroy', ['id' => $appointments[0]->id]) }}"
                      method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="showDeleteConfirmationMessage(event)">Eliminar
                        la cita
                    </button>
                </form>--}}
            </div>
            <div class="col-2"></div>
        </div>
    </div>

    <script>
        function showMessage(event) {
            event.preventDefault();

            const date = document.getElementById("date");
            const time = document.getElementById("time");
            const number_of_assistants = document.getElementById("number_of_assistants");

            if (number_of_assistants.value === "") {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'El número de asistentes no puede ser menor a 1.',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            if (number_of_assistants.value < 1) {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'El número de asistentes no puede ser menor a 1.',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            if (number_of_assistants.value > 20) {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'El número de asistentes no puede ser mayor a 20.',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            let selectedDate = new Date(date.value);
            let today = new Date();

            today.setHours(0, 0, 0, 0);

            if (selectedDate <= today) {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'La fecha debe ser posterior a hoy.',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            let selectedTime = time.value;
            let selectedHour = parseInt(selectedTime.split(':')[0]);
            if (selectedHour < 8 || selectedHour > 16) {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'El horario de atención es de 8:00 AM a 04:00 PM',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                iconColor: '#046620',
                title: '¡Cita actualizada exitosamente!',
                text: 'Tu cita ha sido actualizada exitosamente.',
                confirmButtonColor: '#046620',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                document.getElementById('updateForm').submit();
            });
        }

        function showDeleteConfirmationMessage(event) {
            event.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        iconColor: '#046620',
                        title: '¡Cita eliminada exitosamente!',
                        text: 'Tu cita ha sido eliminada exitosamente.',
                        confirmButtonColor: '#046620',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        document.getElementById('deleteForm').submit();
                    });
                }
            });
        }
    </script>
    <script src="{{ asset('js/viewAppointment.js') }}"></script>

@endsection
