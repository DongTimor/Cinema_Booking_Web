let selecting_start_date = null;
let selecting_end_date = null;

const events_formatted = events.map(event => ({
    title: event.title,
    start: `${event.all_day ? `${event.start_date}` : `${event.start_date}T${event.start_time}`}`,
    end: `${event.end_date}T${event.end_time}`,
    allDay : event.all_day,
    extendedProps: {
        event_id: event.id,
        start_time : event.start_time,
        end_time : event.end_time,
        all_day : event.all_day,
    },
}));
console.log(events_formatted);
const calendarEl = $('#calendar')[0];
const calendar = new FullCalendar.Calendar(calendarEl, {
    height: '90vh',
    aspectRatio: 1.35,
    initialView: 'dayGridMonth',
    selectable: true,
    events: events_formatted,
    eventOverlap: false,
    droppable: true,
    editable: true,
    headerToolbar:{
        left : "timeGridWeek",
        center : "title",
        right : "today,prev,next",
    },
    eventClick: function (info) {
        console.log(info);
    },
    select: function (info) {
        // selecting_start_date = info.startStr;
        $('#Event-Modal').modal('show');
        console.log(info);
    },
    eventContent: function (arg) {
        return {
            html: `<div style="
                background-color: ${arg.event.extendedProps.all_day ? '#23903c' : '#f56954'} !important;
                color: #fff206;
                font-size: 14px;
                font-weight: bold;
                padding: 5px;
                border-radius: 5px;
                height: 100%;
                width: 100%;
                overflow: hidden;
            ">
                ${arg.event.title}
                <div>
                    <span style="
                        margin-left: 5px;
                        font-size: 12px;
                        font-weight: bold;
                        color: #ffffff;
                    ">${arg.event.extendedProps.all_day ? 'All day' : `${arg.event.extendedProps.start_time} - ${arg.event.extendedProps.end_time}`}</span>
                </div>
            </div>`
        };
    },
});
calendar.render();

$(document).ready(function() {

});
