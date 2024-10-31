@extends('layouts.customer')

@section('content')
    <div class="flex flex-col">
        <div class="w-full h-[500px] bg-black flex items-center justify-center">
            <img class="w-[900px] h-full"
                src="{{ $movie->images->skip(1)->first()?->url ? asset($movie->images->skip(1)->first()->url) : asset('default-image.jpg') }}" />
        </div>
        <div class="flex flex-col gap-4 -mt-10 z-10 ml-72 w-2/4">
            <div class="flex gap-4">
                <img class="w-[280px] h-[400px] border-3 border-white rounded-md"
                    src="{{ $movie->images->first()?->url ? asset($movie->images->first()->url) : asset('default-image.jpg') }}" />
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

            <div class="mt-1" id="timeslot-section">
                <h2 class="font-bold text-lg">Mirabo Đà Nẵng</h2>
                <div class="mt-2">
                    <p>2D Phụ Đề</p>
                    <div class="grid grid-cols-6 gap-4 mt-2" id="timeslot-container">
                        @foreach ($showtimes as $showtime)
                            <button
                                onclick="openSeatSelectionModal('{{ $today }}',{{ $movie->id }},{{ $showtime->id }})"
                                class="border border-gray-300 px-2 py-2 rounded hover:bg-gray-300 showtime-button">
                                {{ $showtime->start_time }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="seatSelectionModal"
            class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-75">
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
                    <button onclick="bookSeats()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Book
                        Selected Seats</button>
                </div>
            </div>
        </div>
        <div class="invoice-container w-[800px] ml-72 p-6 rounded-lg shadow-md flex flex-col justify-center items-center relative">
            <h2 class="text-2xl font-semibold mb-4">Invoice</h2>
            <div class="mb-2" >
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
            <div class="hidden ml-10 text-lg font-medium text-red-500" id="discount-amount" ><h1></h1></div>
            <div class="mb-6">
                <p class="text-lg font-medium pt-3 border-t-2">Total : <span id="total_amount" class="text-green-600">0
                        VND</span></p>
            </div>
            <form action="{{ route('momo-payment') }}" method="POST" class="text-center" onsubmit="setDefaultValues()">
                @csrf
                <input type="hidden" name="total_amount" id="hidden_total_amount">
                <input type="hidden" name="voucher_code" id="hidden_voucher_code">
                <button type="submit" name="payUrl"
                    class="w-full px-14 bg-pink-500 text-white font-extrabold py-2 rounded-md hover:bg-pink-600">
                    Checkout with MOMO
                </button>
            </form>
        </div>
        <div id="voucherSelectionModal"
            class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-75">
            <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 max-w-2xl relative">
                <button onclick="closeVoucherModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
                <h2 class="text-2xl font-bold mb-4">Select Voucher</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($customerVouchers as $customerVoucher)
                        <div id="voucher_code" class="border border-gray-300 p-4 rounded hover:bg-gray-200 cursor-pointer"
                            onclick="selectVoucher('{{ $vouchers->firstWhere('id', $customerVoucher)->code }}')">
                            <h3 class="text-lg font-bold">{{ $vouchers->firstWhere('id', $customerVoucher)->description }}
                            </h3>
                            <p>Discount: {{ $vouchers->firstWhere('id', $customerVoucher)->value }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            const customerVouchers = @json($customerVouchers);
            const vouchers = @json($vouchers);
            let originalTotal = 0;
            let currentTotal = 0;
            let selectedSeats = [];

            function applyVoucher() {
                const voucherCode = document.getElementById('voucher_code').value;
                let total = Number(originalTotal);
                let discountAmount = 0;
                console.log(originalTotal);
                const voucher = vouchers.find(v => v.code === voucherCode && customerVouchers.includes(v.id));
                let discountValue = total * (voucher.value / 100)
                if (voucher) {
                    total = total - discountValue;
                }
                document.getElementById('total_amount').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
                document.getElementById('hidden_total_amount').value = total;
                document.getElementById('hidden_voucher_code').value = voucherCode;
                const discountAmountElement = document.getElementById('discount-amount');
                discountAmountElement.innerText = '- ' + discountValue + ' VND';
                discountAmountElement.classList.remove('hidden');
            }

            function setDefaultValues() {
                const totalAmountInput = document.getElementById('hidden_total_amount');
                if (!totalAmountInput.value) {
                    totalAmountInput.value = originalTotal;

                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const dateSelector = document.getElementById('date-selector');
                dateSelector.addEventListener('click', function(event) {
                    const item = event.target.closest('.date-item');
                    if (item) {
                        const selectedDate = item.getAttribute('data-date');
                        const movieId = {{ $movie->id }};
                        fetchShowtimes(selectedDate, movieId);
                        const activeItem = dateSelector.querySelector('.bg-blue-600');
                        if (activeItem) {
                            activeItem.classList.remove('bg-blue-600', 'text-white');
                            activeItem.classList.add('bg-gray-200');
                        }
                        item.classList.remove('bg-gray-200');
                        item.classList.add('bg-blue-600', 'text-white');
                    }
                });
            });

            async function fetchShowtimes(date, movieId) {
                try {
                    const response = await fetch(`/showtimes?date=${date}&movie_id=${movieId}`);
                    const data = await response.json();
                    const timeslotContainer = document.getElementById('timeslot-container');
                    timeslotContainer.innerHTML = '';
                    data.forEach(showtime => {
                        const button = document.createElement('button');
                        button.classList.add('border', 'border-gray-300', 'px-2', 'py-2', 'rounded',
                            'hover:bg-gray-300', 'showtime-button');
                        button.textContent = showtime.start_time;
                        button.addEventListener('click', function() {
                            openSeatSelectionModal(date, movieId, showtime.id);
                        });
                        timeslotContainer.appendChild(button);
                    });
                } catch (error) {
                    console.error('Error fetching showtimes:', error);
                }
            }

            function openSeatSelectionModal(date, movieId, showtimeId) {
                fetchSeats(date, movieId, showtimeId).then(() => {
                    selectedSeats.forEach(seatId => {
                        const seatDiv = document.querySelector(`[data-seat-id="${seatId}"]`);
                        if (seatDiv) {
                            seatDiv.classList.add('selected', 'bg-blue-500', 'text-white');
                            seatDiv.classList.remove('hover:bg-gray-300');
                        }
                    });
                });
                document.getElementById('seatSelectionModal').classList.remove('hidden');
            }

            async function fetchSeats(date, movieId, showtimeId) {
                const response = await fetch(`/seats?date=${date}&movie_id=${movieId}&showtime_id=${showtimeId}`);
                const data = await response.json();
                const seats = data.seats;
                const seatsContainer = document.getElementById('seats-container');
                seatsContainer.innerHTML = '';
                seats.forEach((seat) => {
                    const seatDiv = document.createElement('div');
                    seatDiv.classList.add('border', 'border-gray-300', 'px-2', 'py-2',
                        'rounded', 'hover:bg-gray-300', 'cursor-pointer');
                    seatDiv.textContent = seat.seat_number;
                    seatDiv.setAttribute('data-seat-id', seat.id);
                    seatDiv.setAttribute('data-seat-price', data.price);
                    seatDiv.addEventListener('click', function() {
                        toggleSeatSelection(seatDiv);
                    });
                    seatsContainer.appendChild(seatDiv);
                });
            }

            function toggleSeatSelection(seatDiv) {
                const seatId = seatDiv.getAttribute('data-seat-id');
                const seatPrice = parseFloat(seatDiv.getAttribute('data-seat-price'));
                if (seatDiv.classList.contains('selected')) {
                    seatDiv.classList.remove('selected');
                    seatDiv.classList.remove('bg-blue-500', 'text-white');
                    seatDiv.classList.add('hover:bg-gray-300');
                    selectedSeats = selectedSeats.filter(id => id !== seatId);
                    updateTotalPrice(-seatPrice);
                } else {
                    seatDiv.classList.add('selected');
                    seatDiv.classList.add('bg-blue-500', 'text-white');
                    seatDiv.classList.remove('hover:bg-gray-300');
                    selectedSeats.push(seatId);
                    updateTotalPrice(seatPrice);
                }
            }

            function updateTotalPrice(priceChange) {
                const totalPriceElement = document.getElementById('total_price');
                currentTotal += priceChange;
                totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(currentTotal) + ' VND';
            }

            function bookSeats() {
                originalTotal = document.getElementById('total_price').textContent.replace(' VND', '').replace(/\./g, '');
                document.getElementById('default-price').innerText = 'Price: ' + new Intl.NumberFormat('vi-VN').format(
                    originalTotal) + ' VND';
                document.getElementById('total_amount').innerText = document.getElementById('total_price').textContent;
                closeSeatSelectionModal();
            }

            function closeSeatSelectionModal() {
                document.getElementById('seatSelectionModal').classList.add('hidden');
            }

            function openVoucherModal() {
                document.getElementById('voucherSelectionModal').classList.remove('hidden');
            }

            function closeVoucherModal() {
                document.getElementById('voucherSelectionModal').classList.add('hidden');
            }

            function selectVoucher(voucherCode) {
                document.getElementById('voucher_code').value = voucherCode;
                applyVoucher();
                closeVoucherModal();
            }
        </script>
    @endsection
