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
        <div class="mx-auto max-w-4xl">
            <div>
                <h1 class="text-2xl font-extrabold text-center mb-4">My Vouchers</h1>
                <div class="">
                    @if ($customerVouchers->isEmpty())
                        <p class="text-3xl font-bold">Oops, you don't have any voucher</p>
                        <a class="text-xl font-extrabold text-blue-500 hover:text-blue-700" href="{{ route('vouchers') }}"
                            class="text-blue-500 underline">Go get some</a>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-20 justify-center">
                        @foreach ($customerVouchers as $customerVoucher)
                            <div>
                                <input type="hidden" name="voucher_id" value="{{ $customerVoucher['voucher_id'] }}">
                                <div
                                    class="rounded overflow-hidden shadow-lg bg-gradient-to-r from-[#FF8160] to-[#FEB179] my-4 relative ticket-style">
                                    <div class="px-6 py-4">
                                        <div class="flex justify-between items-center">
                                            <div class="font-bold text-2xl mb-2 font-mono"
                                                style="color: {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->value >= 50 ? 'red' : 'green' }}">
                                                {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->description }}
                                            </div>
                                            @if ($customerVoucher['status'] == 1)
                                                <button
                                                    class="bg-gray-500 text-white px-4 py-1 text-lg font-extrabold mb-2 rounded-lg"
                                                    disabled>
                                                    Used
                                                </button>
                                            @else
                                                <a href="{{ route('home') }}"
                                                    class="bg-green-500 text-white px-4 py-1 text-lg font-extrabold mb-2 rounded-lg hover:bg-green-700">
                                                    Use
                                                </a>
                                            @endif
                                        </div>
                                        <p class="ml-2 text-gray-100 text-lg font-bold uppercase">
                                            {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->code }}
                                        </p>
                                    </div>
                                    <div class="px-6 pt-1 pb-2">
                                        <span
                                            class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                                            style="color: {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->value >= 50 ? 'red' : 'green' }}">Discount:
                                            {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->value }}%</span>
                                        <span
                                            class="inline-block bg-white rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Expiry:
                                            {{ $vouchers->firstWhere('id', $customerVoucher['voucher_id'])->expires_at }}</span>
                                    </div>
                                </div>
                            </div>
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
