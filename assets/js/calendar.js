import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import bootstrapPlugin from '@fullcalendar/bootstrap';

import $ from 'jquery';
import 'bootstrap';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    Routing.setRoutingData(routes);

    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, bootstrapPlugin],
        header: {
            left: 'dayGridMonth,timeGridWeek,timeGridDay custom1',
            center: 'title',
            right: 'today prevYear,prev,next,nextYear'
        },
        themeSystem: 'bootstrap',
        events: 'calendar/events',
        eventClick: function(info) {
            document.location.href = Routing.generate('crm_activity_list_familiars', {'id': info.event.id})
        }
    });

    calendar.render();
});