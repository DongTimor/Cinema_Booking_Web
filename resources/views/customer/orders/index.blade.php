@extends('layouts.customer')

@section('content')
    <div class="flex justify-content-center">
        <div class="wrapper rounded bg-white shadow">
            <div class="h2 fw-bold text-center">Order History</div>
            <div class="accordion">
                @foreach ($orders as $item)
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed order border">
                                Booking date: {{ \Carbon\Carbon::parse($item->created_at)->format("H:i ~ d/m/Y") }}
                            </button>
                        </h2>
                        <div class="accordion-collapse hidden border">
                            <ul class="list-unstyled cursor-pointer">
                                <li><a href="#">Name Movie: {{ $item->movie }}</a></li>
                                <li><a href="#">Start time ~ End time: {{ $item->start_time }} ~
                                        {{ $item->end_time }}</a></li>
                                <li><a href="#">Auditotium: {{ $item->auditorium }}</a></li>
                                <li><a href="#">Quantity: {{ $item->quantity }}</a></li>
                                <li><a href="#">Ticket: {{ $item->ticket_ids }}</a></li>
                                <li><a href="#">Voucher: {{ number_format($item->voucher) }} VND</a></li>
                                <li><a href="#">Original price: {{ number_format($item->price) }} VND</a></li>
                                <li><a href="#">Total price: {{ number_format($item->total) }} VND</a></li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.order', function() {
            $(this).parent().next('.accordion-collapse').slideToggle(400);
        });
    </script>
@endsection
