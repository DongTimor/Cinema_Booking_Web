@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tickets/create.css') }}">
@endsection
@section('content')
    <div class="card my-3">
        <div class="card-header">
            <h4>Create Ticket</h4>
        </div>
        <div class="card-body">
            <form class="ticket-form" action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="form-group w-50">
                    <label for="customer">Customer</label>
                    <a class="btn btn-outline-success my-3 px-3 py-2" href="{{ route('customers.create') }}"
                        role="button"><i class="fas fa-plus"></i></a>
                    <div class="input-group">
                        <input type="text" class="form-control phone-number shadow-none"
                            placeholder="Input customer phone number" />
                        <button type="button" class="input-group-text search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="info"></div>
                </div>
                <div class="modal fade" id="voucher-modal" tabindex="-1" role="dialog"
                    aria-labelledby="Show-Voucher-ModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content h-100">
                            <div class="modal-header">
                                <h5 class="modal-title">Voucher List</h5>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body overflow-auto">
                                <ul id="voucher-list" class="row px-5 font-mono">
                                </ul>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="movie">Movie</label>
                            <div class="flex items-center gap-2">
                                <select class="form-control shadow-none" name="movie_id" id="movie"
                                    data-date="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}" data-price="{{ $movie->price }}">
                                            {{ $movie->name }}</option>
                                    @endforeach
                                </select>
                                <a class="btn btn-outline-success px-3 py-2" href="{{ route('movies.features.create') }}"
                                    role="button"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="showtime">Showtime</label>
                            <select class="form-control shadow-none" name="showtime_id" id="showtime"
                                data-date="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                data-movie-id="{{ $movie->id }}" required>
                                <option value="">-Select Showtime-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="auditorium">Auditorium</label>
                            <select class="form-control shadow-none" name="auditorium_id" id="auditorium"
                                data-date="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                                <option value="">-Select Auditorium-</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="seats" class="seats-container my-3">
                </div>
                <div class="flex">
                    <button type="submit" class="btn btn-outline-primary ml-auto">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let count = 0;
        $(document).on('click', '.seat', async function() {
            const seat = $(this);


            if (seat.hasClass('bg-secondary-subtle')) {
                return;
            }

            if (seat.hasClass('bg-primary')) {
                seat.find('input[name="seats[]"]').remove();
                count -= 1;
                await selectedSeats.splice(selectedSeats.indexOf(seat.data('id')), 1);
            } else {
                seat.append(`<input type="hidden" name="seats[]" value="${seat.data('id')}">`);
                count += 1;
                await selectedSeats.push(seat.data('id'));
            }
            console.log(selectedSeats);
            if (count == 0) {
                $('.discount').text('');
                $('#price').text('0 VND');
                $('#quantity').text(`Quantity: ${count}`);
                $('#total').text('Total: 0 VND');
            }

            seat.toggleClass('bg-primary');
            priceCalculation();
        });

        async function fetchCustomer(phone) {
            const response = await fetch(`/admin/tickets/search/${phone}`);

            if (!response.ok) {
                const {
                    message
                } = await response.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
                $('.info').html('');
                $('#voucher-btn').addClass('d-none');
                return;
            }
            const customer = await response.text();
            return customer;
        }

        async function fetchVoucherList(customer_id) {
            const response = await fetch(`/admin/tickets/voucher-list/${customer_id}`);
            const voucherList = await response.text();
            return voucherList;
        }

        $('.search-btn').click(async function() {
            const phone = $(this).prev('input').val();
            const customer = await fetchCustomer(phone);
            if (customer) {
                $('.info').html(customer);
                $('#voucher-btn').removeClass('d-none');
                const voucherList = await fetchVoucherList($('#customer_id').text());
                $('#voucher-list').html(voucherList);
            } else {
                $('#voucher-list').html('');
                voucherId = null;
                voucherValue = null;
                voucherType = null;
                priceCalculation();
            }
        })

        $('.phone-number').on("keydown", async function(event) {
            if (event.key === "Enter") {
                const customer = await fetchCustomer($(this).val());
                if (customer) {
                    $('.info').html(customer);
                    $('#voucher-btn').removeClass('d-none');
                }
            }
        })

        function getDiscount(button = $(this)) {
            console.log(button, 'button', price, 'price', count, 'count', events, 'events', eventDiscount);
            let discount = 0;
            const value = button.prev().find('.value');
            const moviePrice = $('#movie option:selected').data('price');
            const type = value.data('type');
            const total = moviePrice * count;

            if (type === 'percent') {
                discount = value.data('value') / 100 * total;
            } else {
                discount = parseFloat(value.data('value')) || 0;
            }

            const isDiscounted = button.hasClass('bg-secondary-subtle');

            $('#price').text(`${new Intl.NumberFormat('vi-VN').format(total)} VND`);
            if (count > 0 && voucherDiscount > 0) {
                $('#voucher-discount').text(`- ${new Intl.NumberFormat('vi-VN').format(voucherDiscount*count)} VND`);
            } else {
                $('#voucher-discount').text('');
            }

            if (count > 0 && eventDiscount > 0) {
                $('#event-discount').text(`- ${new Intl.NumberFormat('vi-VN').format(eventDiscount*count)} VND`);
            } else {
                $('#event-discount').text('');
            }

            $('.total').text(
                `Total: ${new Intl.NumberFormat('vi-VN').format(price*count)} VND`);
            $('.total-price').val(price);
            $('#quantity').text(`Quantity: ${count}`);
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="{{ asset('js/tickets/create.js') }}"></script>
@endsection
