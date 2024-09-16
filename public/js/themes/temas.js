//Archivo con la logica para cambiar los colores del sistema

//Establecemos las constantes a emplear
const CambioTema = document.getElementById('Tema');
const navbar = document.querySelector('.navbar');
const footer = document.getElementById('FooterCambio');
const buttons = document.querySelectorAll('.nav-item button');
const body = document.body;
const modoMinerva = 'IMG/Minerva.png';
const modoVR = 'IMG/Lentes.png';

//Definimos una función que nos ayude a cambiar el tema establecido por otro
function newTema(){
    const temaGuardado = localStorage.getItem('tema'); //Nos ayuda a obtener el tema guardado en localstorage
    //Verificamos si el tema guardado es 'minerva'
    if (temaGuardado === 'minerva') {
        navbar.classList.add('Temas');
        body.classList.add('fondos');
        buttons.forEach(button => button.classList.add('Temas'));
        footer.classList.add('Temas');
        CambioTema.innerHTML = `<img class="Lentes" src="${modoVR}" alt="Lentes" style="width: 40px; height: 40px;">`;
    } else {
        navbar.classList.remove('Temas');
        body.classList.remove('fondos');
        buttons.forEach(button => button.classList.remove('Temas'));
        footer.classList.remove('Temas');
        CambioTema.innerHTML = `<img class="minerva" src="${modoMinerva}" alt="Lentes" style="width: 40px; height: 40px;">`;
    }
}

//Indicamos que se aplique el tema al cargar la pagina
newTema();

//Evento antes del cambio
CambioTema.addEventListener("click", () => {
    navbar.classList.toggle("Temas");
    body.classList.toggle("fondos");
    buttons.forEach(button => button.classList.toggle('Temas'));
    footer.classList.toggle('Temas');

    //Indicamos el cambio de icono(IMG) según el modo en el que se encuentre la pagina
    const miverIcon = `<img class="minerva" src="${modoMinerva}" alt="Lentes" style="width: 40px; height: 40px;">`;
    const vrIcon = `<img class="Lentes" src="${modoVR}" alt="Lentes" style="width: 40px; height: 40px;">`;

    // Si las clases están presentes, cambiamos la IMG a modo VR y guardamos el estado
    if (navbar.classList.contains("Temas") && body.classList.contains("fondos")) {
        CambioTema.innerHTML = vrIcon;
        localStorage.setItem('tema', 'minerva'); // Guardar el estado en localStorage
    } else {
         // Si no, cambiamos la IMG a modo Minerva y guardamos el estado
        CambioTema.innerHTML = miverIcon;
        localStorage.setItem('tema', 'vr'); // Guardar el estado en localStorage
    }
});
