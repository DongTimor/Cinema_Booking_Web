@extends('layouts.customer')

@section('content')
    <div class="">
        <div class="flex justify-end w-full">
            <div class="w-[500px] mr-3 mt-3">
                <div class="text-xl font-bold"
                    style="color: {{ $points->ranking_level == 'Bronze' ? '#854c12' : ($points->ranking_level == 'Silver' ? '#868686' : ($points->ranking_level == 'Gold' ? '#FFD700' : 'black')) }}">
                    {{ $points->ranking_level }} Member
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2 mt-2">
                    @php
                        $pointsToNextLevel = 0;
                        if ($points->ranking_level == 'Bronze') {
                            $pointsToNextLevel = 150;
                        } elseif ($points->ranking_level == 'Silver') {
                            $pointsToNextLevel = 250;
                        }
                    @endphp
                    <div class="bg-green-500 h-4 rounded-full"
                        style="width: {{ $points->total_points >= $pointsToNextLevel ? 100 : ($points->total_points / $pointsToNextLevel) * 100 }}%">
                    </div>
                </div>
                <div class="text-sm">
                    {{ $points->total_points }} / {{ $pointsToNextLevel }} points to
                    {{ $points->ranking_level == 'Bronze' ? 'Silver' : ($points->ranking_level == 'Silver' ? 'Gold' : 'next level') }}
                </div>
            </div>
        </div>
        <div class="mx-10">
            <div>
                <h1 class="text-2xl font-extrabold">My Vouchers</h1>
                <div class="">
                    @if ($customerVouchers->isEmpty())
                        <h1 class="text-xl font-bold text-center mt-4">You don't have any vouchers yet, collect them now!</h1>
                    @endif
                    @foreach ($customerVouchers as $customerVoucher)
                        <div>
                            <form action="" method="POST">
                                @csrf
                                <input type="hidden" name="voucher_id" value="{{ $customerVoucher }}">
                                <div
                                    class="max-w-sm rounded overflow-hidden shadow-lg bg-gradient-to-r from-blue-400 to-blue-600 my-4 relative ticket-style">
                                    <div class="px-6 py-4">
                                        <div class="font-bold text-2xl mb-2 font-mono"
                                            style="color: {{ $vouchers->firstWhere('id', $customerVoucher)->value >= 50 ? 'red' : 'green' }}">
                                            {{ $vouchers->firstWhere('id', $customerVoucher)->description }}
                                        </div>
                                        <p class="ml-2 text-gray-100 text-lg font-bold">
                                            {{ $vouchers->firstWhere('id', $customerVoucher)->code }}
                                        </p>
                                    </div>
                                    <div class="px-6 pt-1 pb-2">
                                        <span
                                            class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                                            style="color: {{ $vouchers->firstWhere('id', $customerVoucher)->value >= 50 ? 'red' : 'green' }}">Discount:
                                            {{ $vouchers->firstWhere('id', $customerVoucher)->value }}%</span>
                                        <span
                                            class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Expiry:
                                            {{ $vouchers->firstWhere('id', $customerVoucher)->expires_at }}</span>
                                    </div>
    
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold">New Vouchers</h1>
                @if ($vouchers->isEmpty())
                    <h1 class="text-xl font-bold text-center mt-4">Run out of vouchers, wait for new voucher to update..</h1>
                @endif
                <div class="grid grid-cols-3">
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
                                    <p class="ml-2 text-gray-100 text-lg font-bold">
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
