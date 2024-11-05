@extends("adminlte::page")
@vite(["resources/sass/app.scss", "resources/css/app.css"])
@section("css")
    <link rel="stylesheet" href="{{ asset("/css/style.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <style>
        .content {
            overflow-y: auto;
            height: 95vh;
        }

        html, body {
            height: auto;
            overflow: hidden;
        }
    </style>
    @yield("styles")
@endsection

@section("title", "Cinema Booking Admin Panel")

@section("content_header")

@stop

@section("js")
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    @yield("scripts")
    @include('layouts.alert')
@endsection

@section("content")

@stop
