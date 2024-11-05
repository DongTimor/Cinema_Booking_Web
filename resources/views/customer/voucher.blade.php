@extends('layouts.customer')

@section('content')
    <div class="">
        <div class="mx-10 mt-10">
            <div>
                <h1 class="text-2xl font-extrabold">New Vouchers</h1>
                @if ($vouchers->isEmpty())
                    <h1 class="text-xl font-bold text-center mt-4">Run out of vouchers, wait for new voucher to update..</h1>
                @endif
                <div class="grid grid-cols-3 justify-center">
                    @foreach ($vouchers as $voucher)
                        <form action="{{ route('vouchers.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                            <div
                                class="max-w-sm rounded overflow-hidden shadow-lg bg-gradient-to-r from-blue-400 to-blue-600 my-4 relative ticket-style">
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
                                        class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Quantity:
                                        {{ $voucher->quantity }}</span>
                                    <span
                                        class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                                        style="color: {{ $voucher->value >= 50 ? 'red' : 'green' }}">Discount:
                                        {{ $voucher->value }}%</span>
                                    <span
                                        class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Expiry:
                                        {{ $voucher->expires_at }}</span>
                                </div>
                                @if ($customerVouchers->contains($voucher->id))
                                    <span
                                        class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Saved</span>
                                @elseif($voucher->quantity == 0)
                                    <span class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Out of
                                        turn</span>
                                @else
                                    <button type="submit"
                                        class="px-4 py-2 rounded-md bg-yellow-400 absolute right-2 bottom-3 font-bold">Save</button>
                                @endif
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .ticket-style {
            position: relative;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            border-radius: 8px;
            overflow: hidden;
        }

        .ticket-style::before,
        .ticket-style::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 60px;
            background-color: #fff;
            border-radius: 50%;
        }

        .ticket-style::before {
            top: 50%;
            left: -30px;
            transform: translateY(-50%);
        }

        .ticket-style::after {
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
        }
    </style>
@endsection
