async function getCurrentUser() {
    const response = await fetch('/admin/users/current');
    const user = await response.json();
    return user;
}

async function editTicket(ticket) {
    const user = await getCurrentUser();
    console.log(ticket.user_id, user.id);
    if (ticket.user_id !== null && user.id === ticket.user_id) {
        window.location.href = `/admin/tickets/${ticket.id}`;
    } else {
        Swal.fire({
            title: "You are not authorized to edit this ticket",
            icon: "warning",
        })
    }
}

async function deleteTicket(ticket) {
    const user = await getCurrentUser();
    if (user.id === ticket.user_id) {
        const url = `/admin/tickets/${ticket.id}`;
        Swal.fire({
            title: "Are you sure you want to delete this ticket?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        Swal.fire({
                            title: "Success!",
                            text: 'Ticket deleted successfully.',
                            icon: "success",
                        })
                        window.location.reload();
                    },
                    error: function (error) {
                        Swal.fire({
                            title: "Error!",
                            text: 'An error occurred while deleting the ticket: ' + error.message,
                            icon: "error",
                        })
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: "You are not authorized to delete this ticket",
            icon: "warning",
        })
    }
}
