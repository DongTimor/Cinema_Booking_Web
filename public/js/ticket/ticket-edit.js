$(document).ready(function() {

    const baseUrl = window.location.origin;
    $('#movie_id').on('change', function() {
        const movieId = $(this).val();
        const showtimeSelect = $('#showtime_id');
        showtimeSelect.empty();
        showtimeSelect.append('<option value="-1">---Select the showtime---</option>');
        if (movieId) {
            async function fetchShowtimes() {
                const response = await fetch(baseUrl + "/movies/getShowtimes/" + movieId);
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
});
