$(document).ready(function() {
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
            $.ajax({
                url: "/admin/roles/delete/" + id,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() {
                    window.location.href = "/admin/roles";
                },
            });
    })
});

$(document).ready(function() {
    $('.delete-button').on('click', function() {
        const id = $(this).data('id');
            $.ajax({
                url: "/admin/permissions/delete/" + id,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() {
                    window.location.href = "/admin/permissions";
                },
            });
    })
});
