let selectedDate = null; // Variable para almacenar la fecha seleccionada actualmente
function ajaxpage(url, containerid) {

    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById(containerid).innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

console.log('calendar.js loaded');

const calendarEl = document.getElementById('calendar');
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth', locale: 'es', headerToolbar: {
        left: 'prev,next,today', center: 'title', right: 'prev,next'
    }, events: 'prueba.php', dateClick: function (info) {

        // Verificar si hay eventos en la fecha seleccionada
        let eventsOnDate = calendar.getEvents().filter(function (event) {
            return event.start.toDateString() === info.date.toDateString();
        });

        // Ejecutar ajaxpage si hay eventos en la fecha seleccionada
        if (eventsOnDate.length > 0) {
            // Restaurar el color de la fecha seleccionada anteriormente
            if (selectedDate) {
                const prevBgHarness = selectedDate.querySelector('.fc-daygrid-bg-harness');
                if (prevBgHarness) {
                    prevBgHarness.style.backgroundColor = 'rgb(255,255,255)'; // Restaurar color original
                }
            }

            // Actualizar la fecha seleccionada actual
            selectedDate = info.dayEl;
            // Acceder al elemento con la clase fc-daygrid-bg-harness dentro de la celda actual
            const bgHarness = info.dayEl.querySelector('.fc-daygrid-bg-harness');
            if (bgHarness) {
                // Cambiar el color de fondo de la etiqueta fc-daygrid-bg-harness
                bgHarness.style.backgroundColor = 'rgb(229,145,145)'; // Cambiar a un color diferente
            } else {
                console.log('Elemento fc-daygrid-bg-harness no encontrado en la celda');
            }
            const dateParam = encodeURIComponent(info.dateStr);
            //ajaxpage('events.php?date=' + dateParam, 'Events');
        }
    }, eventDidMount: function (info) {
        // Obtener la celda correspondiente a la fecha del evento
        const cell = info.el.parentNode;

        // Cambiar el color de fondo de la celda
        cell.style.backgroundColor = 'rgb(255,255,255)';
    }, eventMouseEnter: function (info) {
        info.el.style.cursor = 'pointer';
    },
});
calendar.render();

let myModalEl = document.getElementById('calendarModal');
myModalEl.addEventListener('shown.bs.modal', function () {
    calendar.render();
    calendar.updateSize();
});
