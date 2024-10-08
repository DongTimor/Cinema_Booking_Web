function convertTimeToDecimalHour(timeString) {
    const [hours, minutes, seconds] = timeString.split(':').map(Number);
    return hours + (minutes / 60) + (seconds / 3600);
}

function convertTimeToHourAndMinute(timeString) {
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

const timeline = $('.timeline');

$(document).ready(function () {
    const baseUrl = window.location.origin;
    $('#movie').on('change', function () {
        const movieId = $(this).val();
        if (movieId !== '-1') {
            $('#date').prop('placeholder', 'Select Date');
            $('#date').prop('disabled', false);
        } else {
            $('#date').prop('disabled', true);
            $('#date').val('--Select Movie First--');
        }
    });

    $('#datepicker').on('change.datetimepicker', function (e) {
        const date = $('#date').val();
        if (date !== '-1') {
            $('#auditorium').prop('disabled', false);
            $('#auditorium').val('--Select Date First--');
            $('#showtime').prop('disabled', true);
            $('#showtime').empty();
            timeline.html('');
        } else {
            $('#auditorium').prop('disabled', true);
            $('#showtime').prop('disabled', true);
        }
    });

    $('#auditorium').on('change', function () {
        const auditoriumId = $(this).val();
        if (auditoriumId !== '-1') {
            $('#showtime').prop('disabled', false);
            async function fetchShowtimes() {
                const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
                const response = await fetch(baseUrl + "/admin/showtimes/getDullicateShowtimes/" + auditoriumId + "/" + formattedDate);
                const data = await response.json();
                if (data) {
                    const allShowtimes = [];
                    data.forEach(showtime => {
                        if (showtime) {
                            allShowtimes.push({ start: showtime.start_time, end: showtime.end_time, movie_id: movie.id });
                        }
                    });
                    console.log(allShowtimes);
                    timeline.html('');
                    allShowtimes.forEach((showtime, index) => {
                        const showtimeDiv = $('<div>').addClass('showtime');
                        const readyDiv = $('<div>').addClass('ready');
                        showtimeDiv.css('left', `${(convertTimeToDecimalHour(showtime.start) / 24) * 100}%`);
                        showtimeDiv.css('width', `${((convertTimeToDecimalHour(showtime.end) - convertTimeToDecimalHour(showtime.start)) / 24) * 100}%`);
                        readyDiv.css('left', `${(convertTimeToDecimalHour(showtime.end) / 24) * 100}%`);
                        readyDiv.css('width', `${(0.25 / 24) * 100}%`);
                        if (index % 2 !== 0) {
                            showtimeDiv.append('<span class="start-time1">' + convertTimeToHourAndMinute(showtime.start) + '</span>');
                            showtimeDiv.append('<span class="end-time1">' + convertTimeToHourAndMinute(showtime.end) + '</span>');
                        } else {
                            showtimeDiv.append('<span class="start-time2">' + convertTimeToHourAndMinute(showtime.start) + '</span>');
                            showtimeDiv.append('<span class="end-time2">' + convertTimeToHourAndMinute(showtime.end) + '</span>');
                        };
                        timeline.append(showtimeDiv);
                        timeline.append(readyDiv);
                    });
                } else {
                    showtimeSelect.append('<option value="-1">---No showtimes available---</option>');
                }
            }
            async function fetchAvailableShowtimes() {
                const showtimeSelect = $('#showtime');
                showtimeSelect.empty();
                const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
                const response = await fetch(baseUrl + "/admin/showtimes/getAvailableShowtimes/" + auditoriumId + "/" + formattedDate);
                const data = await response.json();
                if (data) {
                    data.forEach(function (showtime) {
                        showtimeSelect.append('<option value="' + showtime.id + '">' +
                            showtime.start_time + ' - ' + showtime.end_time + '</option>');
                    });
                } else {
                    $('#showtime').prop('disabled', true);
                }
            }
            fetchShowtimes();
            fetchAvailableShowtimes();
        } else {
            $('#showtime').prop('disabled', true);
            $('#showtime').empty();
            timeline.html('');
        }
    });
});
