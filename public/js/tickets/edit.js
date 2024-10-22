const baseUrl = 'http://localhost/admin';
let currentFilter = 'date';

function convertTimeToHourAndMinute(timeString) {
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

function findMaxRowAndColumn(seats) {
    let maxRow = -Infinity;
    let maxColumn = -Infinity;

    seats.forEach(seat => {
        if (seat.row > maxRow) {
            maxRow = seat.row;
        }
        if (seat.column > maxColumn) {
            maxColumn = seat.column;
        }
    });

    return { maxRow, maxColumn };
}

function sortSeats(seats) {
    return seats.sort((a, b) => {
        if (a.row === b.row) {
            return a.column - b.column;
        }
        return a.row - b.row;
    });
}

function click(){
    console.log("sortedSeats", $('#movie_id').val(), $('#date').val(), $('#auditorium_id').val(), $('#showtime_id').val());
}

async function fillSeatsContainer(){
    const sortedSeats = sortSeats(await getSeatsOfAuditorium($('#auditorium_id').val()));
    const tickets = await getTicketOfSchedule($('#movie_id').val(), $('#date').val(), $('#auditorium_id').val(), $('#showtime_id').val());
            const { maxRow, maxColumn } = findMaxRowAndColumn(sortedSeats);
            const height = 600 / (maxRow) - (maxRow * 7);
            $('#seats_container').empty();
            $('#seats_container').css({
                'display': 'grid',
                'grid-template-rows': `repeat(${maxRow + 1}, 1fr)`,
                'grid-template-columns': `repeat(${maxColumn + 1}, 1fr)`,
                'gap': '5px',
                'padding': '10px',
                'border': '1px solid black',
                'overflow': 'auto',
                'max-height': '600px'
            });
            $('#another_seats_container').empty();
            $('#another_seats_container').css({
                'display': 'grid',
                'grid-template-columns': `repeat(${maxColumn + 1}, 1fr)`,
                'gap': '5px',
                'padding': '10px',
                'border': '1px solid black'
            });
            sortedSeats.forEach(seat => {
                const bookedTicket = tickets.find(ticket => ticket.seat_id === seat.id);
                const isBooked = !!bookedTicket;
                const status = isBooked ? bookedTicket.status : null;
                const backgroundColor = status === 'ordered' ? 'rgb(255, 204, 204)' :
                    status === 'settled' ? 'rgb(102, 255, 102)' : 'rgb(204, 229, 255)';
                if (seat.row !== null) {
                    const seatDiv = $('<div></div>')
                        .attr('id', `${seat.id}`)
                        .addClass('seat')
                        .text(seat.seat_number)
                        .addClass(isBooked ? 'crossed' : '')
                        .css({
                            'grid-row': seat.row + 1,
                            'grid-column': seat.column + 1,
                            'height': '100%',
                            'background-color': backgroundColor,
                            'border': '1px solid black',
                            'border-radius': '5px',
                            'display': 'flex',
                            'flex-direction': 'column',
                            'justify-content': 'center',
                            'align-items': 'center',
                            'cursor': 'pointer',
                            'font-weight': 'bold',
                            'min-width': `75px`,
                            'min-height': `30px`,
                            'position': 'relative'
                        })
                        // .on('click', function () {
                        //     if (!isBooked) {
                        //         seatChoosen = $(this).attr('id');
                        //         $('#Select-Status-Modal').modal('show');
                        //         if ($(this).data('status')) {
                        //             $('#status').val($(this).data('status'));
                        //         } else {
                        //             $('#status').val('unplaced');
                        //         }
                        //     } else {
                        //         alert('This seat is already booked.');
                        //     }
                        // });
                    $('#seats_container').append(seatDiv);
                } else {
                    $('#another_seats_container_lable').css('display', 'flex');
                    const seatDiv = $('<div></div>')
                        .attr('id', `${seat.id}`)
                        .addClass('seat')
                        .addClass(isBooked ? 'crossed' : '')
                        .text(seat.seat_number)
                        .css({
                            'height': `${height}px`,
                            'background-color': backgroundColor,
                            'border': '1px solid black',
                            'border-radius': '5px',
                            'display': 'flex',
                            'flex-direction': 'column',
                            'justify-content': 'center',
                            'align-items': 'center',
                            'cursor': 'pointer',
                            'font-weight': 'bold',
                            'min-width': `75px`,
                            'min-height': `30px`,
                            'position': 'relative'
                        })
                        // .on('click', function () {
                        //     if (!isBooked) {
                        //         seatChoosen = $(this).attr('id');
                        //         $('#Select-Status-Modal').modal('show');
                        //         if ($(this).data('status')) {
                        //             $('#status').val($(this).data('status'));
                        //         } else {
                        //             $('#status').val('unplaced');
                        //         }
                        //     } else {
                        //         alert('This seat is already booked.');
                        //     }
                        // });
                    $('#another_seats_container').append(seatDiv);
                }
            });
}

async function getSchedule(id) {
    const response = await fetch(`${baseUrl}/movies/features/getSchedule/${id}`);
    const schedule = await response.json();
    return schedule;
}

async function getShowtimesOfMovieAndDate(date, movie) {
    const response = await fetch(`${baseUrl}/showtimes/getShowtimesOfMovieAndDate/${date}/${movie}`);
    const showtimes = await response.json();
    return showtimes;
}

async function getAuditoriumsOfShowtime(date, movie, showtime) {
    const response = await fetch(`${baseUrl}/auditoriums/getAuditoriumsOfShowtime/${date}/${movie}/${showtime}`);
    const auditoriums = await response.json();
    return auditoriums;
}

async function getSeatsOfAuditorium(auditorium) {
    const response = await fetch(`${baseUrl}/seats/getSeatsOfAuditorium/${auditorium}`);
    const seats = await response.json();
    return seats;
}

async function getTicketOfSchedule(movie, date, auditorium, showtime) {
    const response = await fetch(`${baseUrl}/tickets/getTicketsOfSchedule/${movie}/${date}/${auditorium}/${showtime}`);
    const ticket = await response.json();
    return ticket;
}

async function filter() {
    $('#date').empty();
    $('#showtime_id').empty();
    $('#auditorium_id').empty();
    const data = await getSchedule($(movie_id).val());
    console.log(data);
    if (currentFilter == 'date') {
        if (data) {
        data.dates.forEach(day => {
            if (day == date) {
                $('#date').append(`<option value="${day}" selected>${day}</option>`);
            } else {
                $('#date').append(`<option value="${day}">${day}</option>`);
            }
            });
        }
        const showtimes = await getShowtimesOfMovieAndDate($('#date').val(), $('#movie_id').val());
        $('#showtime_id').prop('disabled', false);
        showtimes.forEach(showtime => {
            if (showtime.id == showtime_id) {
                $('#showtime_id').append(`<option value="${showtime.id}" selected>${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            } else {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            }
        });
        $('#auditorium_id').prop('disabled', true);
        $('#auditorium_id').append(`<option value="${auditorium_id}" selected>${auditorium_name}</option>`);
    }else if (currentFilter == 'showtime') {
        data.showtimes.forEach(showtime => {
            if (showtime.id == showtime_id) {
                $('#showtime_id').append(`<option value="${showtime.id}" selected>${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            } else {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            }
        });
        $('#showtime_id').prop('disabled', false);
        $('#date').prop('disabled', false);
        $('#date').append(`<option value="${date}" selected>${date}</option>`);
        $('#auditorium_id').prop('disabled', true);
        $('#auditorium_id').append(`<option value="${auditorium_id}" selected>${auditorium_name}</option>`);
    }else {
        const auditoriumsArray = Array.isArray(data.auditoriums) ? data.auditoriums : Object.values(data.auditoriums);
        auditoriumsArray.forEach(auditorium => {
            if (auditorium.id == auditorium_id) {
                $('#auditorium_id').append(`<option value="${auditorium.id}" selected>${auditorium.name}</option>`);
            } else {
                $('#auditorium_id').append(`<option value="${auditorium.id}">${auditorium.name}</option>`);
            }
        });
        $('#auditorium_id').prop('disabled', false);
        $('#date').prop('disabled', false);
        $('#date').append(`<option value="${date}" selected>${date}</option>`);
        $('#showtime_id').prop('disabled', true);
        $('#showtime_id').append(`<option value="${showtime_id}" selected>${showtime_value}</option>`);
    }
}

filter();
// fillSeatsContainer();
$(document).ready(function(){
    // Khởi tạo với 'date' được chọn
    $('#date-filter').prop('checked', true);

    $('input[name="group"]').on('change', function() {
        if (!$(this).is(':checked')) {
            $(this).prop('checked', true);
        } else {
            $('input[name="group"]').not(this).prop('checked', false);
        }
    });

    $('#date-filter').on('change', function(){
        $('#date').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'date';
        filter();
    });
    $('#auditorium-filter').on('change', function(){
        $('#auditorium_id').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'auditorium';
        filter();
    });
    $('#showtime-filter').on('change', function(){
        $('#showtime_id').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'showtime';
        filter();
    });

    $('#date').on('change', async function(){
        if (currentFilter == 'date') {
            const showtimes = await getShowtimesOfMovieAndDate($('#date').val(), $('#movie_id').val());
            $('#showtime_id').empty();
            $('#showtime_id').append('<option value="">Select showtime</option>');
            showtimes.forEach(showtime => {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            });
        }
    });

    $('#showtime_id').on('change', async function(){
        if (currentFilter == 'date') {
            const auditoriums = await getAuditoriumsOfShowtime($('#date').val(), $('#movie_id').val(), $('#showtime_id').val());
            $('#auditorium_id').empty();
            $('#auditorium_id').append('<option value="">Select auditorium</option>');
            auditoriums.forEach(auditorium => {
                $('#auditorium_id').append(`<option value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`);
            });
            $('#auditorium_id').prop('disabled', false);
        }
    });
});
