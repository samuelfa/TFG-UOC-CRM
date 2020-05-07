import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import bootstrapPlugin from '@fullcalendar/bootstrap';

import $ from 'jquery';
import 'bootstrap';

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, bootstrapPlugin],
        header: {
            left: 'dayGridMonth,timeGridWeek,timeGridDay custom1',
            center: 'title',
            right: 'today prevYear,prev,next,nextYear'
        },
        themeSystem: 'bootstrap',
        events: 'calendar/events'
    });

    calendar.render();
});