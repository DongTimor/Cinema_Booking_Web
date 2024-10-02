$(document).ready(function() {

    const baseUrl = window.location.origin;
    $('#movie_id').on('change', function() {
        const movieId = $(this).val();
        const showtimeSelect = $('#showtime_id');
        showtimeSelect.empty();
        showtimeSelect.append('<option value="-1">---Select the showtime---</option>');
        if (movieId) {
            async function fetchShowtimes() {
                const response = await fetch(baseUrl + "/admin/movies/getShowtimes/" + movieId);
                const data = await response.json();
                data.forEach(function(showtime) {
                    showtimeSelect.append('<option value="' + showtime.id + '">' +
                        showtime.id + '</option>');
                });
            }
            fetchShowtimes();
        } else {
            showtimeSelect.prop('disabled', true);
        }
    })

    $('#showtime_id').on('change', function() {
        const showtimeId = $(this).val();
        const seatSelect = $('#seat_id');
        seatSelect.empty();
        seatSelect.append('<option value="-1">---Select the seat---</option>');
        if (showtimeId) {
            async function fetchSeats() {
                const response = await fetch(baseUrl + "/admin/showtimes/getSeats/" + showtimeId);
                const data = await response.json();
                console.log(data);
                data.forEach(function(seat) {
                    seatSelect.append('<option value="' + seat.id + '">' +
                        seat.seat_number + '</option>');
                });
            }
            fetchSeats();
        } else {
            seatSelect.prop('disabled', true);
        }
    })
});
