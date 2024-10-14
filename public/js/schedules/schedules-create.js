const timeline = $('.timeline');
let allShowtimes = [];
let duplicateShowtimes = [];
let duration = 0;
const allOptions = [];
let clicked = false;

function convertTimeToDecimalHour(timeString) {
    const [hours, minutes, seconds] = timeString.split(':').map(Number);
    return hours + (minutes / 60) + (seconds / 3600);
}

function convertTimeToHourAndMinute(timeString) {
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

function addMissingOptions() {
    const select = $('#showtime');
    const existingValues = select.find('option').map(function () {
        return $(this).val();
    }).get().map(String);

    allOptions.forEach(option => {
        if (!existingValues.includes(String(option.value))) {
            select.append(`<option value="${option.value}" data-start="${option.start_time}" data-end="${option.end_time}">${option.text}</option>`);
        }
    });
}

function timelineRender(showtimeDiv, showtime, index) {
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
    }
    timeline.append(showtimeDiv);
    timeline.append(readyDiv);
}

function renderTimeline(showtimes1, showtimes2) {
    let index = 0;
    timeline.html('');
    const combinedShowtimes = [...showtimes1.map(showtime => ({ ...showtime, type: 'showtime2' })),
                               ...showtimes2.map(showtime => ({ ...showtime, type: 'showtime1' }))];
    combinedShowtimes.sort((a, b) => {
        const timeA = convertTimeToDecimalHour(a.start);
        const timeB = convertTimeToDecimalHour(b.start);
        return timeA - timeB;
    });
    combinedShowtimes.forEach(showtime => {
        index++;
        const showtimeDiv = $('<div>').addClass(showtime.type);
        timelineRender(showtimeDiv, showtime, index);
    });
}

$(document).ready(function () {
    const baseUrl = window.location.origin;
    $('#movie').on('change', function () {
        const movieId = $(this).val();
        if(movieId !== '-1') {
            $('#date').prop('placeholder', 'Select Date');
            $('#date').prop('disabled', false);
            $('#auditorium').prop('disabled', false);
            $('#auditorium').val('--Select Date First--');
            $('#showtime').prop('disabled', true);
            $('#showtime').empty();
            timeline.html('');
        async function fetchDuration() {
            try {
                const response = await fetch(baseUrl + "/admin/movies/features/getDuration/" + movieId);
                const data = await response.json();
                if (data) {
                    duration = data;
                }
            } catch (error) {
                console.error('Error fetching duration:', error);
            }
        }
        fetchDuration();
    }else{
        $('#date').prop('disabled', true);
        $('#date').val('--Select Movie First--');
        $('#auditorium').prop('disabled', true);
        $('#auditorium').val('--Select Movie First--');
        $('#showtime').prop('disabled', true);
        $('#showtime').empty();
        timeline.html('');
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
        clicked = false;
        allOptions.length = 0;
        allShowtimes.length = 0;
        duplicateShowtimes.length = 0;
        const auditoriumId = $(this).val();
        if (auditoriumId !== '-1') {
            $('#showtime').prop('disabled', false);
            async function fetchShowtimes() {
                try {
                    const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
                    const response = await fetch(baseUrl + "/admin/showtimes/getDullicateShowtimes/" + auditoriumId + "/" + formattedDate);
                    const data = await response.json();
                    if (data) {
                        data.forEach(showtime => {
                            if (showtime) {
                                allShowtimes.push({ start: showtime.start_time, end: showtime.end_time });
                            }
                        });
                        renderTimeline(allShowtimes, duplicateShowtimes);
                    } else {
                        $('#showtime').append('<option value="-1">---No showtimes available---</option>');
                    }
                } catch (error) {
                    console.error('Error fetching showtimes:', error);
                }
            }
            async function fetchAvailableShowtimes() {
                try {
                    const showtimeSelect = $('#showtime');
                    showtimeSelect.empty();
                    const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
                    const response = await fetch(baseUrl + "/admin/showtimes/getAvailableShowtimes/" + auditoriumId + "/" + formattedDate + "/" + duration);
                    const data = await response.json();
                    if (data) {
                        data.forEach(function (showtime) {
                            showtimeSelect.append('<option data-start="' + showtime.start_time + '" data-end="' + showtime.end_time + '" value="' + showtime.id + '">' +
                                showtime.start_time + ' - ' + showtime.end_time + '</option>');
                            allOptions.push({
                                value: showtime.id,
                                text: showtime.start_time + ' - ' + showtime.end_time,
                                start_time: showtime.start_time,
                                end_time: showtime.end_time
                            });
                        });
                    } else {
                        $('#showtime').prop('disabled', true);
                    }
                } catch (error) {
                    console.error('Error fetching available showtimes:', error);
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

    $('#showtime').on('change', function () {
        if (clicked) {
            addMissingOptions();
        }
        clicked = true;
        duplicateShowtimes.length = 0;
        const selectedOptions = $(this).find('option:selected');
        const selectedTimes = selectedOptions.map(function () {
            return {
                start_time: $(this).data('start'),
                end_time: $(this).data('end')
            };
        }).get();
        $(this).find('option').each(function () {
            if ($(this).is(':selected')) {
                duplicateShowtimes.push({ start: $(this).data('start'), end: $(this).data('end') });
                return;
            }
            const optionStartTime = convertTimeToDecimalHour($(this).data('start'));
            const optionEndTime = convertTimeToDecimalHour($(this).data('end'));
            const isOverlapping = selectedTimes.some(selected => {
                return (optionStartTime > convertTimeToDecimalHour(selected.start_time) && optionStartTime < convertTimeToDecimalHour(selected.end_time) + 0.25) ||
                    (optionEndTime > convertTimeToDecimalHour(selected.start_time) - 0.25 && optionEndTime < convertTimeToDecimalHour(selected.end_time)) ||
                    (optionStartTime <= convertTimeToDecimalHour(selected.start_time) && optionEndTime >= convertTimeToDecimalHour(selected.end_time));
            });
            if (isOverlapping) {
                $(this).remove();
            } else {
                $(this).prop('disabled', false);
            }
        });
        renderTimeline(allShowtimes, duplicateShowtimes);
    });
});
