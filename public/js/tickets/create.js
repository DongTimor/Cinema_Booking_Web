const baseUrl = "http://localhost/admin";
let selectedSeats = [];
let successTicketConfirmationMail = [];
let voucherId = null;
let voucherValue = null;
let voucherType = null;
let events = null;
let price = 0;
let eventDiscount = 0;
let voucherDiscount = 0;

function paramsBuilder(action, params) {
    const queryString = new URLSearchParams(params).toString();
    return queryString + '&action=' + action;
}

async function fetchShowtimes(date, movie) {
    const params = {
        date: date,
        movie: movie
    };
    const queryString = paramsBuilder('for-movie', params);
    const response = await fetch(
        `${baseUrl}/showtimes/get-showtimes?${queryString}`
    );
    const showtimes = await response.json();
    return showtimes;
}

async function getAuditoriumsOfShowtime(date, movie, showtime) {
    const response = await fetch(
        `${baseUrl}/auditoriums/getAuditoriumsOfShowtime/${date}/${movie}/${showtime}`
    );
    const auditoriumes = await response.json();
    return auditoriumes;
}

async function getSchedule(id) {
    const response = await fetch(
        `${baseUrl}/movies/features/getSchedule/${id}`
    );
    const schedule = await response.json();
    return schedule;
}

async function getSeatsOfAuditorium(auditorium, orderedSeats) {
    const response = await fetch(
        `${baseUrl}/auditoriums/seats/${auditorium}/${orderedSeats}`
    );
    const seats = await response.text();
    return seats;
}

async function getTicketOfSchedule(movie, date, auditorium, showtime) {
    const response = await fetch(
        `${baseUrl}/tickets/getTicketsOfSchedule/${movie}/${date}/${auditorium}/${showtime}`
    );
    const ticket = await response.text();
    return ticket;
}

async function getPrice(id) {
    const response = await fetch(`${baseUrl}/movies/features/getPrice/${id}`);
    const price = await response.json();
    return price;
}

async function getEventsOfDateAndMovie(date, movie) {
    const response = await fetch(
        `${baseUrl}/events/getEventsOfDateAndMovie/${date}/${movie}`
    );
    events = await response.json();
}

async function getVouchersOfCustomer(customer) {
    const response = await fetch(
        `${baseUrl}/vouchers/getVoucherOfCustomer/${customer}`
    );
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

function convertTimeToDecimal(timeString) {
    const [hours, minutes, seconds] = timeString.split(":").map(Number);
    const decimalHours = hours + minutes / 60 + seconds / 3600;
    return decimalHours;
}

async function priceCalculation() {
    if(count !== 0) {
        const filmPrice = $("#movie option:selected").data("price");
        voucherDiscount = 0;
        eventDiscount = 0;
        if (voucherId) {
            $('.voucher-id').val(voucherId);
            if (voucherType === "percent") {
                voucherDiscount += filmPrice * (voucherValue / 100);
            } else {
                voucherDiscount += parseInt(voucherValue);
            }
        }
        if(events){
            if (events.number_of_tickets === 1) {
                if (events.quantity !== 0 && events.all_day) {
                    eventDiscount += (events.discount_percentage / 100) * filmPrice;
                }
                if (!events.all_day && $("#showtime").val() !== "") {
                    if (
                        convertTimeToDecimal(
                            $("#showtime")
                                .find("option:selected")
                                .data("start-time")
                        ) >= convertTimeToDecimal(events.start_time.toString()) &&
                        convertTimeToDecimal(
                            $("#showtime")
                                .find("option:selected")
                                .data("end-time")
                        ) <= convertTimeToDecimal(events.end_time.toString())
                    ) {
                        eventDiscount +=
                            (events.discount_percentage / 100) * filmPrice;
                    }
                }
            } else {
                if (selectedSeats.length >= events.number_of_tickets) {
                    if (events.quantity !== 0 && events.all_day) {
                        eventDiscount +=
                            (events.discount_percentage / 100) * filmPrice;
                    }
                    if (!events.all_day && $("#showtime").val() !== "") {
                        if (
                            convertTimeToDecimal(
                                $("#showtime")
                                    .find("option:selected")
                                    .data("start-time")
                            ) >=
                                convertTimeToDecimal(events.start_time.toString()) &&
                            convertTimeToDecimal(
                                $("#showtime")
                                    .find("option:selected")
                                    .data("end-time")
                            ) <= convertTimeToDecimal(events.end_time.toString())
                        ) {
                            eventDiscount +=
                                (events.discount_percentage / 100) * filmPrice;
                        }
                    }
                }
            }
        }
        price = filmPrice - eventDiscount - voucherDiscount;
        getDiscount();
    }else{
        price = 0;
        eventDiscount = 0;
        voucherDiscount = 0;
    }
}

async function ticketConfirmationMail() {
    const url = "/admin/tickets/ticketConfirmationMail";
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    try {
        for (let i = 0; i < selectedSeats.length; i++) {
            selectedSeats[i] = await getSeatNumber(selectedSeats[i]);
        }
        let voucher = null;
        if (voucherId) {
            voucher = await getVoucherInfo(voucherId);
        }
        const data = {
            action: "ticketConfirmationMail",
            order_date: new Date().toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            }),
            customer: $("#customer-name").val() ? $("#customer-name").val() : null,
            customer_email: $("#customer-email").val() ? $("#customer-email").val() : null,
            movie: $("#movie").find("option:selected").text(),
            auditorium: $("#auditorium")
                .find("option:selected")
                .text(),
            showtime:
                $("#showtime").find("option:selected").text(),
            seats: selectedSeats,
            total:
                ($("#movie option:selected").data("price")),
            voucher: voucher,
            event_discount: eventDiscount,
            cost: price,
        };
        console.log("data", data);
        await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.status);
                }
                return response.json();
            })
            .then((data) => {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: data.message,
                });
            })
            .catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: error,
                });
            });
    } catch (error) {
        $("#loader").css("display", "none");
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message,
        });
    }
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function selectVoucher(selectedVoucher) {
    console.log("selectedVoucher", selectedVoucher);
    $(document).find('.save-btn').not("#voucher-btn-" + selectedVoucher.id).removeClass('bg-secondary-subtle');
    $(`#voucher-btn-${selectedVoucher.id}`).toggleClass('bg-secondary-subtle');
    if( $(`#voucher-btn-${selectedVoucher.id}`).hasClass('bg-secondary-subtle')){
        voucherId = selectedVoucher.id;
        voucherValue = selectedVoucher.value;
        voucherType = selectedVoucher.type;
    }else{
        voucherId = null;
        voucherValue = null;
        voucherType = null;
    }
    priceCalculation();
}

$(document).ready(function () {
    $("#movie").on("change", async function () {
        const date = $(this).data("date");
        $("#showtime").empty();
        $("#showtime").append('<option value="">-Select Showtime-</option>');
        $("#auditorium").empty();
        $("#auditorium").append('<option value="">-Select Auditorium-</option>');
        $("#seats").empty();
        if ($(this).val() !== "") {
            await getEventsOfDateAndMovie(date, $(this).val());
            const showtimes = await fetchShowtimes(date, $(this).val());
            $("#showtime").empty();
            $("#showtime").append('<option value="">-Select Showtime-</option>');
            showtimes.forEach((showtime) => {
            const startTime = moment(showtime.start_time, "HH:mm:ss").format(
                "HH:mm"
            );
            const endTime = moment(showtime.end_time, "HH:mm:ss").format(
                "HH:mm"
            );
            $("#showtime").append(
                `<option data-start-time="${showtime.start_time}" data-end-time="${showtime.end_time}" value="${showtime.id}">${startTime} - ${endTime}</option>`
                );
            });
        }
    });

    $("#showtime").on("change", async function () {
        const showtime = $(this).val();
        $("#auditorium").empty();
        $("#auditorium").append('<option value="">-Select Auditorium-</option>');
        $("#seats").empty();
        if (showtime !== "") {
            const date = $(this).data("date");
            const movie_id =  $("#movie").val();
            const auditoriums = await getAuditoriumsOfShowtime(
            date,
            movie_id,
            showtime
        );
        $("#auditorium").empty();
        $("#auditorium").append(
            '<option value="">-Select Auditorium-</option>'
        );
        auditoriums.forEach((auditorium) => {
            $("#auditorium").append(
                `<option value="${auditorium.auditorium_id}">${auditorium.auditorium}</option>`
            );
            });
        }
    });

    $("#auditorium").on("change", async function () {
        const auditorium = $(this).val();
        if (auditorium !== "" && showtime !== "") {
            const movie_id = $("#movie").val();
            const date = formatDate(new Date());
            const showtime = $("#showtime").val();
            const orderedSeats = await getTicketOfSchedule(movie_id, date, auditorium, showtime);
            let seats;
            if (orderedSeats !== "") {
                seats = await getSeatsOfAuditorium(auditorium, orderedSeats);
            }else{
                seats = await getSeatsOfAuditorium(auditorium, "null");
            }
                $('#seats').html(seats);
                $('#seats .row').append('<div class="voucher"></div>');
        }else{
            $("#seats").empty();
        }
    });

    $(".ticket-form").on("submit", function (e) {
        if ($("#customer-email").val() !== undefined) {
            ticketConfirmationMail();
        }
    });
});
