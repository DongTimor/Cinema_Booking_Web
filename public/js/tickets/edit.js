const baseUrl = 'http://localhost/admin';
let currentFilter = 'date';
let selectedSeats = [];
let seatChoosen = null;
let seatStatus = 'unplaced';
let changedSeatStatus = false;
let voucherId = null;
let voucherValue = null;
let voucherType = null;

if (voucher) {
    voucherId = voucher.id;
    voucherValue = voucher.value;
    voucherType = voucher.type;
}

console.log(voucherId, voucherValue, voucherType);

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

function changeSeatStatus() {
    if (changedSeatStatus === false) {
        $('#current-seat-container').hide()
        changedSeatStatus = true;
        $('#description').css('justify-content', 'flex-end');
        $('#guide').text('[Select at least one seat.]');
    }
}

function closeSelectStatusModal() {
    $('#Select-Status-Modal').modal('hide');
}

function openShowVoucherModal() {
    $('#Show-Voucher-Modal').modal('show');
}

function closeShowVoucherModal() {
    $('#Show-Voucher-Modal').modal('hide');
}

async function selectVoucher(voucher) {
    voucherId = voucher.dataset.id;
    voucherValue = voucher.dataset.value;
    voucherType = voucher.dataset.type;
    $('#Show-Voucher-Modal').modal('hide');
    $('#voucher-description').text("Giảm ngay " + voucher.dataset.value + (voucher.dataset.type === 'percent' ? '%' : 'VND') + " cho đơn hàng tiếp theo!");
    $('#voucher-code').text(voucher.dataset.code);
    $('#voucher-expiry').text("Hết hạn: " + voucher.dataset.expiry);
    $('#voucher-body').css('display', 'flex');
    if ($(movie_id).val()) {
        let price = await getPrice($(movie_id).val());
        if (voucherId) {
            if (voucherType === 'percent') {
                price = price * (1 - voucherValue / 100);
            } else {
                price = price - voucherValue;
            }
        }
        $('#price').val(price);
    }
}

async function deleteVoucher() {
    voucherId = null;
    voucherValue = null;
    voucherType = null;
    $('#voucher-body').css('display', 'none');
    if ($(movie_id).val()) {
        let price = await getPrice($(movie_id).val());
        $('#price').val(price);
    }
}

async function applyStatus() {
    seatStatus = $('#status').val();
    if (seatStatus !== 'unplaced') {
        const seatIndex = selectedSeats.findIndex(seat => seat[0] === seatChoosen);
        if (seatIndex !== -1) {
            selectedSeats[seatIndex][1] = seatStatus;
            $('#' + seatChoosen + ' .child-div').empty();
            $('#' + seatChoosen + ' .child-div').append(`<span>${seatStatus}</span>`);
        } else {
            selectedSeats.push([seatChoosen, seatStatus]);
            if ($('#' + seatChoosen + ' .child-div').length === 0) {
                $('#' + seatChoosen).append('<div class="child-div"></div>');
            }
            $('#' + seatChoosen + ' .child-div').empty();
            $('#' + seatChoosen + ' .child-div').append(`<span>${seatStatus}</span>`);
        }
        $('#' + seatChoosen).css('background-color', 'rgb(255,255,153)');
        $('#' + seatChoosen).data('status', seatStatus);
        if (changedSeatStatus === false) {
            $('#seat_number').val(await getSeatNumber(seatChoosen));
            $('#seat_status').val(selectedSeats[0][1]);
        }
        seatChoosen = null;
    } else {
        $('#' + seatChoosen).css('background-color', 'rgb(204, 229, 255)');
        selectedSeats = selectedSeats.filter(seat => seat[0] !== seatChoosen);
        $('#' + seatChoosen + ' .child-div').empty();
        $('#' + seatChoosen).data('status', seatStatus);
        seatChoosen = null;
        if (changedSeatStatus === false) {
            $('#seat_number').val(await getSeatNumber(ticket.seat_id));
            $('#seat_status').val(ticket.status);
        }
    }
    if (selectedSeats.length > 1) {
        selectedSeats[0][1] = 'unplaced';
        $('#' + selectedSeats[0][0]).css('background-color', 'rgb(204, 229, 255)');
        $('#' + selectedSeats[0][0] + ' .child-div').empty();
        selectedSeats.splice(0, 1)
    }
    $('#Select-Status-Modal').modal('hide');
}

async function fillSeatsContainer() {
    $('#seats_container').empty();
    $('#another_seats_container').empty();
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
                .on('click', function () {
                    if (!isBooked) {
                        seatChoosen = $(this).attr('id');
                        $('#Select-Status-Modal').modal('show');
                        if ($(this).data('status')) {
                            $('#status').val($(this).data('status'));
                        } else {
                            $('#status').val('unplaced');
                        }
                    } else {
                        alert('This seat is already booked.');
                    }
                });
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
                .on('click', function () {
                    if (!isBooked) {
                        seatChoosen = $(this).attr('id');
                        $('#Select-Status-Modal').modal('show');
                        if ($(this).data('status')) {
                            $('#status').val($(this).data('status'));
                        } else {
                            $('#status').val('unplaced');
                        }
                    } else {
                        alert('This seat is already booked.');
                    }
                });
            $('#another_seats_container').append(seatDiv);
        }
    });
}

async function getSeatNumber(id) {
    const response = await fetch(`${baseUrl}/seats/getSeatNumber/${id}`);
    const seatNumber = await response.json();
    return seatNumber;
}

async function getSchedule(id) {
    const response = await fetch(`${baseUrl}/movies/features/getSchedule/${id}`);
    const schedule = await response.json();
    return schedule;
}

async function getPrice(id) {
    const response = await fetch(`${baseUrl}/movies/features/getPrice/${id}`);
    const price = await response.json();
    return price;
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

async function getScheduleId(movie, date, auditorium) {
    const response = await fetch(`${baseUrl}/schedules/getSchedule/${movie}/${date}/${auditorium}`);
    const scheduleId = await response.json();
    return scheduleId;
}

async function getShowtimeOfSchedule(schedule) {
    const response = await fetch(`${baseUrl}/showtimes/getShowtimeOfSchedule/${schedule}`);
    const showtime = await response.json();
    return showtime;
}

async function getDatesOfMovieAndAuditorium(movie, auditorium) {
    const response = await fetch(`${baseUrl}/schedules/getDatesOfMovieAndAuditorium/${movie}/${auditorium}`);
    const dates = await response.json();
    return dates;
}

async function getDateOfMovieAndShowtime(movie, showtime) {
    const response = await fetch(`${baseUrl}/schedules/getDateOfMovieAndShowtime/${movie}/${showtime}`);
    const date = await response.json();
    return date;
}

async function getScheduleId(movie, date, auditorium) {
    const response = await fetch(`${baseUrl}/schedules/getSchedule/${movie}/${date}/${auditorium}`);
    const schedule = await response.json();
    return schedule;
}

async function getSeatId(seat_number, auditorium) {
    const response = await fetch(`${baseUrl}/seats/getSeatId/${seat_number}/${auditorium}`);
    const seatId = await response.json();
    return seatId;
}

async function filter() {
    $('#date').empty();
    $('#showtime_id').empty();
    $('#auditorium_id').empty();
    const data = await getSchedule($(movie_id).val());
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
        $('#showtime_id').append('<option value="">Select showtime</option>');
        showtimes.forEach(showtime => {
            if (showtime.id == showtime_id) {
                $('#showtime_id').append(`<option value="${showtime.id}" selected>${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            } else {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            }
        });
        $('#auditorium_id').prop('disabled', true);
        $('#auditorium_id').append('<option value="">Select auditorium</option>');
        $('#auditorium_id').append(`<option value="${auditorium_id}" selected>${auditorium_name}</option>`);
    } else if (currentFilter == 'showtime') {
        data.showtimes.forEach(showtime => {
            if (showtime.id == showtime_id) {
                $('#showtime_id').append(`<option value="${showtime.id}" selected>${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            } else {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            }
        });
        const date = await getDateOfMovieAndShowtime($('#movie_id').val(), $('#showtime_id').val());
        date.forEach(day => {
            if (day == date) {
                $('#date').append(`<option value="${day}" selected>${day}</option>`);
            } else {
                $('#date').append(`<option value="${day}">${day}</option>`);
            }
        });
        $('#showtime_id').prop('disabled', false);
        $('#date').prop('disabled', false);
        $('#auditorium_id').prop('disabled', true);
        $('#auditorium_id').append('<option value="">Select auditorium</option>');
        $('#auditorium_id').append(`<option value="${auditorium_id}" selected>${auditorium_name}</option>`);
    } else {
        const auditoriumsArray = Array.isArray(data.auditoriums) ? data.auditoriums : Object.values(data.auditoriums);
        auditoriumsArray.forEach(auditorium => {
            if (auditorium.id == auditorium_id) {
                $('#auditorium_id').append(`<option value="${auditorium.id}" selected>${auditorium.name}</option>`);
            } else {
                $('#auditorium_id').append(`<option value="${auditorium.id}">${auditorium.name}</option>`);
            }
        });
        const dates = await getDatesOfMovieAndAuditorium($('#movie_id').val(), $('#auditorium_id').val());
        $('#auditorium_id').prop('disabled', false);
        $('#date').prop('disabled', false);
        $('#date').empty();
        $('#date').append('<option value="">Select date</option>');
        dates.forEach(day => {
            if (day == date) {
                $('#date').append(`<option value="${day}" selected>${day}</option>`);
            } else {
                $('#date').append(`<option value="${day}">${day}</option>`);
            }
        });
        $('#showtime_id').prop('disabled', true);
        $('#showtime_id').append('<option value="">Select showtime</option>');
        $('#showtime_id').append(`<option value="${showtime_id}" selected>${showtime_value}</option>`);
    }
}

async function updateTicket() {
    let data = {};
    const url = `/admin/tickets/${ticket.id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (changedSeatStatus === true) {
        if ($('#date').val() == '') {
            alert("Please select date");
            return;
        }

        if ($('#auditorium_id').val() == '') {
            alert("Please select auditorium");
            return;
        }

        if ($('#showtime_id').val() == '') {
            alert("Please select showtime");
            return;
        }

        if (selectedSeats.length == 0) {
            alert("Please select at least one seat");
            return;
        }
        const scheduleId = await getScheduleId(movie, $('#date').val(), $('#auditorium_id').val());
        data = {
            seat_id: selectedSeats[0][0],
            status: selectedSeats[0][1],
            showtime_id: $('#showtime_id').val(),
            schedule_id: scheduleId,
            customer_id: $('#customer_id').val(),
            price: $('#price').val(),
            voucher_id: voucherId,
        };
    } else {
        const seat_id = await getSeatId($('#seat_number').val(), $('#auditorium_id').val());
        data = {
            seat_id: seat_id,
            status: $('#seat_status').val(),
            price: $('#price').val(),
            voucher_id: voucherId,
        };
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
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            window.location.href = '/admin/tickets';
        })
        .catch(error => {
            alert('An error occurred while updating the ticket: ' + error.message);
        });
}

$(document).ready(async function () {
    await filter();
    await fillSeatsContainer();
    $('#date-filter').prop('checked', true);

    $('input[name="group"]').on('change', function () {
        if (!$(this).is(':checked')) {
            $(this).prop('checked', true);
        } else {
            $('input[name="group"]').not(this).prop('checked', false);
        }
    });

    $('#date-filter').on('change', async function () {
        $('#date').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'date';
        $('#date-container').css('order', 1);
        $('#showtime-container').css('order', 2);
        $('#auditorium-container').css('order', 3);
        await filter();
        fillSeatsContainer();

    });
    $('#auditorium-filter').on('change', async function () {
        $('#auditorium_id').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'auditorium';
        $('#date-container').css('order', 2);
        $('#showtime-container').css('order', 3);
        $('#auditorium-container').css('order', 1);
        await filter();
        fillSeatsContainer();

    });
    $('#showtime-filter').on('change', async function () {
        $('#showtime_id').prop('disabled', !$(this).is(':checked'));
        currentFilter = 'showtime';
        $('#date-container').css('order', 2);
        $('#showtime-container').css('order', 1);
        $('#auditorium-container').css('order', 3);
        await filter();
        fillSeatsContainer();

    });

    $('#customer_id').on('change', async function () {
        $('#status').empty();
        if ($(this).val() != '') {
            $('#status').append('<option value="unplaced">Unplaced</option>');
            $('#status').append('<option value="ordered">Ordered</option>');
            $('#status').append('<option value="settled">Settled</option>');
        } else {
            $('#status').append('<option value="unplaced">Unplaced</option>');
            $('#status').append('<option value="settled">Settled</option>');
            selectedSeats[0][1] = 'settled';
            $('#' + selectedSeats[0][0] + ' .child-div').empty();
            $('#' + selectedSeats[0][0] + ' .child-div').append(`<span>${selectedSeats[0][1]}</span>`);
            $('#' + selectedSeats[0][0]).data('status', selectedSeats[0][1]);
        }
    });

    $('#date').on('change', async function () {
        changeSeatStatus()
        if (currentFilter == 'date') {
            const showtimes = await getShowtimesOfMovieAndDate($('#date').val(), $('#movie_id').val());
            $('#showtime_id').empty();
            $('#showtime_id').append('<option value="">Select showtime</option>');
            $('#auditorium_id').prop('disabled', true);
            $('#auditorium_id').val('');
            showtimes.forEach(showtime => {
                $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)}
                 - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
            });
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        } else if (currentFilter == 'auditorium') {
            if ($('#date').val() != '') {
                const schedule = await getScheduleId($('#movie_id').val(), $('#date').val(), $('#auditorium_id').val());
                const showtime = await getShowtimeOfSchedule(schedule);
                $('#showtime_id').empty();
                $('#showtime_id').append('<option value="">Select showtime</option>');
                showtime.forEach(showtime => {
                    $('#showtime_id').append(`<option value="${showtime.id}">${convertTimeToHourAndMinute(showtime.start_time)} - ${convertTimeToHourAndMinute(showtime.end_time)}</option>`);
                });
                $('#showtime_id').prop('disabled', false);
                $('#seats_container').empty();
                $('#another_seats_container').empty();
            } else {
                $('#showtime_id').prop('disabled', true);
                $('#showtime_id').val('');
                $('#seats_container').empty();
                $('#another_seats_container').empty();
            }
        } else {
            if ($('#date').val() != '') {
                const auditoriums = await getAuditoriumsOfShowtime($('#date').val(), $('#movie_id').val(), $('#showtime_id').val());
                $('#auditorium_id').empty();
                $('#auditorium_id').append('<option value="">Select auditorium</option>');
                auditoriums.forEach(auditorium => {
                    $('#auditorium_id').append(`<option value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`);
                });
                $('#auditorium_id').prop('disabled', false);
                $('#seats_container').empty();
                $('#another_seats_container').empty();
            } else {
                $('#seats_container').empty();
                $('#another_seats_container').empty();
            }
        }
    });

    $('#showtime_id').on('change', async function () {
        changeSeatStatus()
        if (currentFilter == 'date') {
            if ($('#showtime_id').val() != '') {
                const auditoriums = await getAuditoriumsOfShowtime($('#date').val(), $('#movie_id').val(), $('#showtime_id').val());
                $('#auditorium_id').empty();
                $('#auditorium_id').append('<option value="">Select auditorium</option>');
                auditoriums.forEach(auditorium => {
                    $('#auditorium_id').append(`<option value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`);
                });
                $('#auditorium_id').prop('disabled', false);
            } else {
                $('#seats_container').empty();
                $('#another_seats_container').empty();
                $('#auditorium_id').prop('disabled', true);
                $('#auditorium_id').val('');
            }
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        } else if (currentFilter == 'auditorium') {
            await fillSeatsContainer();
        } else {
            const dates = await getDateOfMovieAndShowtime($('#movie_id').val(), $('#showtime_id').val());
            $('#date').empty();
            $('#date').append('<option value="">Select date</option>');
            dates.forEach(day => {
                $('#date').append(`<option value="${day}">${day}</option>`);
            });
            $('#date').val('');
            $('#auditorium_id').prop('disabled', true);
            $('#auditorium_id').val('');
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        }
    });

    $('#auditorium_id').on('change', async function () {
        changeSeatStatus()
        if (currentFilter == 'date') {
            if ($('#auditorium_id').val() != '') {
                await fillSeatsContainer();
            } else {
                $('#seats_container').empty();
                $('#another_seats_container_lable').empty();
            }
        } else if (currentFilter == 'auditorium') {
            const dates = await getDatesOfMovieAndAuditorium($('#movie_id').val(), $('#auditorium_id').val());
            $('#date').empty();
            $('#date').append('<option value="">Select date</option>');
            dates.forEach(day => {
                $('#date').append(`<option value="${day}">${day}</option>`);
            });
            $('#date').val('');
            $('#showtime_id').val('');
            $('#showtime_id').prop('disabled', true);
            await fillSeatsContainer();
        } else {
            await fillSeatsContainer();
        }
    });
});
