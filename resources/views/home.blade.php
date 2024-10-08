@extends('layouts.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('CSS/principal.css')}}">
    <!--Contenido de Home-->
    <section>
        <!--Contenedor principal-->
        <div class="container">
            <div class="row row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card custom-card">
                        <div class="card-body">
                            <video class="video" controls autoplay disablepictureinpicture loop muted
                                   controlsList="nodownload nofullscreen noplaybackrate" oncontextmenu="return false;"
                                   src="{{asset('IMG/VideoVR.mp4')}}" type="video/mp4"></video>
                        </div>
                    </div>
                    <br><br>

                    <div class="informacion">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Servicio de capacitación </h4>
                                <p class="card-text"><b>Descripción:</b>  El Minerva VR Lab es un laboratorio de realidad virtual inmersiva el cual tiene como objetivo
                                    principal potenciar la formación de los estudiantes a través de la interacción virtual. <br><br>

                                    En dicho espacio los estudiantes y docentes que reserven cita podrán tener acceso a una de las dos salas con la cuenta el Minerva VR Lab FMO
                                    donde un capacitador o guía les brindara un recorrido a través de los distintos entornos o escenarios virtuales con los que se cuentan.
                                    <br><br>

                                    <b>Equipo con el que contamos: </b> <br><br>

                                    <i class="fa-solid fa-vr-cardboard"></i> Lentes de realidad virtual. <br>
                                    <i class="fa-solid fa-tv"></i> Televisión de alta definición. <br>
                                    <i class="fa-solid fa-desktop"></i> Computadoras de alta gama. <br> <br>

                                    <b>Visítanos no pierdas la oportunidad de experimentar una forma de aprendizaje</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>


@endsection
