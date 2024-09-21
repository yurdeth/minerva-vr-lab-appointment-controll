document.addEventListener('DOMContentLoaded', () => {
    // Establecemos las constantes a emplear
    const CambioTema = document.getElementById('Tema');
    const navbar = document.querySelector('.navbar');
    const footer = document.getElementById('FooterCambio');
    const buttons = document.querySelectorAll('.nav-item button');
    const contenedorImagen = document.getElementById('contenedorImagen');
    const logoInicio = document.getElementById('logoInicio');
    const body = document.body;
    //Constantes con las rutas de las imagenes
    const modoMinerva = '../IMG/Minerva.png';
    const modoVR = '../IMG/Lentes.png';
    const logoFMO = '../IMG/Logo FMO.png';
    const logoVR = '../IMG/LogoLentes.png';

    // Definimos una función que nos ayude a cambiar el tema establecido por otro
    function newTema() {
        // Obtenemos el tema guardado en localStorage, por defecto es 'vr'
        const temaGuardado = localStorage.getItem('tema') || 'vr';
        const isMinerva = temaGuardado === 'minerva'; // Verificamos si el tema guardado es 'minerva'

        // Cambiamos las clases de los elementos según el tema
        navbar.classList.toggle('Temas', isMinerva);
        body.classList.toggle('fondos', isMinerva);
        buttons.forEach(button => button.classList.toggle('Temas', isMinerva));
        footer.classList.toggle('Temas', isMinerva);
         //Condicional para verificar que el elemento contenedorImagen existe antes de intentar cambiar sus clases.
        if (contenedorImagen) {
            contenedorImagen.classList.toggle('contenedor-minerva', isMinerva);
            contenedorImagen.classList.toggle('contenedor-vr', !isMinerva);
        }
        //condicional para verificar que el elemento logoInicio existe antes de intentar cambiar su fuente y clase
        if (logoInicio) {
            logoInicio.src = isMinerva ? logoFMO : logoVR;
            logoInicio.className = isMinerva ? 'logo-fmo' : 'logo-vr';
        }

        // Cambiamos la imagen del botón de cambio de tema con clases específicas
        CambioTema.innerHTML = `<img class="${isMinerva ? 'Lentes' : 'minerva'}" src="${isMinerva ? modoVR : modoMinerva}" alt="Lentes" style="width: 40px; height: 40px;">`;
    }

    // Aplicamos el tema al cargar la página
    newTema();

    // Evento para cambiar el tema al hacer clic en el botón
    CambioTema.addEventListener("click", () => {
        // Alternamos las clases de los elementos según el tema
        const isMinerva = navbar.classList.toggle('Temas');
        body.classList.toggle('fondos', isMinerva);
        buttons.forEach(button => button.classList.toggle('Temas', isMinerva));
        footer.classList.toggle('Temas', isMinerva);
        //Condicional para verificar que el elemento contenedorImagen existe antes de intentar cambiar sus clases.
        if (contenedorImagen) {
            contenedorImagen.classList.toggle('contenedor-minerva', isMinerva);
            contenedorImagen.classList.toggle('contenedor-vr', !isMinerva);
        }
        //condicional para verificar que el elemento logoInicio existe antes de intentar cambiar su fuente y clase
        if (logoInicio) {
            logoInicio.src = isMinerva ? logoFMO : logoVR;
            logoInicio.className = isMinerva ? 'logo-fmo' : 'logo-vr';
        }

        // Cambiamos la imagen del botón de cambio de tema con clases específicas
        const icon = isMinerva ? modoVR : modoMinerva;
        CambioTema.innerHTML = `<img class="${isMinerva ? 'Lentes' : 'minerva'}" src="${icon}" alt="Lentes" style="width: 40px; height: 40px;">`;

        // Guardamos el estado del tema en localStorage
        localStorage.setItem('tema', isMinerva ? 'minerva' : 'vr');
    });
});
