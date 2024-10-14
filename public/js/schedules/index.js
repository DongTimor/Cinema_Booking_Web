let suppressChangeEvent = false;
let events = schedules.map(schedule => ({
    title: schedule.movie_title,
    date: schedule.date,
    extendedProps: {
        date: schedule.date,
        movie_id: schedule.movie_id,
        auditorium_id: schedule.auditorium_id,
        schedule_id: schedule.id,
    },
    className: 'custom-event'
}));
let events2 = [];
const calendarEl = $('#calendar')[0];
const calendar = new FullCalendar.Calendar(calendarEl, {
    height: '90vh',
    aspectRatio: 1.35,
    initialView: 'dayGridMonth',
    selectable: true,
    events: events,
    eventOverlap: false,
    eventClick: function (info) {
        $('#customWeekModal').modal('show');
        $('#editButton').data('schedule-id', info.event.extendedProps.schedule_id);
        $('#showButton').data('schedule-id', info.event.extendedProps.schedule_id);
        $('#showButton').data('date', info.event.extendedProps.date);
        $('#showButton').data('auditorium-id', info.event.extendedProps.auditorium_id);
        $('#deleteButton').data('schedule-id', info.event.extendedProps.schedule_id);
    },
    eventContent: function (arg) {
        return {
            html: `<div><b>${arg.event.title}</b><br>Room: ${arg.event.extendedProps.auditorium_id}</div>`
        };
    },
    customButtons: {
        customWeekButton: {
            text: 'Week',
            click: async function () {
                const response = await getShowtimes($('#auditoriumFilter').val());
                if (Array.isArray(response)) {
                    events2.length = 0;
                    fillEvents2(response);
                    calendarSettings(view = 'timeGridWeek', headersLeft = 'customMonthButton');
                    calendar.addEventSource(events2);
                    calendar.setOption('eventClick', function (info) {
                        if (confirm('Do you want to edit this showtime?')) {
                            console.log(info.event.extendedProps.schedule_id);
                            editSchedule(info.event.extendedProps.schedule_id);
                        }
                    });
                    calendar.setOption('eventContent', function (arg) {
                        return {
                            html: `<div><b>${arg.event.title}</b><br>${arg.event.extendedProps.start_time} - ${arg.event.extendedProps.end_time}</div>`
                        };
                    });
                } else {
                    console.error("Expected an array but got:", response);
                }
            }
        },
        customMonthButton: {
            text: 'Month',
            click: function () {
                $('#auditoriumFilter').prop('selectedIndex', 0);
                calendarSettings();
                calendar.addEventSource(events);
                calendar.setOption('eventContent', function (arg) {
                    return {
                        html: `<div><b>${arg.event.title}</b><br>Room: ${arg.event.extendedProps.auditorium_id}</div>`
                    };
                });
            }
        }
    }
});

function convertTimeToHourAndMinute(timeString) {
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

function calendarSettings(
    view = 'dayGridMonth',
    headersLeft = "",
    headersCenter = "title",
    headersRight = "today,prev,next",
    date = new Date(),
    rerender = true
) {
    if (view === 'dayGridMonth') {
        calendar.setOption('eventClick', function (info) {
            $('#customWeekModal').modal('show');
            $('#editButton').data('schedule-id', info.event.extendedProps.schedule_id);
            $('#showButton').data('schedule-id', info.event.extendedProps.schedule_id);
            $('#showButton').data('date', info.event.extendedProps.date);
            $('#showButton').data('auditorium-id', info.event.extendedProps.auditorium_id);
            $('#deleteButton').data('schedule-id', info.event.extendedProps.schedule_id);
        });
    }
    calendar.changeView(view);
    calendar.setOption('headerToolbar', {
        left: headersLeft,
        center: headersCenter,
        right: headersRight
    });
    calendar.gotoDate(date);
    if (rerender) {
        calendar.removeAllEvents();
    }
}

function fillEvents2(response) {
    events2 = response.map(schedule => ({
        title: schedule.movie_title,
        start: `${schedule.date}T${schedule.start_time}`,
        end: `${schedule.date}T${schedule.end_time}`,
        extendedProps: {
            showtime_id: schedule.id,
            movie_id: schedule.movie_id,
            auditorium_id: schedule.auditorium_id,
            schedule_id: schedule.schedule_id,
            start_time: convertTimeToHourAndMinute(schedule.start_time),
            end_time: convertTimeToHourAndMinute(schedule.end_time)
        },
        className: 'custom-event'
    }));
}

async function getDates(movie_id) {
    const response = await fetch(`/admin/movies/features/getDates/${movie_id}`);
    return response.json();
}

async function getShowtimes(auditorium_id) {
    const response = await fetch(`/admin/showtimes/getShowtimesOfAuditorium/${auditorium_id}`);
    return response.json();
}

function editSchedule(scheduleId = $('#editButton').data('schedule-id')) {
    window.location.href = `/admin/schedules/${scheduleId}`;
}

async function showSchedule() {
    $('#customWeekModal').modal('hide');
    fillEvents2(await getShowtimes($('#showButton').data('auditorium-id')));
    var filteredEvents = events2.filter(event => {
        return event.extendedProps.schedule_id == $('#showButton').data('schedule-id');
    });
    calendarSettings('timeGridDay', '', 'title', 'customMonthButton', $('#showButton').data('date'));
    calendar.addEventSource(filteredEvents);
    calendar.setOption('eventClick', function (info) { });
    calendar.setOption('eventContent', function (arg) {
        return {
            html: `<div>
                <button class="delete-showtime-button" onclick="deleteShowtime(${arg.event.extendedProps.schedule_id},${arg.event.extendedProps.showtime_id})">X</button>
                <b>${arg.event.title}</b>
                <br>Room: ${arg.event.extendedProps.auditorium_id}
                <br>${arg.event.extendedProps.start_time} - ${arg.event.extendedProps.end_time}
                </div>`
        };
    });
}

function deleteSchedule() {
    const scheduleId = String($('#deleteButton').data('schedule-id'));
    const url = `/admin/schedules/${scheduleId}`;

    if (confirm('Are you sure you want to delete this schedule?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                alert('Schedule deleted successfully.');
                $('#customWeekModal').modal('hide');
                const events = calendar.getEvents();
                events.forEach(event => {
                    if (event.extendedProps.schedule_id === $('#deleteButton').data(
                        'schedule-id')) {
                        event.remove();
                    }
                });
            },
            error: function (error) {
                alert('An error occurred while deleting the schedule: ' + error);
            }
        });
    }
}

function deleteShowtime(scheduleId, showtimeId) {
    const url = `/admin/schedules/${scheduleId}/${showtimeId}`;
    if (confirm('Are you sure you want to delete this showtime?')) {

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                alert('Showtime deleted successfully.');
                const events = calendar.getEvents();
                events.forEach(event => {
                    if (event.extendedProps.showtime_id === showtimeId) {
                        event.remove();
                    }
                });
            },
            error: function (error) {
                alert('An error occurred while deleting the showtime: ' + error);
            }
        });
    }
}
calendar.render();

$(document).ready(async function () {
    $('#movieFilter').on('change', async function () {
        $('#auditoriumFilter').prop('selectedIndex', 0);
        var selectedMovieId = $(this).val();
        calendar.changeView('dayGridMonth');
        if (selectedMovieId !== "") {
            var filteredEvents = events.filter(event => {
                return selectedMovieId === "" || event.extendedProps.movie_id == selectedMovieId;
            });
            calendarSettings('dayGridMonth', '', 'title', 'today,prev,next', await getDates(selectedMovieId));
            calendar.addEventSource(filteredEvents);
            calendar.setOption('eventContent', function (arg) {
                return {
                    html: `<div><b>${arg.event.title}</b><br>Room: ${arg.event.extendedProps.auditorium_id}</div>`
                };
            });
        } else {
            calendarSettings('dayGridMonth', '', 'title', 'today,prev,next');
            calendar.addEventSource(events);
        }
    });

    $('#auditoriumFilter').on('change', async function () {
        $('#movieFilter').prop('selectedIndex', 0);
        var selectedAuditoriumId = $(this).val();
        if (selectedAuditoriumId !== "") {
            try {
                var filteredEvents = events.filter(event => {
                    return selectedAuditoriumId === "" || event.extendedProps.auditorium_id == selectedAuditoriumId;
                });
                calendarSettings('dayGridMonth', 'prev,next today', 'title', 'customMonthButton,customWeekButton');
                calendar.addEventSource(filteredEvents);
                calendar.setOption('eventContent', function (arg) {
                    return {
                        html: `<div><b>${arg.event.title}</b><br>Room: ${arg.event.extendedProps.auditorium_id}</div>`
                    };
                });
            } catch (error) {
                console.error("Error fetching showtimes:", error);
            }
        } else {
            calendarSettings('dayGridMonth', '', 'title', 'today,prev,next');
            calendar.addEventSource(events);
        }
    });
});

