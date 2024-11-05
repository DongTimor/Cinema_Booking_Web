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

async function create() {
    const auditoriumId = $('#auditorium_id').val();
    const url = '/admin/seats/create';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (auditoriumId !== "") {
        const data = {
            auditorium_id: auditoriumId,
            seat_number: $('#seat_number').val()
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
                Swal.fire({
                    title: "Success!",
                    text: data.message,
                    icon: "success",
                })
                location.reload();
            })
            .catch(error => {
                Swal.fire({
                    title: "Error!",
                    text: error.error,
                    icon: "error",
                })
            });
    }
}

function autoCreate() {
    Swal.fire({
        title: "Go to automatically create form?",
        text: "You won't be able to revert this!",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const url = `/admin/seats/create`;
            window.location.href = url;
        }
    });
}

$(document).ready(async function () {
    $('#auditorium_id').change(async function () {
        const value = $(this).val();
        if (value != "") {
            const totalSeats = await getTotalSeats(value);
            const totalAvailableSeats = await getTotalAvailableSeats(value);

            $('#total_available_seats').val(totalAvailableSeats + "/" + totalSeats);
        }
    });
});
