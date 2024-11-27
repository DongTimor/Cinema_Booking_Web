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
                <input type="hidden" name="order_id" id="order_id">
                <div class="form-group w-50">
                    <label for="customer">Customer</label>
                    <a class="btn btn-outline-success my-3 px-3 py-2" href="{{ route("customers.create") }}"
                        role="button"><i class="fas fa-plus"></i></a>
                    <div class="input-group">
                        <input type="text" class="form-control phone-number shadow-none"
                            placeholder="Input customer phone number" />
                        <button type="button" class="input-group-text search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="info"></div>
                </div>
                <div class="modal fade" id="voucher-modal" tabindex="-1" role="dialog"
                    aria-labelledby="voucher-modal-label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content h-100">
                            <div class="modal-header">
                                <h5 class="modal-title">Voucher List</h5>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body overflow-auto">
                                <ul id="vouchers" class="row px-5 font-mono">
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
                                <select class="form-control shadow-none" name="movie_id" id="movie" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}" data-price="{{ $movie->price }}"
                                            data-event-price="{{ isset($movie->event) ? $movie->price - ($movie->price * $movie->event->discount_percentage) / 100 : 0 }}">
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
                            <select class="form-control shadow-none" name="showtime_id" id="showtime" required>
                                <option value="">-Select Showtime-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="auditorium">Auditorium</label>
                            <select class="form-control shadow-none" name="auditorium_id" id="auditorium" required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="{{ asset("js/tickets/create.js") }}"></script>
@endsection
