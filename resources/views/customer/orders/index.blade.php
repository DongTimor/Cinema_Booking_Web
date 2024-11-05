@extends('layouts.customer')

@section('content')
    <div class="container d-flex justify-content-center align-items-center">
        <div class="wrapper bg-white rounded shadow">
            <div class="h2 text-center fw-bold">Order History</div>

            <div class="accordion accordion-flush border-top border-start border-end" id="myAccordion">
                @foreach ($orders as $item)
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed border-0 order" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Booking date: {{ \Carbon\Carbon::parse($item->created_at)->format('H:i ~ d/m/Y') }}
                            </button>
                        </h2>
                        <div class="accordion-collapse collapse border-0" aria-labelledby="flush-headingOne"
                            data-bs-parent="#myAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-unstyled m-0">
                                    <li><a href="#">Name Movie: {{ $item->movie }}</a></li>
                                    <li><a href="#">Start time ~ End time: {{ $item->start_time }} ~
                                            {{ $item->end_time }}</a></li>
                                    <li><a href="#">Auditotium: {{ $item->auditorium }}</a></li>
                                    <li><a href="#">Quantity: {{ $item->quantity }}</a></li>
                                    <li><a href="#">Ticket: {{ $item->ticket_ids }}</a></li>
                                    <li><a href="#">Voucher: {{ $item->voucher }}</a></li>
                                    <li><a href="#">Price: {{ $item->price }}</a></li>
                                </ul>
                            </div>
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
