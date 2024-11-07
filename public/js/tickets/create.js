const baseUrl = 'http://localhost/admin';
let customerInput = true;
let selectedSeats = [];
let seatChoosen = null;
let seatStatus = 'unplaced';
let successArray = [];
let successTicketConfirmationMail = [];
let errorArray = [];
let voucherId = null;
let voucherValue = null;
let voucherType = null;
let events = [];
let eventDiscount = 0;
let price = 0;
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

async function getEventsOfDateAndMovie(date, movie) {
    const response = await fetch(`${baseUrl}/events/getEventsOfDateAndMovie/${date}/${movie}`);
    events = await response.json();
}

async function getVouchersOfCustomer(customer) {
    const response = await fetch(`${baseUrl}/vouchers/getVoucherOfCustomer/${customer}`);
    const vouchers = await response.json();
    return vouchers;
}

async function getVoucherInfo(id) {
    const response = await fetch(`${baseUrl}/vouchers/getVoucherInfo/${id}`);
    const voucher = await response.json();
    return voucher;
}

async function getSeatNumber(id) {
    const response = await fetch(`${baseUrl}/seats/getSeatNumber/${id}`);
    const seat = await response.json();
    return seat;
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

function convertTimeToDecimal(timeString) {
    const [hours, minutes, seconds] = timeString.split(':').map(Number);
    const decimalHours = hours + (minutes / 60) + (seconds / 3600);
    return decimalHours;
}

async function priceCalculation() {
    const filmPrice = await getPrice($(movie_id).val());
    eventDiscount = 0;
    if (voucherId) {
        if (voucherType === 'percent') {
            eventDiscount += filmPrice * (voucherValue / 100);
        } else {
            eventDiscount += parseInt(voucherValue);
        }
    }
    events.forEach(event => {

        if (event.number_of_tickets === 1) {
            if (event.quantity !== 0 && event.all_day) {
                eventDiscount += event.discount_percentage / 100 * filmPrice;
            }
            if (!event.all_day && $('#showtime_id').val() !== '') {
                if (convertTimeToDecimal($('#showtime_id').find('option:selected').data('start-time').toString()) >= convertTimeToDecimal(event.start_time.toString())
                    && convertTimeToDecimal($('#showtime_id').find('option:selected').data('end-time').toString()) <= convertTimeToDecimal(event.end_time.toString())) {
                    eventDiscount += event.discount_percentage / 100 * filmPrice;
                }
            }
        } else {
            if (selectedSeats.length >= event.number_of_tickets) {
                if (event.quantity !== 0 && event.all_day) {
                    eventDiscount += event.discount_percentage / 100 * filmPrice;
                }
                if (!event.all_day && $('#showtime_id').val() !== '') {
                    if (convertTimeToDecimal($('#showtime_id').find('option:selected').data('start-time').toString()) >= convertTimeToDecimal(event.start_time.toString())
                        && convertTimeToDecimal($('#showtime_id').find('option:selected').data('end-time').toString()) <= convertTimeToDecimal(event.end_time.toString())) {
                        eventDiscount += event.discount_percentage / 100 * filmPrice;
                    }
                }
            }
        }
    });
    price = filmPrice - eventDiscount;
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
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a customer.',
                });
                return;
            }
        }
        if (!$(movie_id).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a movie.',
            });
            return;
        }
        if (!$(date).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a date.',
            });
            return;
        }
        if (!$(auditorium_id).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an auditorium.',
            });
            return;
        }
        const scheduleId = await getScheduleId($(movie_id).val(), $(date).val(), $(auditorium_id).val());
        if (!$(showtime_id).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a showtime.',
            });
            return;
        }
        if (selectedSeats.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select at least one seat.',
            });
            return;
        }
        for (const seat of selectedSeats) {
            const data = {
                action: 'create',
                user_id: $('#user_id').val(),
                seat_id: seat[0],
                status: seat[1],
                customer_id: $('#customer_id').val(),
                showtime_id: $('#showtime_id').val(),
                schedule_id: scheduleId,
                price: price,
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
                    successTicketConfirmationMail.push(seat);
                })
                .catch(error => {
                    errorArray.push(error.message);
                });
        }
        if (successArray.length > 0 && customerInput) {
            $('#loader').css('display', 'flex');
            await ticketConfirmationMail();
        }
        updateFetchSeatsModal(successArray, errorArray);

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message,
        });
    }

}

async function ticketConfirmationMail() {
    const url = '/admin/tickets/ticketConfirmationMail';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        successTicketConfirmationMail.forEach(async seat => {
            seat[0] = await getSeatNumber(seat[0]);
        });
        let voucher = null;
        if (voucherId) {
            voucher = await getVoucherInfo(voucherId);
        }
        const data = {
            action: 'ticketConfirmationMail',
            order_date: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
            customer: $('#customer-name').val(),
            customer_email: $('#customer-email').val(),
            movie: $('#movie_name').find('option:selected').text(),
            date: $('#date').find('option:selected').text(),
            auditorium: $('#auditorium_id').find('option:selected').data('auditorium'),
            showtime: $('#showtime_id').find('option:selected').data('start-time') + ' - ' + $('#showtime_id').find('option:selected').data('end-time'),
            seats: successTicketConfirmationMail,
            total: await getPrice($(movie_id).val()) * successTicketConfirmationMail.length,
            voucher: voucher,
            event_discount: eventDiscount,
            cost: price * successTicketConfirmationMail.length
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
                $('#loader').css('display', 'none');
                return response.json();
            })
            .then(data => {
                $('#loader').css('display', 'none');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                });
                console.log(data);
            })
            .catch(error => {
                $('#loader').css('display', 'none');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                });
                console.log(error);
            });

    } catch (error) {
        $('#loader').css('display', 'none');
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message,
        });
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
        await priceCalculation();
        $('#price').val(price);
    }
}

async function deleteVoucher() {
    voucherId = null;
    voucherValue = null;
    voucherType = null;
    $('#voucher-body').css('display', 'none');
    if ($(movie_id).val()) {
        await priceCalculation();
        $('#price').val(price);
    }
}

async function switchCustomerInput() {
    $('#voucher-body').css('display', 'none');
    voucherId = null;
    voucherValue = null;
    voucherType = null;
    await priceCalculation();
    $('#price').val(price);
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
        $('#show-voucher-button').css('display', 'none');
    } else {
        $('#show-voucher-button').css('display', 'none');
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
        seatChoosen = null;
    } else {
        $('#' + seatChoosen).css('background-color', 'rgb(204, 229, 255)');
        selectedSeats = selectedSeats.filter(seat => seat[0] !== seatChoosen);
        $('#' + seatChoosen + ' .child-div').empty();
        $('#' + seatChoosen).data('status', seatStatus);
        seatChoosen = null;
    }
    $('#Select-Status-Modal').modal('hide');
    await priceCalculation();
    $('#price').val(price);
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
            $('#show-voucher-button').css('display', 'block');
            const customer = await getCustomerInfor($(this).val());
            $('#customer-name').val(customer.name);
            $('#customer-email').val(customer.email);
            $('#customer-phone').val(customer.phone_number);
            $('#customer-address').val(customer.address);
            $('#customer-gender').val(customer.gender);
            $('#customer-date-of-birth').val(customer.birth_date);
            const vouchers = await getVouchersOfCustomer(customer.id);
            $('#voucher-list').empty();
            vouchers.forEach(voucher => {
                $('#voucher-list').append(`<div class="voucher" data-id="${voucher.id}" data-value="${voucher.value}"
                                data-type="${voucher.type}" data-code="${voucher.code}"
                                data-expiry="${voucher.expires_at}" onclick="selectVoucher(this)"
                                style="background-image: url('/images/voucher_background.jpg');">
                                <h1 class="label">Voucher Giảm Giá</h1>
                                <p class="description">Nhận ngay ${voucher.value}
                                    ${voucher.type == 'percent' ? '%' : 'VND'} cho đơn hàng tiếp theo!
                                </p>
                                <div class="code text-uppercase">${voucher.code}</div>
                                <div class="expiry">Hết hạn: ${voucher.expires_at}</div>
                            </div>`);
            });
        } else {
            $('#show-voucher-button').css('display', 'none');
            $('#customer-name').val('');
            $('#customer-email').val('');
            $('#customer-phone').val('');
            $('#customer-address').val('');
            $('#customer-gender').val('');
            $('#customer-date-of-birth').val('');
        }
    });

    $('#movie_name').on('change', async function () {
        events = [];
        $('#availableEventsButton').css('display', 'none');
        $('#movie_id').val($(this).val());
        await priceCalculation();
        $('#price').val(price);
        if ($(this).val() !== '') {
            $('#date').prop('disabled', false);
        } else {
            $('#date').prop('disabled', true);
        }
        await resetFilter();
    });

    $('#movie_id').on('change', async function () {
        events = [];
        $('#availableEventsButton').css('display', 'none');
        $('#movie_name').val($(this).val());
        await priceCalculation();
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
            events = [];
            const showtimes = await getShowtimesOfMovieAndDate(date, $(movie_id).val());
            $('#showtime_id').prop('disabled', false);
            $('#showtime_id').empty();
            $('#showtime_id').append(`<option value="">--Select Showtime--</option>`);
            showtimes.forEach(showtime => {
                $('#showtime_id').append(`<option data-start-time="${showtime.start_time}" data-end-time="${showtime.end_time}" value="${showtime.id}">${showtime.start_time} - ${showtime.end_time}</option>`);
            });
            $('#auditorium_id').val('');
            await getEventsOfDateAndMovie($("#date").val(), $('#movie_id').val());
            if (events.length > 0) {
                $('#availableEventsButton').css('display', 'block');
                const datatableBody = $('#datatable tbody');
                datatableBody.empty();
                events.forEach(async event => {
                    datatableBody.append(`
                        <tr>
                            <td>${event.id}</td>
                            <td>${event.title}</td>
                            <td>${event.start_time ? event.start_time : '00:00'}</td>
                            <td>${event.end_time ? event.end_time : '24:00'}</td>
                            <td>${event.number_of_tickets}</td>
                            <td>${event.quantity === -1 ? '∞' : event.quantity}</td>
                            <td>${event.discount_percentage} %</td>
                        </tr>`);
                });
                await priceCalculation();
                $('#price').val(price);
            } else {
                $('#availableEventsButton').css('display', 'none');
            }
        } else {
            events = [];
            $('#availableEventsButton').css('display', 'none');
            $('#showtime_id').prop('disabled', true);
            $('#auditorium_id').prop('disabled', true);
            $('#showtime_id').val('');
            $('#auditorium_id').val('');
            await priceCalculation();
            $('#price').val(price);
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
                    $('#auditorium_id').append(`<option data-auditorium="${auditorium.auditorium}" value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`);
                });
            } else {
                $('#auditorium_id').prop('disabled', true);
                $('#auditorium_id').val('');
            }
            selectedSeats.length = 0;
            $('#seats_container').empty();
            $('#another_seats_container').empty();
            await priceCalculation();
            $('#price').val(price);
        } else {
            await priceCalculation();
            $('#price').val(price);
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'This seat is already booked.',
                                });
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
