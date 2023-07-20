window.onload = () => {
    let calendarElt = document.querySelector("#calendrier");

    let calendar = new FullCalendar.Calendar(calendarElt, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        events: events,
        nowIndicator: true,
        editable: true,
        eventResizableFromStart: true,
        selectable: true,
        droppable: true,
        eventDrop: (infos) => {
            if(!confirm('Étes-vous sûr(e) de vouloir déplacer cette réservation ?')) {
                infos.revert()
            }
        },
        eventClick: function(event) {
            if (event.url) {
                window.open(event.url);
                return false; // Empêche le navigateur de suivre le lien de l'événement
            }
        }
    });

    calendar.on('eventChange', (e) => {
        let url = `/api/${e.event.id}/edit`
        let donnees = {
            "title": e.event.title,
            "description": e.event.extendedProps.description,
            "start": e.event.start,
            "end": e.event.end,
            "backgroundColor": e.event.backgroundColor,
            "borderColor": e.event.borderColor,
            "textColor": e.event.textColor,
            "allDay": e.event.allDay,
            "salle": e.event.extendedProps.salle,
            "typeReservation": e.event.extendedProps.typeReservation,
            "gestionnaireSalle": e.event.extendedProps.gestionnaireSalle,
            "participantNumber": e.event.extendedProps.participantNumber,
            "url": e.event.url,
            "deposit": e.event.extendedProps.deposit,
            "paid": e.event.extendedProps.paid,

        }
        console.log(donnees);
        
        let xhr = new XMLHttpRequest

        xhr.open("PUT", url)
        xhr.send(JSON.stringify(donnees))
    })

    calendar.render();

}