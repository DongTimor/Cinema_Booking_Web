let selecting_start_date = null;
let selecting_end_date = null;
let editing_event_id = null;
let editing_event_start_date = null;
let editing_event_end_date = null;

const events_formatted = events.map(event => ({
    id: event.id,
    title: event.title,
    start: `${event.start_date}`,
    end: `${getNextDay(event.end_date)}`,
    allDay: true,
    extendedProps: {
        start_time: event.start_time,
        end_time: event.end_time,
        all_day: event.all_day,
        all_movies: event.all_movies,
        movies: event.movies,
    },
}));

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
    droppable: true,
    eventOverlap: true,
    eventDurationEditable: true,
    headerToolbar: {
        left: "customDiv",
        center: "title",
        right: "today,prev,next",
    },
    customButtons: {
        customDiv: {
            text: '',
            click: function () {
            }
        }
    },
    eventClick: async function (info) {
        $('#customEventModal').modal('show');
        editing_event_start_date = info.event.startStr;
        editing_event_end_date = info.event.endStr;
        editing_event_id = info.event.id;
        const available_movies = await getMoviesOfDates(editing_event_start_date, editing_event_end_date);
        const movies_of_event = await getMoviesOfEvent(editing_event_id);
        $('#edit_movies').empty();
        available_movies.forEach(movie => {
            if (movies_of_event.some(m => m.id === movie.id)) {
                $('#edit_movies').append(`<option selected value="${movie.id}">${movie.name}</option>`);
            } else {
                $('#edit_movies').append(`<option value="${movie.id}">${movie.name}</option>`);
            }
        });
        const event = await getEvents(info.event.id);
        $('#edit_start_time').val(null);
        $('#edit_end_time').val(null);
        $('#edit_title').val(event.title);
        $('#edit_allday').prop('checked', event.all_day);
        $('#edit_description').val(event.description);
        $('#edit_number_of_tickets').val(event.number_of_tickets);
        $('#edit_quantity').val(event.quantity < 0 ? "" : event.quantity);
        $('#edit_discount-percentage').val(event.discount_percentage);
        if (!event.all_day) {
            $('#edit_start_time').val(event.start_time);
            $('#edit_end_time').val(event.end_time);
        } else {
            $('#edit_start_time').prop('disabled', true);
            $('#edit_end_time').prop('disabled', true);
        }
        if (event.all_movies) {
            $('#edit_all_movies').prop('checked', true);
            $('#edit_movies').prop('disabled', true);
            $('#edit_movies').val(null).trigger('change');
        } else {
            $('#edit_all_movies').prop('checked', false);
            $('#edit_movies').prop('disabled', false);
        }

    },
    eventDrop: async function (info) {
        Swal.fire({
            title: "Are you sure you want to move this event?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                await moveEvent(info.event.id, getNextDay(info.event.start), convertToDate(info.event.end));
                calendar.refetchEvents();
            }else{
                info.revert();
            }
        });
    },
    eventDragStop: function (info) {
        console.log("eventDragStop", info);
    },
    eventResize: async function (info) {
        Swal.fire({
            title: "Are you sure you want to resize this event?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                await moveEvent(info.event.id, getNextDay(info.event.start), convertToDate(info.event.end));
                calendar.refetchEvents();
            }else{
                info.revert();
            }
        });
    },
    select: async function (info) {
        selecting_start_date = info.startStr;
        selecting_end_date = getPreviousDay(info.endStr);
        const movies = await getMoviesOfDates(selecting_start_date, selecting_end_date);
        $('#movies').empty();
        movies.forEach(movie => {
            $('#movies').append(`<option value="${movie.id}">${movie.name}</option>`);
        });
        $('#Event-Modal').modal('show');
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
                <div style="display: flex; justify-content: space-between;">
                    ${arg.event.title}

                </div>
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
function getPreviousDay(startStr) {
    const date = new Date(startStr);
    date.setDate(date.getDate() - 1);
    return date.toISOString().split('T')[0];
}

function convertToDate(dateStr) {
    const date = new Date(dateStr);
    return date.toISOString().split('T')[0];
}

function getNextDay(startStr) {
    const date = new Date(startStr);
    date.setDate(date.getDate() + 1);
    return date.toISOString().split('T')[0];
}

function closeCustomEventModal() {
    $('#customEventModal').modal('hide');
}


function closeEventModal() {
    $('#Event-Modal').modal('hide');
}

function editCustomEvent() {
    $('#customEventModal').modal('hide');
    $('#Edit-Modal').modal('show');
}

async function deleteCustomEvent() {
    console.log('deleting_event_id', editing_event_id);
    const url = `/admin/events/${editing_event_id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    await fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.status);
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                title: "Success!",
                text: data.success,
                icon: "success",
            })

            const event = calendar.getEventById(editing_event_id);
            if (event) {
                event.remove();
            } else {
                console.log('Event not found');
            }

            closeCustomEventModal();
        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: error.error,
                icon: "error",
            })
        });
}

async function applyEvent() {
    let quantity = -1;
    if ($('#title').val() === '') {
        Swal.fire({
            title: "Warning!",
            text: 'Title is required',
            icon: "warning",
        })
        return;
    }
    if ($('#allday').is(':checked') === false) {
        if ($('#start_time').val() === "" || $('#end_time').val() === "") {
            Swal.fire({
                title: "Warning!",
                text: 'Start time and end time are required',
                icon: "warning",
            })
            return;
        }
    }
    if ($('#all_movies').is(':checked') === false) {
        if ($('#movies').val().length === 0) {
            Swal.fire({
                title: "Warning!",
                text: 'Movies are required',
                icon: "warning",
            })
            return;
        }
    }
    if ($('#quantity').val() !== "") {
        quantity = $('#quantity').val();
    }
    const url = '/admin/events/create';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = {
        title: $('#title').val(),
        start_date: selecting_start_date,
        end_date: selecting_end_date,
        all_day: $('#allday').is(':checked'),
        all_movies: $('#all_movies').is(':checked'),
        movies: $('#movies').val(),
        start_time: $('#start_time').val(),
        end_time: $('#end_time').val(),
        description: $('#description').val(),
        number_of_tickets: $('#number_of_tickets').val(),
        quantity: quantity,
        discount_percentage: $('#discount-percentage').val(),
    };
    await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.status);
            }
            return response.json();
        })
        .then(async data => {
            Swal.fire({
                title: "Success!",
                text: data.success,
                icon: "success",
            })
            console.log('data', data);
            const events = await getEvents("all");
            const events_formatted_2 = events.map(event => ({
                id: event.id,
                title: event.title,
                start: `${event.start_date}`,
                end: `${getNextDay(event.end_date)}`,
                allDay: event.all_day,
                extendedProps: {
                    start_time: event.start_time,
                    end_time: event.end_time,
                    all_day: event.all_day,
                },
            }));
            calendar.removeAllEventSources();
            calendar.addEventSource(events_formatted_2);
            closeEventModal();

        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: error.error,
                icon: "error",
            })
            console.log('error', error);
        });
}

async function moveEvent(id, start_date, end_date) {
    const url = `/admin/events/${id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = {
        start_date: start_date,
        end_date: end_date,
    };
    await fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.status);
            }
            return response.json();
        })
        .then(async data => {
            Swal.fire({
                title: "Success!",
                text: data.success,
                icon: "success",
            })
        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: error.error,
                icon: "error",
            })
        });
}

async function applyEdit() {
    if ($('#edit_title').val() === "") {
        Swal.fire({
            title: "Warning!",
            text: 'Title is required',
            icon: "warning",
        })
        return;
    }
    if ($('#edit_allday').is(':checked') === false) {
        if ($('#edit_start_time').val() === "" || $('#edit_end_time').val() === "") {
            Swal.fire({
                title: "Warning!",
                text: 'Start time and end time are required',
                icon: "warning",
            })
            return;
        }
    }
    if ($('#edit_all_movies').is(':checked') === false) {
        if ($('#edit_movies').val().length === 0) {
            Swal.fire({
                title: "Warning!",
                text: 'Movies are required',
                icon: "warning",
            })
            return;
        }
    }
    editEvent(editing_event_id,
        $('#edit_title').val(),
        $('#edit_description').val(),
        $('#edit_number_of_tickets').val(),
        $('#edit_quantity').val(),
        $('#edit_discount-percentage').val(),
        $('#edit_start_time').val(),
        $('#edit_end_time').val(),
        $('#edit_allday').is(':checked'),
        $('#edit_all_movies').is(':checked'),
        $('#edit_movies').val(),
    );
    closeEditModal();
    const events = await getEvents("all");
    const events_formatted_2 = events.map(event => ({
        id: event.id,
        title: event.title,
        start: `${event.start_date}`,
        end: `${getNextDay(event.end_date)}`,
        allDay: event.all_day,
        extendedProps: {
            start_time: event.start_time,
            end_time: event.end_time,
            all_day: event.all_day,
        },
    }));
    calendar.removeAllEventSources();
    calendar.addEventSource(events_formatted_2);
    calendar.refetchEvents();
}

async function closeEditModal() {
    $('#Edit-Modal').modal('hide');
}

async function editEvent(id, title, description, number_of_tickets, quantity, discount_percentage, start_time, end_time, allday, all_movies, movies) {
    console.log('editEvent', id, title, description, number_of_tickets, quantity, discount_percentage, start_time, end_time, allday, all_movies, movies);
    const url = `/admin/events/${id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let startTime = null;
    let endTime = null;
    let discountPercentage = null;
    let Quantity = null;
    let numberOfTickets = null;
    if (allday) {
        startTime = null;
        endTime = null;
    } else {
        startTime = start_time;
        endTime = end_time;
    }
    if (all_movies) {
        movies = [];
    }
    if (discount_percentage === '') {
        discountPercentage = 0;
    } else {
        discountPercentage = discount_percentage;
    }
    if (quantity === '') {
        Quantity = -1;
    } else {
        Quantity = quantity;
    }
    if (number_of_tickets === '') {
        numberOfTickets = 1;
    } else {
        numberOfTickets = number_of_tickets;
    }
    const data = {
        title: title,
        description: description,
        number_of_tickets: numberOfTickets,
        quantity: Quantity,
        discount_percentage: discountPercentage,
        start_time: startTime,
        end_time: endTime,
        all_day: allday,
        all_movies: all_movies,
        movies: movies,
    };
    console.log('data', data);
    await fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.status);
            }
            return response.json();
        })
        .then(async data => {
            Swal.fire({
                title: "Success!",
                text: data.success,
                icon: "success",
            })
        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: error.error,
                icon: "error",
            })
        });
}

async function getEvents(id) {
    const url = `/admin/events/get/${id}`;
    const response = await fetch(url);
    const data = await response.json();
    return data;
}

async function getMoviesOfDates(start_date, end_date) {
    const url = `/admin/movies/features/getMoviesOfDates/${start_date}/${end_date}`;
    const response = await fetch(url);
    const data = await response.json();
    console.log('data', data);
    return data;
}

async function getMoviesOfEvent(id) {
    const url = `/admin/movies/features/getMoviesOfEvent/${id}`;
    const response = await fetch(url);
    const data = await response.json();
    return data;
}

calendar.render();


$(document).ready(function () {
    $('#edit_allday').change(function () {
        if ($('#edit_allday').is(':checked')) {
            $('#edit_start_time').prop('disabled', true);
            $('#edit_end_time').prop('disabled', true);
            $('#edit_start_time').val(null);
            $('#edit_end_time').val(null);
        } else {
            $('#edit_start_time').prop('disabled', false);
            $('#edit_end_time').prop('disabled', false);
        }
    });

    $('#allday').change(function () {
        if ($('#allday').is(':checked')) {
            console.log('allday');
            $('#start_time').prop('disabled', true);
            $('#end_time').prop('disabled', true);
            $('#start_time').val(null);
            $('#end_time').val(null);
        } else {
            $('#start_time').prop('disabled', false);
            $('#end_time').prop('disabled', false);
        }
    });


    $('#all_movies').on('change', function () {
        if ($(this).is(':checked')) {
            $('#movies').prop('disabled', true);
            $('#movies').val(null).trigger('change');
        } else {
            $('#movies').prop('disabled', false);
        }
    });

    $('#edit_all_movies').on('change', function () {
        if ($(this).is(':checked')) {
            $('#edit_movies').prop('disabled', true);
            $('#edit_movies').val(null).trigger('change');
        } else {
            $('#edit_movies').prop('disabled', false);
        }
    });

    $('.fc-customDiv-button').replaceWith('<div id="myCustomDiv" style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; display: flex; width: 250px; justify-content: space-around;">' +
        '<div style="display: flex; align-items: center; margin-top: 5px;">' +
        '<span style="display: inline-block; width: 15px; height: 15px; background-color: #23903c; border-radius: 50%; margin-right: 5px;"></span>' +
        '<label style="font-size: 14px;margin-top: 5px;">All day</label>' +
        '</div>' +
        '<div style="display: flex; align-items: center; margin-top: 5px;">' +
        '<span style="display: inline-block; width: 15px; height: 15px; background-color: #f56954; border-radius: 50%; margin-right: 5px;"></span>' +
        '<label style="font-size: 14px;margin-top: 5px;">With time</label>' +
        '</div>' +
        '</div>');
});
