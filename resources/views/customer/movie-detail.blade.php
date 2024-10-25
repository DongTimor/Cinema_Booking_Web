@extends('layouts.app')

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
            </div>
            <div class="invoice-container w-[800px] mx-auto p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Invoice</h2>
                <div class="mb-4">
                    <p class="text-lg font-medium">Total: <span class="text-gray-700">150,000 VND</span></p>
                </div>
                <div class="mb-4">
                    <label for="voucher_code" class="block text-sm font-medium text-gray-600 mb-2">Select a Voucher:</label>
                    <div class="flex items-center">
                        <select name="voucher_code" id="voucher_code" class="w-full p-2 border rounded-md">
                            <option value="" selected disabled hidden>Your Vouchers</option>
                            @foreach ($userVouchers as $userVoucher)
                                <option value="{{ $vouchers->firstWhere('id', $userVoucher)->code }}">
                                    {{ $vouchers->firstWhere('id', $userVoucher)->description }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="applyVoucher()"
                            class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Apply
                        </button>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-lg font-medium">Total after Discount: <span id="total_amount"
                            class="text-green-600">150,000 VND</span></p>
                </div>
                <form action="{{ route('momo-payment') }}" method="POST" class="text-center" onsubmit="setDefaultValues()">
                    @csrf
                    <input type="hidden" name="total_amount" id="hidden_total_amount">
                    <input type="hidden" name="voucher_code" id="hidden_voucher_code">
                    <button type="submit" name="payUrl"
                        class="w-1/3 bg-pink-500 text-white font-extrabold py-2 rounded-md hover:bg-pink-600">
                        Checkout with MOMO
                    </button>
                </form>
            </div>
            <script>
                const userVouchers = @json($userVouchers);
                const vouchers = @json($vouchers);
                let originalTotal = 150000;

                function applyVoucher() {
                    const voucherCode = document.getElementById('voucher_code').value;
                    let total = originalTotal;
                    const voucher = vouchers.find(v => v.code === voucherCode && userVouchers.includes(v.id));
                    if (voucher) {
                        total = total - (total * (voucher.value / 100));
                    }
                    document.getElementById('total_amount').innerText = total + ' VND';
                    document.getElementById('hidden_total_amount').value = total;
                    document.getElementById('hidden_voucher_code').value = voucherCode;
                }

                function setDefaultValues() {
                    const totalAmountInput = document.getElementById('hidden_total_amount');
                    if (!totalAmountInput.value) {
                        totalAmountInput.value = originalTotal;
                    }
                }
            </script>
@endsection
