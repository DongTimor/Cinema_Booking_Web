<script>
    const sessionAlerts = {
        error: '{{ session("error") }}',
        success: '{{ session("success") }}',
        warning: '{{ session("warning") }}',
    };

    Object.entries(sessionAlerts).forEach(([type, message]) => {
        if (message) {
            Swal.fire({
                title: type.charAt(0).toUpperCase() + type.slice(1),
                text: message,
                icon: type,
            });
        }
    });
</script>
