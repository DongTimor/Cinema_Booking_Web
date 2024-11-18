@extends("layouts.customer")
@section("content")
    <div class="flex flex-col">
        <div class="flex h-[500px] w-full items-center justify-center bg-black">
            <img class="h-full w-[900px]"
                src="{{ $movie->images->where("type", "banner")->first() ? asset($movie->images->where("type", "banner")->first()->url) : asset("default-image.jpg") }}" />
        </div>
        <div class="z-10 -mt-10 ml-72 flex w-2/4 flex-col gap-4">
            <div class="flex gap-4">
                <img class="border-3 h-[400px] w-[280px] rounded-md border-white"
                    src="{{ $movie->images->where("type", "poster")->first() ? asset($movie->images->where("type", "poster")->first()->url) : asset("default-image.jpg") }}" />
                <div class="flex flex-col justify-end gap-3">
                    <h1 class="movie text-3xl font-extrabold" id="{{ $movie->id }}" price="{{ $movie->price }}">
                        {{ $movie->name }}</h1>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-xl text-[#f8c92c]"></i>
                            <p>{{ $movie->duration }} minutes</p>
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <i class="far fa-calendar text-xl text-[#f8c92c]"></i>
                            <p>{{ $movie->start_date }}</p>
                        </div>
                    </div>
                    <p>Category:
                        @foreach ($movie->categories as $category)
                            {{ $category->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                    <p>Director : Updating...</p>
                    <p>Characters : Updating...</p>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="h-[25px] w-[5px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Description</h1>
                </div>
                <p class="text-base">{{ $movie->description }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="h-[25px] w-[5px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Schedule</h1>
                </div>
                <div class="flex overflow-x-auto" id="date-selector">
                    @foreach ($dates as $date)
                        <div class="date-item {{ $date->isToday() ? "bg-blue-600 text-white" : "bg-gray-200" }} mx-2 flex-shrink-0 cursor-pointer rounded px-3 py-2"
                            date="{{ $date->toDateString() }}">
                            <p class="text-center font-bold">{{ $date->format("l") }}</p>
                            <p class="text-center">{{ $date->format("d/m") }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="movie-id" id="movie-id" value="{{ $movie->id }}">
            <div class="mt-1">
                <h2 class="text-lg font-bold">Mirabo Đà Nẵng</h2>
                <div class="mt-2">
                    <p>2D Phụ Đề</p>
                    <div class="mt-2 grid grid-cols-6 gap-4" id="showtimes-container">
                        @foreach ($showtimes as $showtime)
                            <button type="button"
                                onclick="fetchSeats('{{ today()->toDateString() }}',{{ $movie->id }},{{ $showtime->id }}, this)"
                                class="showtime-btn {{ $showtime->tickets->count() == $orderedCount ? "bg-gray-300 pe-none" : "" }} rounded border border-gray-300 px-2 py-2 hover:bg-gray-300"
                                id="{{ $showtime->id }}" start-time="{{ $showtime->start_time }}"
                                end-time="{{ $showtime->end_time }}" data-bs-toggle="modal" data-bs-target="#seats-modal">
                                {{ \Carbon\Carbon::parse($showtime->start_time)->format("H:i") }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="seats-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Seats</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="seats-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden" id="invoice-field">
            <div
                class="invoice-container relative ml-72 flex w-[800px] flex-col items-center justify-center rounded-lg p-6 shadow-md">
                <h2 class="mb-4 text-2xl font-semibold">Invoice</h2>
                <div class="mb-2">
                    <p class="text-lg font-medium" id="price" data-price="">Price: <span class="text-gray-700">0
                            VND</span></p>
                    <div>
                        <div class="absolute right-2 top-20">
                            <button type="button" class="rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                                data-bs-toggle="modal" data-bs-target="#voucher-modal">
                                Select Voucher
                            </button>
                        </div>
                    </div>
                </div>
                <div class="discount text-lg font-medium text-red-500">
                </div>
                <div class="mb-6">
                    <p class="border-t-2 pt-3 text-lg font-medium">Total : <span id="total_amount"
                            class="total text-green-600">0
                            VND</span></p>
                </div>
                <form action="{{ route("momo-payment") }}" method="POST" class="text-center"
                    onsubmit="handleTotalPrice()">
                    @csrf
                    <input type="hidden" name="order_data" id="order-data">
                    <button type="submit" name="payUrl"
                        class="w-full rounded-md bg-pink-500 px-14 py-2 font-extrabold text-white hover:bg-pink-600">
                        Checkout with MOMO
                    </button>
                </form>
            </div>
        </div>
        <div class="modal fade" id="voucher-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Voucher</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="justify-centerl flex w-full items-center">
                            <div
                                class="mx-16 grid max-h-[400px] w-full grid-cols-2 items-center justify-between gap-4 overflow-y-auto">
                                @if ($vouchers->isNotEmpty())
                                    @foreach ($vouchers as $voucher)
                                        @if ($voucher->pivot->status == 0)
                                            <div id="voucher_code" class="cursor-pointer"
                                                onclick="applyVoucher({{ $voucher->id }}, '{{ $voucher->type }}', {{ $voucher->value }})"
                                                data-bs-dismiss="modal">
                                                <input type="hidden" name="voucher_id" value="{{ $voucher }}">
                                                <div
                                                    class="ticket-style relative my-4 max-w-sm overflow-hidden rounded bg-gradient-to-r from-[#FF8160] to-[#FEB179] shadow-sm">
                                                    <div class="px-6 py-4">
                                                        <div class="mb-2 font-mono text-2xl font-bold"
                                                            style="color: {{ $voucher->value >= 50 ? "red" : "green" }}">
                                                            {{ $voucher->description }}
                                                        </div>
                                                        <p class="ml-2 text-lg font-bold uppercase text-gray-100">
                                                            {{ $voucher->code }}
                                                        </p>
                                                    </div>
                                                    <div class="px-6 pb-2 pt-1">
                                                        <span
                                                            class="value mb-2 mr-2 inline-block rounded-full bg-white px-3 py-1 text-sm font-semibold text-gray-700"
                                                            style="color: {{ $voucher->value >= 50 ? "red" : "green" }}">Discount:
                                                            {{ $voucher->type == "percent" ? $voucher->value . "%" : number_format($voucher->value) . " VND" }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-span-2">
                                        <div class="flex flex-col items-center justify-center">
                                            <p class="text-3xl font-bold">Oops, you don't have any voucher</p>
                                            <img class="h-[300px] w-auto" src="{{ asset("common/empty.jpg") }}"
                                                alt="EmptyImg">
                                            <a class="text-2xl font-extrabold text-blue-500 hover:text-blue-700"
                                                href="{{ route("vouchers") }}" class="text-blue-500 underline">Go
                                                get
                                                some</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="{{ asset("js/home/movie.js") }}"></script>
@endsection
