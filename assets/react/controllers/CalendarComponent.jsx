import React from 'react'
import FullCalendar from '@fullcalendar/react' // doit être importé avant les plugins
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list';
import dayGridPlugin from '@fullcalendar/daygrid' // un plugin
import interactionPlugin from '@fullcalendar/interaction'

export default class CalendarComponent extends React.Component {
    render() {
        return (
            <FullCalendar
            plugins={[ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ]}
            initialView= 'timeGridWeek'
            locale= 'fr'
            timeZone= 'Europe/Paris'
            headerToolbar= {{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
            }}
            buttonText= {{
                today: 'aujourd\'hui',
                month: 'mois',
                week: 'semaine',
                day: 'jour',
                list: 'liste'
            }}
            // events= { data|raw }
            editable={true}
            selectable={true}
            eventResizableFromStart={true}
        />
    )
    }
}
