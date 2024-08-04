<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')

<div class="container">
    <div class="mensaje">
        <h1>Bienvenido al Minerva VR Web <br> Tu espacio para poder agendar citas para recibir capacitación.</h1>
    </div>

    <div class="info1">
        <div class="IMGrobot">
             <img class="robot" src="{{ asset('IMG/Robot_transparente.png') }}" alt="Robot">
        </div>
        <div class="card w-75 mb-3">
                <div class="card-body">
                    <p class="card-text">En un mundo en constante avance y desarrollo tecnológico, es fundamental que la educación camine de la mano con la ciencia y la tecnología.
                        En la Universidad de El Salvador, somos conscientes de esto, por lo que te extendemos una cordial invitación a visitar el Minerva VR Lab, una innovadora forma de enseña
                    </p>
                </div>
        </div>

    </div>

    <div class="mensaje2">
        <h3>Visítanos y vive la experiencia del emocionante <br> mundo de la realidad virtual.</h3>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col">
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
        <div class="col"></div>
    </div>

      <div class="mensaje3">
        <p>la realidad virtual está transformando la educación al proporcionar experiencias inmersivas, auténticas y <br>
         significativas para los estudiantes. ¡Anímate a explorar este emocionante campo y descubrir cómo puedes <br>
         integrar la RV en tus prácticas educativas!</p>
      </div>
      <br> <br>

</div>
@endsection
