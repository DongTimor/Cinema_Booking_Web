
const baseUrl = window.location.origin;
let auditoriumId = $('#auditorium').val();
const duration = $('#movie').data('duration');
const dullicateShowtimes = [];
const anotherDullicateShowtimes = [];
const allOptions = [];
const timeline = $('.timeline');

showtimes.forEach(showtime => {
    dullicateShowtimes.push({ start_time: showtime.start_time, end_time: showtime.end_time, id: showtime.id })
});
function paramsBuilder(action, params) {
    const queryString = new URLSearchParams(params).toString();
    return queryString + '&action=' + action;
}

function renderTimeline(showtimes1, showtimes2) {
    let index = 0;
    timeline.html('');
    const combinedShowtimes = [...showtimes1.map(showtime => ({ ...showtime, type: 'showtime1' })),
                               ...showtimes2.map(showtime => ({ ...showtime, type: 'showtime2' }))];
    combinedShowtimes.sort((a, b) => {
        const timeA = convertTimeToDecimalHour(a.start_time);
        const timeB = convertTimeToDecimalHour(b.start_time);
        return timeA - timeB;
    });
    combinedShowtimes.forEach(showtime => {
        index++;
        console.log(index, showtime.type);
        console.log('showtimes1:', showtimes1);
        console.log('showtimes2:', showtimes2);
        const showtimeDiv = $('<div>').addClass(showtime.type);
        timelineRender(showtimeDiv, showtime, index);
    });
}

function timelineRender(showtimeDiv, showtime, index) {
    const readyDiv = $('<div>').addClass('ready');
    showtimeDiv.css('left', `${(convertTimeToDecimalHour(showtime.start_time) / 24) * 100}%`);
    showtimeDiv.css('width', `${((convertTimeToDecimalHour(showtime.end_time) - convertTimeToDecimalHour(showtime.start_time)) / 24) * 100}%`);
    readyDiv.css('left', `${(convertTimeToDecimalHour(showtime.end_time) / 24) * 100}%`);
    readyDiv.css('width', `${(0.25 / 24) * 100}%`);
    if (index % 2 !== 0) {
        showtimeDiv.append('<span class="start-time1">' + convertTimeToHourAndMinute(showtime.start_time) + '</span>');
        showtimeDiv.append('<span class="end-time1">' + convertTimeToHourAndMinute(showtime.end_time) + '</span>');
    } else {
        showtimeDiv.append('<span class="start-time2">' + convertTimeToHourAndMinute(showtime.start_time) + '</span>');
        showtimeDiv.append('<span class="end-time2">' + convertTimeToHourAndMinute(showtime.end_time) + '</span>');
    };
    timeline.append(showtimeDiv);
    timeline.append(readyDiv);
}

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

function fillShowtimeSelect(showtimeSelect, showtime) {
    showtimeSelect.push({
        value: showtime.id,
        text: showtime.start_time + ' - ' + showtime.end_time,
        start_time: showtime.start_time,
        end_time: showtime.end_time
    });
}

function handleShowtimeChange() {
    const selectedOptions = $('#showtime').find('option:selected');
    const selectedTimes = selectedOptions.map(function () {
        return {
            start_time: $(this).data('start'),
            end_time: $(this).data('end')
        };
    }).get();
    $('#showtime').find('option').each(function () {
        if ($(this).is(':selected')) {
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
            console.log("selected", $(this).data('start'), $(this).data('end'));

        }
    });
}

async function fetchAvailableShowtimes() {
    console.log("fetchAvailableShowtimes");
    const showtimeSelect = $('#showtime');
    showtimeSelect.empty();
    const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
    const params = {
        schedule: scheduleId,
        auditorium: auditoriumId,
        date: formattedDate,
        duration: duration
    };
    const queryString = paramsBuilder('for-available', params);
    const response = await fetch(baseUrl + "/admin/showtimes/get-showtimes?" + queryString);

    const data = await response.json();
    if (data) {
        await data.forEach(function (showtime) {
            console.log("showtime----", showtime.id);
            const isSelected = dullicateShowtimes.some(existingShowtime => existingShowtime.id === showtime.id);
            console.log("isSelected", isSelected, showtime.id);
            showtimeSelect.append('<option data-start="' + showtime.start_time + '" data-end="' + showtime.end_time + '" value="' + showtime.id + '"' +
                (isSelected ? ' selected' : '') + '>' +
                showtime.start_time + ' - ' + showtime.end_time + '</option>');
            fillShowtimeSelect(allOptions, showtime);
        });
        handleShowtimeChange();
    } else {
        $('#showtime').prop('disabled', true);
    }
}

async function fetchShowtimes() {
    const formattedDate = moment($('#date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
    auditoriumId = $('#auditorium').val();
    const params = {
        auditorium: auditoriumId,
        date: formattedDate
    };
    const queryString = paramsBuilder('for-duplicate', params);
    const response = await fetch(baseUrl + "/admin/showtimes/get-showtimes?" + queryString);
    const data = await response.json();
    if (data) {
        await data.forEach(showtime => {
            if (showtime && !showtimes.some(original =>
                original.id === showtime.id)) {
                anotherDullicateShowtimes.push({ start_time: showtime.start_time, end_time: showtime.end_time });
            }
        });
        renderTimeline(dullicateShowtimes, anotherDullicateShowtimes);
    } else {

    }
}
fetchAvailableShowtimes();
fetchShowtimes();

$(document).ready(function () {
    $('#showtime').on('change', function () {
        addMissingOptions();
        dullicateShowtimes.length = 0;
        const selectedOptions = $(this).find('option:selected');
        const selectedTimes = selectedOptions.map(function () {
            return {
                start_time: $(this).data('start'),
                end_time: $(this).data('end')
            };
        }).get();

        $(this).find('option').each(function () {
            if ($('#showtime').find('option:selected').length > 0) {
                if ($(this).is(':selected')) {
                    dullicateShowtimes.push({ id: $(this).val(), start_time: $(this).data('start'), end_time: $(this).data('end') });
                    return;
                }
            } else {
                dullicateShowtimes.length = 0;
            }
            const optionStartTime = convertTimeToDecimalHour($(this).data('start'));
            const optionEndTime = convertTimeToDecimalHour($(this).data('end'));
            const isOverlapping = selectedTimes.some(selected => {
                return (optionStartTime > convertTimeToDecimalHour(selected.start_time) && optionStartTime < (convertTimeToDecimalHour(selected.end_time) + 0.25)) ||
                    (optionEndTime > (convertTimeToDecimalHour(selected.start_time) - 0.25) && optionEndTime < convertTimeToDecimalHour(selected.end_time)) ||
                    (optionStartTime <= convertTimeToDecimalHour(selected.start_time) && optionEndTime >= (convertTimeToDecimalHour(selected.end_time)));
            });

            if (isOverlapping) {
                $(this).remove();
            } else {
                $(this).prop('disabled', false);
            }
        });
        renderTimeline(dullicateShowtimes, anotherDullicateShowtimes);
    });
});
