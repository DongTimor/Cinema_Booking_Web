const baseUrl = 'http://localhost/admin';
let customerInput = true;
let selectedSeats = [];
let seatChoosen = null;
let seatStatus = 'unplaced';
let successArray = [];
let errorArray = [];
let voucherId = null;
let voucherValue = null;
let voucherType = null;

async function getCustomerInfor(id) {
    const response = await fetch(`${baseUrl}/getCustomerInfor/${id}`);
    const customer = await response.json();
    return customer;
}

async function getShowtimesOfMovieAndDate(date, movie) {
    const response = await fetch(`${baseUrl}/showtimes/getShowtimesOfMovieAndDate/${date}/${movie}`);
    const showtimes = await response.json();
    return showtimes;
}

async function getAuditoriumsOfShowtime(date, movie, showtime) {
    const response = await fetch(`${baseUrl}/auditoriums/getAuditoriumsOfShowtime/${date}/${movie}/${showtime}`);
    const auditoriumes = await response.json();
    return auditoriumes;
}

async function getSchedule(id) {
    const response = await fetch(`${baseUrl}/movies/features/getSchedule/${id}`);
    const schedule = await response.json();
    return schedule;
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

async function getPrice(id) {
    const response = await fetch(`${baseUrl}/movies/features/getPrice/${id}`);
    const price = await response.json();
    return price;
}

async function resetFilter() {
    $('#date').empty();
    $('#showtime_id').empty();
    $('#auditorium_id').empty();
    $('#seats_container').empty();
    $('#another_seats_container').empty();
    selectedSeats.length = 0;
    const data = await getSchedule($(movie_id).val());
    $('#date').append(`<option value="">--Select Date--</option>`);
    $('#showtime_id').append(`<option value="">--Select Showtime--</option>`);
    $('#auditorium_id').append(`<option value="">--Select Auditorium--</option>`);

    if (data) {
        data.dates.forEach(date => {
            $('#date').append(`<option value="${date}">${date}</option>`);
        });
    }
}

async function getScheduleId(movie, date, auditorium) {
    const response = await fetch(`${baseUrl}/schedules/getSchedule/${movie}/${date}/${auditorium}`);
    const schedule = await response.json();
    return schedule;
}

async function createTicket() {
    const url = '/admin/tickets/create';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        if (customerInput) {
            if (!$('#customer_id').val()) {
                alert('Please select a customer.');
                return;
            }
        }
        if (!$(movie_id).val()) {
            alert('Please select a movie.');
            return;
        }
        if (!$(date).val()) {
            alert('Please select a date.');
            return;
        }
        if (!$(auditorium_id).val()) {
            alert('Please select an auditorium.');
            return;
        }
        const scheduleId = await getScheduleId($(movie_id).val(), $(date).val(), $(auditorium_id).val());
        if (!$(showtime_id).val()) {
            alert('Please select a showtime.');
            return;
        }
        if (!$('#price').val()) {
            alert('Please enter the price.');
            return;
        }
        if (selectedSeats.length === 0) {
            alert('Please select at least one seat.');
            return;
        }
        for (const seat of selectedSeats) {
            const data = {
                user_id: $('#user_id').val(),
                seat_id: seat[0],
                status: seat[1],
                customer_id: $('#customer_id').val(),
                showtime_id: $('#showtime_id').val(),
                schedule_id: scheduleId,
                price: $('#price').val(),
                voucher_id: voucherId
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
                .then(data => {
                    successArray.push(data.message);
                })
                .catch(error => {
                    errorArray.push(error.message);
                });
        }
        updateFetchSeatsModal(successArray, errorArray);

    } catch (error) {
        alert(error.message);
    }

}

function updateFetchSeatsModal(successArray, errorArray) {
    $('#successCount').text(`Success: ${successArray.length} seats`);
    $('#errorCount').text(`Error: ${errorArray.length} seats`);

    $('#successList').empty();
    $('#errorList').empty();

    $.each(successArray, function (index, item) {
        $('#successList').append(`<li class="text-success">${item}</li>`);
    });

    $.each(errorArray, function (index, item) {
        $('#errorList').append(`<li class="text-danger">${item}</li>`);
    });

    $('#Fetch-Seats-Modal').modal('show');
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

function switchCustomerInput() {
    if (customerInput) {
        $('#switch-customer-input').css('background-color', 'blue');
        $('#switch-customer-input').text('Have Account');
        $('#customer_id').val('');
        $('#customer_id').prop('disabled', true);
        $('#customer-name').val('');
        $('#customer-email').val('');
        $('#customer-phone').val('');
        $('#customer-address').val('');
        $('#customer-gender').val('');
        $('#customer-date-of-birth').val('');
        customerInput = false;
        $('#status').empty();
        $('#status').append(`<option value="unplaced">Unplaced</option>`,
            `<option value="settled">Settled</option>`);
        selectedSeats.forEach(seat => {
            seat[1] = 'settled';
            $('#' + seat[0] + ' .child-div').empty();
            $('#' + seat[0] + ' .child-div').append(`<span>${seat[1]}</span>`);
            $('#' + seat[0]).data('status', seat[1]);
        });

    } else {
        $('#switch-customer-input').css('background-color', 'green');
        $('#switch-customer-input').text('No Account');
        $('#customer_id').val('');
        $('#customer_id').prop('disabled', false);
        $('#customer-name').val('');
        $('#customer-email').val('');
        $('#customer-phone').val('');
        $('#customer-address').val('');
        $('#customer-gender').val('');
        $('#customer-date-of-birth').val('');
        customerInput = true;
        $('#status').empty();
        $('#status').append(`<option value="unplaced">Unplaced</option>`,
            `<option value="ordered">Ordered</option>`,
            `<option value="settled">Settled</option>`);
    }
}

function sortSeats(seats) {
    return seats.sort((a, b) => {
        if (a.row === b.row) {
            return a.column - b.column;
        }
        return a.row - b.row;
    });
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

function applyStatus() {
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
        seatChoosen = null;
    } else {
        $('#' + seatChoosen).css('background-color', 'rgb(204, 229, 255)');
        selectedSeats = selectedSeats.filter(seat => seat[0] !== seatChoosen);
        $('#' + seatChoosen + ' .child-div').empty();
        $('#' + seatChoosen).data('status', seatStatus);
        seatChoosen = null;
    }
    $('#Select-Status-Modal').modal('hide');
}

function openShowVoucherModal() {
    $('#Show-Voucher-Modal').modal('show');
}

function backToIndex() {
    window.location.href = '/admin/tickets';
}

function closeFetchSeatsModal() {
    $('#Fetch-Seats-Modal').modal('hide');
    window.location.reload();
}

function closeSelectStatusModal() {
    $('#Select-Status-Modal').modal('hide');
}

function closeShowVoucherModal() {
    $('#Show-Voucher-Modal').modal('hide');
}

$(document).ready(function () {
    $('#customer_id').on('change', async function () {
        if ($(this).val() !== '') {
            const customer = await getCustomerInfor($(this).val());
            $('#customer-name').val(customer.name);
            $('#customer-email').val(customer.email);
            $('#customer-phone').val(customer.phone_number);
            $('#customer-address').val(customer.address);
            $('#customer-gender').val(customer.gender);
            $('#customer-date-of-birth').val(customer.date_of_birth);
        } else {
            $('#customer-name').val('');
            $('#customer-email').val('');
            $('#customer-phone').val('');
            $('#customer-address').val('');
            $('#customer-gender').val('');
            $('#customer-date-of-birth').val('');
        }
    });

    $('#movie_name').on('change', async function () {
        $('#movie_id').val($(this).val());
        let price = await getPrice($(this).val());
        if (voucherId) {
            if (voucherType === 'percent') {
                price = price * (1 - voucherValue / 100);
            } else {
                price = price - voucherValue;
            }
        }
        $('#price').val(price);
        if ($(this).val() !== '') {
            $('#date').prop('disabled', false);
        } else {
            $('#date').prop('disabled', true);
        }
        await resetFilter();
    });

    $('#movie_id').on('change', async function () {
        $('#movie_name').val($(this).val());
        const price = await getPrice($(this).val());
        if (voucherId) {
            if (voucherType === 'percent') {
                price = price * (1 - voucherValue / 100);
            } else {
                price = price - voucherValue;
            }
        }
        $('#price').val(price);
        if ($(this).val() !== '') {
            $('#date').prop('disabled', false);
        } else {
            $('#date').prop('disabled', true);
        }
        await resetFilter();
    });

    $('#date').on('change', async function () {
        const date = $(this).val();
        if (date !== '') {
            const showtimes = await getShowtimesOfMovieAndDate(date, $(movie_id).val());
            $('#showtime_id').prop('disabled', false);
            $('#showtime_id').empty();
            $('#showtime_id').append(`<option value="">--Select Showtime--</option>`);
            showtimes.forEach(showtime => {
                $('#showtime_id').append(`<option value="${showtime.id}">${showtime.start_time} - ${showtime.end_time}</option>`);
            });
            $('#auditorium_id').val('');
        } else {
            $('#showtime_id').prop('disabled', true);
            $('#auditorium_id').prop('disabled', true);
            $('#showtime_id').val('');
            $('#auditorium_id').val('');
        }
        selectedSeats.length = 0;
        $('#seats_container').empty();
        $('#another_seats_container').empty();
    });

    $('#showtime_id').on('change', async function () {
        const showtime = $(this).val();
        if (showtime !== '') {
            const auditoriums = await getAuditoriumsOfShowtime($(date).val(), $(movie_id).val(), showtime);
            if (auditoriums) {
                $('#auditorium_id').prop('disabled', false);
                $('#auditorium_id').empty();
                $('#auditorium_id').append(`<option value="">--Select Auditorium--</option>`);
                auditoriums.forEach(auditorium => {
                    $('#auditorium_id').append(`<option value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`);
                });
            } else {
                $('#auditorium_id').prop('disabled', true);
                $('#auditorium_id').val('');
            }
            selectedSeats.length = 0;
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        } else {
            $('#auditorium_id').prop('disabled', true);
            $('#auditorium_id').val('');
            selectedSeats.length = 0;
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        }
    });

    $('#auditorium_id').on('change', async function () {
        const auditorium = $(this).val();
        if (auditorium !== '') {
            const sortedSeats = sortSeats(await getSeatsOfAuditorium(auditorium));
            const tickets = await getTicketOfSchedule($(movie_id).val(), $(date).val(), auditorium, $(showtime_id).val());
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
        } else {
            selectedSeats.length = 0;
            $('#seats_container').empty();
            $('#another_seats_container').empty();
        }
    });
});
