let count = 0;
const price = $(".movie").attr("price");

async function fetchShowtimes(date, movieId) {
    const response = await fetch(`/showtimes?date=${date}&movie_id=${movieId}`);
    const showtimes = await response.text();
    $("#showtimes-container").html(showtimes);
}

$(document).ready(function() {
    const date = $(".date-item.bg-blue-600").attr("date") || $(".date-item").first().attr("date");
    const movieId = $("#movie-id").val();
    fetchShowtimes(date, movieId);
});

$(document).on("click", ".date-item", function () {
    const date = $(this).attr("date");
    const movieId = $(".movie").attr("id");
    $(".date-item")
        .not(this)
        .removeClass("bg-blue-600 text-white")
        .addClass("bg-gray-200");
    if ($(this).hasClass("bg-blue-600")) {
        return;
    }
    $(this).toggleClass("bg-blue-600 text-white bg-gray-200");
    fetchShowtimes(date, movieId);
    $("#invoice-field").addClass("hidden");
});

$(document).on("click", ".showtime-btn", function () {});

async function fetchSeats(date, movieId, showtimeId, button) {
    if ($(button).hasClass("bg-blue-600")) {
        return;
    }
    $("#invoice-field").addClass("hidden");
    $(".showtime-btn").not(this).removeClass("bg-blue-600 text-white");
    $(button).toggleClass("bg-blue-600 text-white");
    const response = await fetch(
        `/seats?date=${date}&movie_id=${movieId}&showtime_id=${showtimeId}&price=${price}`
    );
    const seats = await response.text();
    $("#seats-container").html(seats);
}

$(document).on("click", ".seat", function () {
    count = document.querySelectorAll(".seat.bg-primary").length;
    if ($(this).hasClass("bg-primary")) {
        count -= 1;
    } else {
        count += 1;
    }

    const text = new Intl.NumberFormat("vi-VN").format(count * price);

    if (count > 0) {
        $("#total").text("Price: " + text + " VND");
        $("#price").text(text + " VND");
        $("#total_amount").text(text + " VND");
    } else {
        $("#total").text("Price: 0 VND");
        $("#price").text("0 VND");
        $("#total_amount").text("0 VND");
    }

    $(this).toggleClass("bg-primary text-white");
});

function applyVoucher(id, type, value) {
    let discount = 0;
    const total = price * count;

    if (type === "percent") {
        discount = (value / 100) * total;
    } else {
        discount = value;
    }
    $(".discount").attr("id", id);
    $(".discount").attr("value", discount);
    $(".discount").text(
        `-${new Intl.NumberFormat("vi-VN").format(discount)} VND`
    );
    $(".total").text(
        `${new Intl.NumberFormat("vi-VN").format(total - discount)} VND`
    );
}

async function handleTotalPrice() {
    let discount = 0;
    const movieId = $(".movie").attr("id");
    const movieName = $(".movie").text();
    const defaultPrice = count * price;
    const startTime = $(".showtime-btn.bg-blue-600").attr("start-time");
    const endTime = $(".showtime-btn.bg-blue-600").attr("end-time");
    const showtimeId = $(".showtime-btn.bg-blue-600").attr("id");
    const seatIds = Array.from(
        document.querySelectorAll(".seat.bg-primary")
    ).map((seat) => seat.id);
    const voucherId = $(".discount").attr("id");
    if (voucherId) {
        discount = $(".discount").attr("value");
    }
    const date = $(".date-item.bg-blue-600").attr("date");
    const totalPrice = defaultPrice - discount;
    const auditorium_id = $("#auditorium-id").attr("data-id")
    const data = {
        auditorium_id,
        movieId,
        movieName,
        defaultPrice,
        totalPrice,
        showtimeId,
        seatIds,
        voucherId,
        discount,
        date,
        startTime,
        endTime,
    };
    const encodedData = btoa(JSON.stringify(data));
    document.getElementById("order-data").value = encodedData;
}

function bookSeats() {
    $("#invoice-field").removeClass("hidden");
}
