<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')

    <!--Contenido de la pagina-->
    <section>
        <!--Mensaje de Bienvenida-->
        <div class="mensaje">
            <h1>Bienvenido al Minerva VR Web <br> Tu espacio para poder agendar citas para recibir capacitación.</h1>
        </div>
        <br><br>
        <!--Contenido Principal-->
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-3 text-center">
                    <img class="robot img-fluid" src="{{ asset('IMG/Robot_transparente.png') }}" alt="Robot">
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="card mb-3">
                        <div class="card-body card-mensaje">
                            <p class="card-text">En un mundo en constante avance y desarrollo tecnológico, es fundamental que la educación camine de la mano con la ciencia y la tecnología.
                                En la Universidad de El Salvador, somos conscientes de esto, por lo que te extendemos una cordial invitación a visitar el Minerva VR Lab, una innovadora forma de enseña
                            </p>
                        </div>
                    </div>

                </div>

            </div>
            <br>
        </div>
        <div class="mensaje2">
            <h3>Visítanos y vive la experiencia del emocionante <br> mundo de la realidad virtual.</h3>
        </div>
        <!--Parte del Carousel-->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-body fotosSlide">
                            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="{{ asset('IMG/vr1.png') }}" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('IMG/vr2.png') }}" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('IMG/vr3.jpeg') }}" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('IMG/vr4.jpeg') }}" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('IMG/vr5.jpg') }}" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('IMG/vr6.jpg') }}" class="d-block w-100" alt="...">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
    </section>


@endsection
