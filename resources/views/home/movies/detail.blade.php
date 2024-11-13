@extends('layouts.customer')

@section('content')
    <div class="flex flex-col">
        <div class="w-full h-[500px] bg-black flex items-center justify-center">
            <img class="w-[900px] h-full"
                src="{{ $movie->images->where('type', 'banner')->first() ? asset($movie->images->where('type', 'banner')->first()->url) : asset('default-image.jpg') }}" />
        </div>
        <div class="flex flex-col gap-4 -mt-10 z-10 ml-72 w-2/4">
            <div class="flex gap-4">
                <img class="w-[280px] h-[400px] border-3 border-white rounded-md"
                    src="{{ $movie->images->where('type', 'poster')->first() ? asset($movie->images->where('type', 'poster')->first()->url) : asset('default-image.jpg') }}" />
                <div class="flex flex-col gap-3 justify-end">
                    <h1 class="text-3xl font-extrabold">{{ $movie->name }}</h1>
                    <div class="flex gap-4">
                        <div class="flex gap-2 items-center">
                            <i class="far fa-clock text-[#f8c92c] text-xl"></i>
                            <p>{{ $movie->duration }} minutes</p>
                        </div>
                        <div class="flex gap-2 items-center justify-center">
                            <i class="far fa-calendar text-[#f8c92c] text-xl"></i>
                            <p>{{ $movie->start_date }}</p>
                        </div>
                    </div>
                    <p>Category:
                        @foreach ($movie->categories as $category)
                            {{ $category->name }}@if (!$loop->last)
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
                    <div class="w-[5px] h-[25px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Description</h1>
                </div>
                <p class="text-base">{{ $movie->description }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="w-[5px] h-[25px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Schedule</h1>
                </div>
                <div class="flex overflow-x-auto" id="date-selector">
                    @php
                        use Carbon\Carbon;
                        $dates = collect(range(0, 6))->map(function ($day) {
                            return Carbon::today()->addDays($day);
                        });
                    @endphp
                    @foreach ($dates as $date)
                        <div class="flex-shrink-0 mx-2 py-2 px-3 rounded cursor-pointer date-item {{ $date->isToday() ? 'bg-blue-600 text-white' : 'bg-gray-200' }}"
                            data-date="{{ $date->toDateString() }}">
                            <p class="text-center font-bold">{{ $date->format('l') }}</p>
                            <p class="text-center">{{ $date->format('d/m') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="movie-id" id="movie-id" value="{{ $movie->id }}">
            <div class="mt-1" id="timeslot-section">
                <h2 class="font-bold text-lg">Mirabo Đà Nẵng</h2>
                <div class="mt-2">
                    <p>2D Phụ Đề</p>
                    <div class="grid grid-cols-6 gap-4 mt-2" id="timeslot-container">
                        @php
                            $sortedShowtimes = $showtimes->sortBy(function ($showtime) {
                                return Carbon::parse($showtime->start_time)->format('H:i');
                            });
                        @endphp
                        @foreach ($sortedShowtimes as $showtime)
                            <button
                                onclick="openSeatSelectionModal('{{ $today }}',{{ $movie->id }},{{ $showtime->id }})"
                                class="border border-gray-300 px-2 py-2 rounded hover:bg-gray-300 showtime-button">
                                {{ Carbon::parse($showtime->start_time)->format('H:i') }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="seatSelectionModal" class="hidden">
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-75">
                <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 max-w-2xl relative">
                    <button onclick="closeSeatSelectionModal()"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
                    <h2 class="text-2xl font-bold mb-4">Select Seats</h2>
                    <div id="seats-container" class="grid grid-cols-10 gap-2">
                    </div>
                    <div class="flex justify-between mt-4">
                        <div class="flex gap-4">
                            <h1 class="text-lg font-extrabold">Price :</h1>
                            <span id="total_price" class="text-lg font-extrabold">0 VND</span>
                        </div>
                        <button onclick="bookSeats()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Book
                            Selected Seats</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden" id="invoice-field">
            <div
                class="invoice-container w-[800px] ml-72 p-6 rounded-lg shadow-md flex flex-col justify-center items-center relative">
                <h2 class="text-2xl font-semibold mb-4">Invoice</h2>
                <div class="mb-2">
                    <p class="text-lg font-medium" id="default-price">Price: <span class="text-gray-700">0 VND</span></p>
                    <div>
                        <div class="absolute right-2 top-20">
                            <button type="button" onclick="openVoucherModal()"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Select Voucher
                            </button>
                        </div>
                    </div>
                </div>
                <div class="hidden ml-10 text-lg font-medium text-red-500" id="discount-amount">
                    <h1></h1>
                </div>
                <div class="mb-6">
                    <p class="text-lg font-medium pt-3 border-t-2">Total : <span id="total_amount" class="text-green-600">0
                            VND</span></p>
                </div>
                <form action="{{ route('momo-payment') }}" method="POST" class="text-center"
                    onsubmit="setDefaultValues()">
                    @csrf
                    <input type="hidden" name="default_price" id="hidden_default_price">
                    <input type="hidden" name="total_amount" id="hidden_total_amount">
                    <input type="hidden" name="voucher_code" id="hidden_voucher_code">
                    <input type="hidden" name="discount_value" id="hidden_discount_value">
                    <input type="hidden" name="customer_id" id="hidden_customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="selected_seats" id="hidden_seats_selected">
                    <input type="hidden" name="schedule_id" id="hidden_schedule_id">
                    <input type="hidden" name="showtime_id" id="hidden_showtime_id">
                    <input type="hidden" name="auditorium_id" id="hidden_auditorium_id">
                    <input type="hidden" name="movie_id" id="hidden_movie_id" value="{{ $movie->id }}">
                    <button type="submit" name="payUrl"
                        class="w-full px-14 bg-pink-500 text-white font-extrabold py-2 rounded-md hover:bg-pink-600">
                        Checkout with MOMO
                    </button>
                </form>
            </div>
        </div>
        <div id="voucherSelectionModal" class="hidden">
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-75">
                <div
                    class="bg-white p-8 rounded-lg shadow-lg w-[1000px] max-h-[500px] relative flex flex-col items-center justify-center">
                    <button onclick="closeVoucherModal()"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
                    <h2 class="text-2xl font-bold mb-4">Select Voucher</h2>
                    <div class="flex items-center justify-centerl w-full">
                        <div
                            class="grid grid-cols-2 gap-4 overflow-y-auto w-full items-center justify-between mx-16 max-h-[400px]">
                            @if ($vouchers && $vouchers->isNotEmpty())
                                @php
                                    $sortedvouchers = $vouchers->sortByDesc(function ($voucher) {
                                        return $voucher->value;
                                    });
                                @endphp
                                @foreach ($sortedvouchers as $voucher)
                                    @if ($voucher->pivot->status == 0)
                                        <div id="voucher_code" class="cursor-pointer"
                                            onclick="selectVoucher('{{ $voucher->code }}')">
                                            <input type="hidden" name="voucher_id" value="{{ $voucher }}">
                                            <div
                                                class="max-w-sm rounded overflow-hidden shadow-sm bg-gradient-to-r from-[#FF8160] to-[#FEB179] my-4 relative ticket-style">
                                                <div class="px-6 py-4">
                                                    <div class="font-bold text-2xl mb-2 font-mono"
                                                        style="color: {{ $voucher->value >= 50 ? 'red' : 'green' }}">
                                                        {{ $voucher->description }}
                                                    </div>
                                                    <p class="ml-2 text-gray-100 text-lg font-bold uppercase">
                                                        {{ $voucher->code }}
                                                    </p>
                                                </div>
                                                <div class="px-6 pt-1 pb-2">
                                                    <span
                                                        class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                                                        style="color: {{ $voucher->value >= 50 ? 'red' : 'green' }}">Discount:
                                                        {{ $voucher->value }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-span-2">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-3xl font-bold">Oops, you don't have any voucher</p>
                                        <img class="h-[300px] w-auto" src="{{ asset('common/empty.jpg') }}"
                                            alt="EmptyImg">
                                        <a class="text-2xl font-extrabold text-blue-500 hover:text-blue-700"
                                            href="{{ route('vouchers') }}" class="text-blue-500 underline">Go get
                                            some</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script src="{{ asset('js/home/movie.js') }}"></script>
        <script>
            const vouchers = @json($vouchers);
        </script>
    @endsection
