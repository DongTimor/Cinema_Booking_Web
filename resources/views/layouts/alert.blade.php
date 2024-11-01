<script>
    @if (session('error'))
        Swal.fire({
            title: "Error!",
            text: '{{ session('error') }}',
            icon: "error",
        });
    @endif
    @if (session('success'))
        Swal.fire({
            title: "Success!",
            text: '{{ session('success') }}',
            icon: "success",
        });
    @endif
    @if (session('warning'))
        Swal.fire({
            title: "Warning!",
            text: '{{ session('warning') }}',
            icon: "warning",
        })
    @endif
</script>
