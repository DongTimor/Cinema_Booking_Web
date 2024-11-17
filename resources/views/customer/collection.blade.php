@extends("layouts.customer")

@section("content")
    <div class="flex w-full justify-end">
        <div class="mr-3 mt-3 w-[500px]">
            <div class="flex justify-between items-center">
                <div class="text-xl font-bold" style="color: {{ $color }}">
                    {{ $customerPoint->ranking_level }} Member
                </div>
                <div>
                    {{ $customerPoint->total_points }} / {{ $points }} points to
                    {{ $nextLevel }}
                </div>
            </div>
            <div class="mb-2 mt-2 h-4 w-full rounded-full bg-gray-200">
                <div class="h-4 rounded-full bg-green-500"
                    style="width: {{ $customerPoint->total_points > 0 ? ($customerPoint->total_points / $points) * 100 : 0 }}%">
                </div>
            </div>
            <div class="text-lg flex justify-between items-center mt-3">
                <h1>Available Points: {{ $customerPoint->points_earned }}</h1>
                <button type="button"
                    class="btn btn-primary bg-gradient-to-r from-[#FF3E8A] to-[#FF9F2B] border-none font-extrabold rounded-xl 
                hover:brightness-125 hover:shadow-lg hover:scale-105 transition-all duration-300"
                    data-bs-toggle="modal" data-bs-target="#exchange-points-modal">
                    Exchange Points
                </button>
            </div>
        </div>
    </div>
    <div class="mx-auto max-w-4xl">
        <div>
            <p class="h1 text-center font-extrabold">My Vouchers</p>
            @if ($vouchers->isEmpty())
                <div class="flex flex-col items-center">
                    <img src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif" class="w-50"
                        alt="404">
                    <p class="empty text-3xl font-bold">Oops, you don't have any voucher</p>
                    <a class="text-xl font-extrabold text-blue-500 hover:text-blue-700 my-2" href="{{ route("vouchers") }}"
                        class="text-blue-500 underline">Go get some</a>
                    </img>
                </div>
            @endif
            <div class="my-3 grid grid-cols-1 justify-center gap-20 md:grid-cols-2">
                @foreach ($vouchers as $voucher)
                    <div
                        class="voucher-card my-2 {{ $voucher->points_required > 0 ? 'bg-gradient-to-r from-sky-500 to-emerald-500' : '' }}">
                        <div class="voucher-title">{{ $voucher->description }}</div>
                        <div class="text-uppercase h3 text-white">{{ $voucher->code }}</div>
                        <div class="voucher-details flex flex-wrap gap-3">
                            <span class="voucher-badge">Quantity: {{ $voucher->quantity }}</span>
                            <span class="voucher-badge badge-discount">Discount:
                                {{ $voucher->type == "percent" ? $voucher->value . "%" : number_format($voucher->value) . "VND" }}</span>
                            <span class="voucher-badge">Expiry:
                                {{ \Carbon\Carbon::parse($voucher->expires_at)->format("d/m/Y") }}</span>
                            <input type="hidden" name="voucher_id" class="value" value="{{ $voucher->id }}"
                                data-value="{{ $voucher->value }}" data-type="{{ $voucher->type }}">
                        </div>
                        <a href="{{ route("movies") }}"
                            class="btn use-btn {{ $voucher->pivot->status == 1 ? "pe-none bg-gray-600" : "bg-green-500 hover:bg-green-700" }} mb-2 rounded-lg border-0 px-4 py-1 text-lg font-extrabold text-white"
                            role="button">
                            Use
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal fade" id="exchange-points-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exchange Points</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div
                        class="my-3 grid grid-cols-1 justify-center gap-20 md:grid-cols-2 overflow-hidden overflow-y-auto max-h-[500px]">
                        @foreach ($pointRequiredVouchers as $voucher)
                            <form action="{{ route('vouchers.exchange', $voucher->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="points" value="{{ $voucher->points_required }}">
                                <div class="voucher-card my-2 bg-gradient-to-r from-sky-500 to-emerald-500 h-48">
                                    <div class="flex justify-between items-center">
                                        <div class="voucher-title text-2xl">{{ $voucher->description }}</div>
                                        <div>
                                            @if ($customerVouchers->contains($voucher->id))
                                                <span class="px-4 py-2 rounded-md bg-gray-400 font-bold">Saved</span>
                                            @elseif($voucher->quantity == 0)
                                                <span class="px-4 py-2 rounded-md bg-gray-400 font-bold">Out
                                                    of turn</span>
                                            @else
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-md bg-yellow-400 font-bold hover:bg-yellow-500">Exchange</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-uppercase h3 text-white text-lg">{{ $voucher->code }}</div>
                                    <div class="voucher-details flex flex-wrap gap-2 mt-4">
                                        <span class="voucher-badge text-[12px]">Quantity: {{ $voucher->quantity }}</span>
                                        <span class="voucher-badge badge-discount text-[12px]">Discount:
                                            {{ $voucher->type == 'percent' ? $voucher->value . '%' : number_format($voucher->value) . ' VND' }}</span>
                                        <div class="flex gap-2">
                                            <span class="voucher-badge text-[12px]">Expiry:
                                                {{ \Carbon\Carbon::parse($voucher->expires_at)->format('d/m/Y') }}</span>
                                            <span class="voucher-badge text-[12px]">Points Required:
                                                {{ $voucher->points_required }}</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vouchers/collection.css') }}">
@endsection
