@extends("layouts.admin")
@section("styles")
    <link rel="stylesheet" href="{{ asset("css/tickets/create.css") }}">
@endsection
@section("content")
    <div class="card my-3">
        <div class="card-header">
            <h4>Create Ticket</h4>
        </div>
        <div class="card-body">
            <form class="ticket-form" action="{{ route("tickets.store") }}" method="POST">
                @csrf
                <div class="form-group w-50">
                    <label for="customer">Customer</label>
                    <a class="btn btn-outline-success my-3 px-3 py-2" href="{{ route("movies.features.create") }}"
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
                                <ul class="row px-5 font-mono">
                                    @foreach ($vouchers as $voucher)
                                        <div class="voucher-card my-2">
                                            <div class="voucher-title">{{ $voucher->description }}</div>
                                            <div class="text-uppercase h3 text-white">{{ $voucher->code }}</div>
                                            <div class="voucher-details flex flex-wrap gap-3">
                                                <span class="voucher-badge">Quantity: {{ $voucher->quantity }}</span>
                                                <span class="voucher-badge badge-discount">Discount:
                                                    {{ $voucher->type == "percent" ? $voucher->value . "%" : number_format($voucher->value) . "VND" }}</span>
                                                <span class="voucher-badge">Expiry:
                                                    {{ \Carbon\Carbon::parse($voucher->expires_at)->format("d/m/Y") }}</span>
                                                <input type="hidden" name="voucher_id" class="value"
                                                    value="{{ $voucher->id }}" data-value="{{ $voucher->value }}"
                                                    data-type="{{ $voucher->type }}">
                                            </div>
                                            <button type="button"
                                                class="btn save-btn text-uppercase rounded-md border-0 shadow-sm"
                                                data-bs-dismiss="modal">Use</button>
                                        </div>
                                    @endforeach
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
                                    data-date="{{ \Carbon\Carbon::today()->format("Y-m-d") }}" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}" data-price="{{ $movie->price }}">
                                            {{ $movie->name }}</option>
                                    @endforeach
                                </select>
                                <a class="btn btn-outline-success px-3 py-2" href="{{ route("movies.features.create") }}"
                                    role="button"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="showtime">Showtime</label>
                            <select class="form-control shadow-none" name="showtime_id" id="showtime"
                                data-date="{{ \Carbon\Carbon::today()->format("Y-m-d") }}"
                                data-movie-id="{{ $movie->id }}" required>
                                <option value="">-Select Showtime-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="auditorium">Auditorium</label>
                            <select class="form-control shadow-none" name="auditorium_id" id="auditorium"
                                data-date="{{ \Carbon\Carbon::today()->format("Y-m-d") }}" required>
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
@section("scripts")
    <script>
        let count = 0;
        $(document).on('click', '.seat', function() {
            const seat = $(this);
            const price = $('#movie option:selected').data('price');

            if (seat.hasClass('bg-secondary-subtle')) {
                return;
            }

            if (seat.hasClass('bg-primary')) {
                seat.find('input[name="seats[]"]').remove();
                count -= 1;
            } else {
                seat.append(`<input type="hidden" name="seats[]" value="${seat.data('id')}">`);
                count += 1;
            }

            if (count == 0) {
                $('.discount').text('');
            }

            seat.toggleClass('bg-primary');
            getDiscount();
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

        $('.search-btn').click(async function() {
            const phone = $(this).prev('input').val();
            const customer = await fetchCustomer(phone);
            if (customer) {
                $('.info').html(customer);
                $('#voucher-btn').removeClass('d-none');
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

        $('.save-btn').click(function() {
            $(document).find('.save-btn').not(this).removeClass('bg-secondary-subtle');
            $(this).toggleClass('bg-secondary-subtle');
            getDiscount($(this));
        });

        function getDiscount(button = $(this)) {
            let discount = 0;
            const value = button.prev().find('.value');
            const price = $('#movie option:selected').data('price');
            const type = value.data('type');
            const total = price * count;

            if (type === 'percent') {
                discount = value.data('value') / 100 * total;
            } else {
                discount = parseFloat(value.data('value')) || 0;
            }

            const isDiscounted = button.hasClass('bg-secondary-subtle');

            $('.price').text(`Price: ${new Intl.NumberFormat('vi-VN').format(total)} VND`);
            $('.discount').text(isDiscounted ? `- ${new Intl.NumberFormat('vi-VN').format(discount)} VND` : '');
            $('.total').text(
                `Total: ${new Intl.NumberFormat('vi-VN').format(isDiscounted ? total - discount : total)} VND`);
            $('.total-price').val(isDiscounted ? total - discount : total);
            $('.voucher-id').val(isDiscounted ? value.val() : '');
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="{{ asset("js/tickets/create.js") }}"></script>
@endsection
