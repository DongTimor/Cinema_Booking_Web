async function getCurrentUser() {
    const response = await fetch('/admin/users/current');
    const user = await response.json();
    return user;
}

async function editTicket(ticket) {
    const user = await getCurrentUser();
    if (user.id === ticket.user_id) {
        window.location.href = `/admin/tickets/${ticket.id}`;
    } else {
        alert('You are not authorized to edit this ticket');
    }
}

async function deleteTicket(ticket) {
    const user = await getCurrentUser();
    if (user.id === ticket.user_id) {
        const url = `/admin/tickets/${ticket.id}`;
        if (confirm('Are you sure you want to delete this ticket?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    alert(result.message);
                    window.location.reload();
                },
                error: function (error) {
                    alert('An error occurred while deleting the ticket: ' + error.message);
                }
            });
        }
    } else {
        alert('You are not authorized to delete this ticket');
    }
}
