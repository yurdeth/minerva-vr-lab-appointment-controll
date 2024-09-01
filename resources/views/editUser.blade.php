<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
{{--@section('content')--}}

@section('content_header')
    <h1>Perfil</h1>
@stop

@section('content')
    @vite(['resources/js/app.js'])

    <div class="container-xl" style="margin-top: 50px; margin-bottom: 70px;">

        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="card">

                    <div class="card-header">
                        <h2 class="text-center">Datos</h2>
                    </div>

                    <div class="card-body">

                        <form id="updateForm" method="POST">
                            @csrf
                            @method("PUT")

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="email" name="email" readonly>
                            </div>

                            <div class="form-group position-relative">
                                <label for="department" class="form-label">Departamento</label>
                                <select name="department" id="department" class="form-control" required>
                                    <option disabled>Seleccione un departamento</option>
                                </select>
                            </div>

                            <div class="form-group position-relative">
                                <label for="career" class="form-label">Carrera</label>
                                <select name="career" id="career" class="form-control" required>
                                    <option disabled>Carrera</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña: </label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese la nueva o la anterior">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Repita su nueva contraseña: </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" placeholder="Confirmar">
                            </div>

{{--                            <button type="submit" class="btn btn-primary" onclick="showMessage(event)">Actualizar datos</button>--}}
                        </form>
                        <div id="actionsButtons" class="row"></div>

                        {{--<form id="deleteForm" method="POST" class="mt-3">
                            @csrf

                            @method("DELETE")
                            <button type="submit" class="btn btn-danger" onclick="showDeleteConfirmationMessage(event)">Eliminar perfil</button>
                        </form>--}}

                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        function showMessage(event) {
            event.preventDefault();

            const name = document.getElementById("name");
            const password = document.getElementById("password");
            const password_confirmation = document.getElementById("password_confirmation");

            if (name.value === "") {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#660D04',
                    title: 'Oops...',
                    text: 'Por favor, ingresa tu nombre',
                    confirmButtonColor: '#660D04',
                });
                return;
            }

            if (password.value !== "" || password_confirmation.value !== "") {
                if (password.value === "") {
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: 'Ingresa tu nueva contraseña',
                        confirmButtonColor: '#660D04',
                        showConfirmButton: true,
                    });
                    return;
                }

                if (password_confirmation.value === "") {
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: 'Confirma tu nueva contraseña',
                        confirmButtonColor: '#660D04',
                        showConfirmButton: true,
                    });
                    return;
                }

                if (password.value !== password_confirmation.value) {
                    Swal.fire({
                        icon: 'error',
                        iconColor: '#660D04',
                        title: 'Oops...',
                        text: 'Las contraseñas no coinciden',
                        confirmButtonColor: '#660D04',
                        showConfirmButton: true,
                    });
                    return;
                }
            }

            Swal.fire({
                icon: 'success',
                iconColor: '#046620',
                title: '¡Perfil actualizado exitosamente!',
                text: 'Tu perfil ha sido actualizado exitosamente.',
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
                        title: '¡Perfil eliminado exitosamente!',
                        text: 'Tu perfil ha sido eliminado exitosamente.',
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

    <script type="module" src="{{asset("js/getResponsePromise.js")}}"></script>
    <script type="module" src="{{ asset('js/viewUser.js') }}"></script>
    <script src="{{ asset('js/departments.js') }}"></script>
    <script src="{{ asset('js/careers.js') }}"></script>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}} ">
@stop
