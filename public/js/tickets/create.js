let count = 0;
const date = moment().format("Y-M-D");

function formatNumber(number) {
    return new Intl.NumberFormat("vi-VN").format(number);
}

async function fetchCustomer(phone) {
    const response = await fetch(`/admin/tickets/${phone}/customer`);

    if (!response.ok) {
        const { message } = await response.json();
        Swal.fire({
            icon: "error",
            title: "Error",
            text: message,
        });
        return;
    }
    const customer = await response.text();
    return customer;
}

async function fetchShowtimes(movie, date) {
    const response = await fetch(`/admin/tickets/${movie}/${date}/showtimes`);
    const showtimes = await response.text();
    return showtimes;
}

async function fetchAuditoriums(movie, date, showtime) {
    const response = await fetch(
        `/admin/tickets/${movie}/${date}/${showtime}/auditoriums`
    );
    const auditoriumes = await response.text();
    return auditoriumes;
}

async function fetchSeats(movie, date, showtime, auditorium) {
    const response = await fetch(
        `/admin/tickets/${movie}/${date}/${showtime}/${auditorium}/seats`
    );
    const seats = await response.text();
    return seats;
}

async function fetchVouchers(customer) {
    const response = await fetch(`/admin/tickets/${customer}/vouchers`);
    const vouchers = await response.text();
    return vouchers;
}

$(".search-btn").click(async function (event) {
    event.preventDefault();
    const phone = $(this).prev("input").val();
    const customer = await fetchCustomer(phone);
    if (customer) {
        $(".info").html(customer);
    }
});

$(".phone-number").on("keydown", async function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        const customer = await fetchCustomer($(this).val());
        if (customer) {
            $(".info").html(customer);
        }
    }
});

$("#movie").on("change", async function () {
    if (this.value) {
        const showtimes = await fetchShowtimes(this.value, date);
        $("#showtime").html(showtimes);
    }
});

$("#showtime").on("change", async function () {
    if (this.value) {
        const movie = $("#movie").val();
        const auditoriums = await fetchAuditoriums(movie, date, this.value);
        $("#seats").html("");
        $("#auditorium").html(auditoriums);
    }
});

$("#auditorium").on("change", async function () {
    if (this.value) {
        const movie = $("#movie").val();
        const showtime = $("#showtime").val();
        const seats = await fetchSeats(movie, date, showtime, this.value);
        $("#seats").html(seats);
    }
});

$(document).on("click", ".seat", function () {
    const price = $("#movie option:selected").data("price");

    count = document.querySelectorAll(".seat.bg-primary").length;

    if ($(this).hasClass("bg-secondary-subtle")) {
        return;
    }

    if ($(this).hasClass("bg-primary")) {
        count -= 1;
    } else {
        count += 1;
    }

    if (count > 0 && $("#customer").val()) {
        $(".voucher").removeClass("hidden");
    } else {
        $(".voucher").addClass("hidden");
    }

    handlePrice(price, count);

    $(this).toggleClass("bg-primary");
});

$(document).on("click", ".voucher", async function () {
    if ($(".use-btn").hasClass("bg-secondary-subtle")) {
        return;
    }
    const vouchers = await fetchVouchers($("#customer").val());
    $("#vouchers").html(vouchers);
});

function handlePrice(price, count) {
    let discount = 0;
    const eventPrice = $("#movie option:selected").data("event-price");
    const voucher = $(".use-btn.bg-secondary-subtle");
    const value = voucher.data("value");
    const type = voucher.data("type");
    let total = price * count;

    if (type === "percent") {
        discount = (value / 100) * total;
    } else {
        discount = value;
    }

    if (eventPrice) {
        $(".event-price").text(`${formatNumber(total)} VND`);
        total = eventPrice * count;
    }

    $(".price").text(`${formatNumber(total)} VND`);

    if (discount > 0) {
        $(".discount").parent().removeClass("hidden");
        $(".discount").text(`- ${formatNumber(discount)} VND`);
        $(".total").data("total", total - discount);
        $(".total").text(`Total: ${formatNumber(total - discount)} VND`);
    } else {
        $(".discount").parent().addClass("hidden");
        $(".total").data("total", total);
        $(".total").text(`Total: ${formatNumber(total)} VND`);
    }
}

$(document).on("click", ".use-btn", function () {
    const price = $("#movie option:selected").data("price");
    count = document.querySelectorAll(".seat.bg-primary").length;
    $(".use-btn").not(this).removeClass("bg-secondary-subtle");
    $(this).toggleClass("bg-secondary-subtle");
    handlePrice(price, count);
});

function utf8ToBase64(str) {
    return btoa(encodeURIComponent(str));
}

$(".ticket-form").on("submit", function (event) {
    event.preventDefault();
    const price = $(".total").data("total");
    const eventPrice = $("#movie option:selected").data("event-price");
    const seats = Array.from(document.querySelectorAll(".seat.bg-primary")).map(
        (seat) => ({ seatId: seat.dataset.id, seatName: seat.dataset.name })
    );
    const voucherId = $(".use-btn.bg-secondary-subtle").data("id");
    const auditorium = $("#auditorium option:selected").text();
    const showtime = $("#showtime option:selected").text();
    const discount = $(".discount").text();
    let eventDiscount = price - eventPrice;

    if (eventDiscount == price) {
        eventDiscount = 0;
    }

    const data = {
        price,
        seats,
        voucherId,
        auditorium,
        showtime,
        discount,
        eventDiscount,
        date,
    };

    $("#order_id").val(btoa(JSON.stringify(data)));
    $(this).off("submit").submit();
});
