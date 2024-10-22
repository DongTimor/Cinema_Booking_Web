const A = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
const B = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
let Result = [];


function isPrime(num) {
    if (num <= 1) return false;
    if (num <= 3) return true;
    if (num % 2 === 0 || num % 3 === 0) return false;
    for (let i = 5; i * i <= num; i += 6) {
        if (num % i === 0 || num % (i + 2) === 0) return false;
    }
    return true;
}

function findMiddleDivisor(num) {
    const divisors = [];
    for (let i = 1; i <= Math.sqrt(num); i++) {
        if (num % i === 0) {
            divisors.push(i);
            if (i !== num / i) {
                divisors.push(num / i);
            }
        }
    }
    divisors.sort((a, b) => a - b);
    const middleIndex = Math.floor((divisors.length - 1) / 2);
    return divisors[middleIndex];
}

function findNearestDivisorBelowPrime(num) {
    if (!isPrime(num)) return null;

    for (let i = num - 1; i > 1; i--) {
        if (num % i !== 0 && hasDivisors(i)) {
            return i;
        }
    }
    return null;
}

function hasDivisors(num) {
    for (let i = 2; i <= Math.sqrt(num); i++) {
        if (num % i === 0) {
            return true;
        }
    }
    return false;
}

function findComplementaryFactor(num, inputFactor) {
    const complementaryFactor = Math.floor(num / inputFactor);
    return complementaryFactor;
}

function generateRegex(number, type, regex = "") {
    let Regex = [];
    for (let i = 0; i < number; i++) {
        if (i < A.length) {
            if (type == "A") {
                Regex.push(A[i]);
            } else if (type == "B") {
                Regex.push(B[i]);
            } else if (type == "C") {
                Regex.push(i);
            } else if (type == "D") {
                Regex.push(regex[i]);
            }
        } else {
            let y = i % A.length;
            if (type == "A") {
                Regex.push(A[y] + Math.floor(i / A.length));
            } else if (type == "B") {
                Regex.push(B[y] + Math.floor(i / A.length));
            } else if (type == "C") {
                Regex.push(i);
            } else if (type == "D") {
                Regex.push(regex[i]);
            }
        }
    }
    return Regex;
}

function convertRegex(regex) {
    const result = regex.split(/,/);
    return result;
}

function combineRegex(row_regex = [], col_regex = []) {
    let combinedRegex = [];
    for (let i = 0; i < row_regex.length; i++) {
        for (let j = 0; j < col_regex.length; j++) {
            combinedRegex.push({regex: row_regex[i] + '-' + col_regex[j], row: i, column: j});
        }
    }
    return combinedRegex;
}

function demoResult() {
    let combinedRegex = [];
    if ($('#Row_Regex_Select').val() == "D" && $('#Column_Regex_Select').val() == "D") {
        if (convertRegex($('#Row_Regex').val()).length == $('#Row_number').val() && convertRegex($('#Column_Regex').val()).length == $('#Column_number').val()) {
            combinedRegex = combineRegex(generateRegex($('#Row_number').val(), $('#Row_Regex_Select').val(), convertRegex($('#Row_Regex').val())), generateRegex($('#Column_number').val(), $('#Column_Regex_Select').val(), convertRegex($('#Column_Regex').val())));
            $('#Demo').val(combinedRegex.map(item => item.regex).join(', '));
        } else {
            combinedRegex = [];
            $('#Demo').val("Please enter valid regex number of rows and columns");
        }
    } else if ($('#Row_Regex_Select').val() == "D") {
        if (convertRegex($('#Row_Regex').val()).length == $('#Row_number').val()) {
            combinedRegex = combineRegex(generateRegex($('#Row_number').val(), $('#Row_Regex_Select').val(), convertRegex($('#Row_Regex').val())), generateRegex($('#Column_number').val(), $('#Column_Regex_Select').val()));
            $('#Demo').val(combinedRegex.map(item => item.regex).join(', '));
        } else {
            $('#Demo').val("Please enter valid regex number of rows");
            combinedRegex = [];
        }
    } else if ($('#Column_Regex_Select').val() == "D") {
        if (convertRegex($('#Column_Regex').val()).length == $('#Column_number').val()) {
            combinedRegex = combineRegex(generateRegex($('#Row_number').val(), $('#Row_Regex_Select').val()), generateRegex($('#Column_number').val(), $('#Column_Regex_Select').val(), convertRegex($('#Column_Regex').val())));
            $('#Demo').val(combinedRegex.map(item => item.regex).join(', '));
        } else {
            $('#Demo').val("Please enter valid regex number of columns");
            combinedRegex = [];
        }
    } else {
        combinedRegex = combineRegex(generateRegex($('#Row_number').val(), $('#Row_Regex_Select').val()), generateRegex($('#Column_number').val(), $('#Column_Regex_Select').val()));
        $('#Demo').val(combinedRegex.map(item => item.regex).join(', '));
    }
    Result = combinedRegex;
}

function updateModal(successArray, errorArray) {
    // Update the success and error counts
    $('#successCount').text(`Success: ${successArray.length} seats`);
    $('#errorCount').text(`Error: ${errorArray.length} seats`);

    // Clear existing list items
    $('#successList').empty();
    $('#errorList').empty();

    // Populate the success list
    $.each(successArray, function (index, item) {
        $('#successList').append(`<li class="text-success">${item}</li>`);
    });

    // Populate the error list
    $.each(errorArray, function (index, item) {
        $('#errorList').append(`<li class="text-danger">${item}</li>`);
    });

    // Show the modal
    $('#customWeekModal').modal('show');
}

function closeModal() {
    $('#customWeekModal').modal('hide');
}

async function create() {
    let success_message = [];
    let error_message = [];
    const auditoriumId = $('#auditorium_id').val();
    const url = '/admin/seats/create';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (auditoriumId !== "") {
        if (Result.length > 0) {
        $('#loader').css('display', 'flex');
            for (const element of Result) {
                console.log(element.regex, element.row, element.column);
                const data = {
                    auditorium_id: auditoriumId,
                    seat_number: element.regex,
                    row: element.row,
                    column: element.column
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
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        success_message.push(`${element.regex}: ${data.message}`);
                    })
                    .catch(error => {
                        error_message.push(`${element.regex}: ${error.message}`);
                    });
            }
            $('#loader').css('display', 'none');
            updateModal(success_message, error_message);
        } else {
            return alert("Please enter valid regex");
        }
    } else {
        return alert("Please select auditorium");
    }
}

function singleCreate() {
    if(confirm("Are you sure you want to create a single seat?")) {
        const url = '/admin/seats/single-create';
        window.location.href = url;
    }
}

async function getTotalSeats(auditoriumId) {
    const response = await fetch(`/admin/auditoriums/getTotalSeats/${auditoriumId}`);
    const data = await response.json();
    return data;
}

async function getTotalAvailableSeats(auditoriumId) {
    const response = await fetch(`/admin/auditoriums/getTotalAvailableSeats/${auditoriumId}`);
    const data = await response.json();
    return data;
}

$(document).ready(async function () {
    $('#auditorium_id').change(async function () {
        const value = $(this).val();
        if (value != "") {
            const totalSeats = await getTotalSeats(value);
            const totalAvailableSeats = await getTotalAvailableSeats(value);
            const availableSeats = totalSeats - totalAvailableSeats;
            $('#Total_seats').val(totalAvailableSeats + "/" + totalSeats);
            if (isPrime(availableSeats)) {
                const row = findMiddleDivisor(findNearestDivisorBelowPrime(availableSeats));
                const col = (findNearestDivisorBelowPrime(availableSeats)) / row;
                $('#Row_number').val(row);
                $('#Column_number').val(col);
                $('#Available_seats').val(findNearestDivisorBelowPrime(availableSeats) + "/" + availableSeats);
            } else {
                const row = findMiddleDivisor(availableSeats);
                const col = availableSeats / row;
                $('#Row_number').val(row);
                $('#Column_number').val(col);
                $('#Available_seats').val(availableSeats + "/" + availableSeats);
            }

            demoResult();
        } else {
            $('#Total_seats').val("0/0  ");
            $('#Row_number').val("");
            $('#Column_number').val("");
            $('#Available_seats').val("0/0");
        }
    });

    $('#Row_number').change(function () {
        const value = $(this).val();
        if (value > 0) {
            const totalSeats = $('#Total_seats').val();
            const complementaryFactor = findComplementaryFactor(totalSeats, value);
            $('#Column_number').val(complementaryFactor);
            $('#Available_seats').val(value * complementaryFactor + "/" + totalSeats);
        }
        demoResult();
    });

    $('#Column_number').change(function () {
        const value = $(this).val();
        if (value > 0) {
            const totalSeats = $('#Total_seats').val();
            const complementaryFactor = findComplementaryFactor(totalSeats, value);
            $('#Row_number').val(complementaryFactor);
            $('#Available_seats').val(value * complementaryFactor + "/" + totalSeats);
        }
        demoResult();
    });

    $('#Row_Regex_Select').change(function () {
        const value = $(this).val();
        if (value == "D") {
            $('#Row_Regex').prop('disabled', false);
            $('#Row_Regex').val("");
        } else {
            $('#Row_Regex').prop('disabled', true);
            $('#Row_Regex').val("Row_Regex");
        }
        demoResult();
    });

    $('#Column_Regex_Select').change(function () {
        const value = $(this).val();
        if (value == "D") {
            $('#Column_Regex').prop('disabled', false);
            $('#Column_Regex').val("");
        } else {
            $('#Column_Regex').prop('disabled', true);
            $('#Column_Regex').val("Column_Regex");
        }
        demoResult();
    });

    $('#Row_Regex').on('change input', function () {
        demoResult();
    });

    $('#Column_Regex').on('change input', function () {
        demoResult();
    });
});

