@extends("layouts.customer")

@section("content")
    <div class="flex w-full justify-end">
        <div class="mr-3 mt-3 w-[500px]">
            <div class="flex items-center justify-between">
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
            <div class="mt-3 flex items-center justify-between text-lg">
                <div class="h5 mb-0">Available Points: <span class="text-orange-500"><i class="fas fa-ticket-alt"></i>
                        {{ $customerPoint->points_earned }}</span>
                </div>
                <a href="{{ route("home.vouchers.exchange") }}" type="button"
                    class="btn btn-primary rounded-xl border-none bg-gradient-to-r from-[#FF3E8A] to-[#FF9F2B] font-extrabold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:brightness-125"
                    role="button">
                    Exchange Points
                </a>
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
                    <a class="my-2 text-xl font-extrabold text-blue-500 hover:text-blue-700" href="{{ route("vouchers") }}"
                        class="text-blue-500 underline">Go get some</a>
                    </img>
                </div>
            @endif
            <div class="my-3 grid grid-cols-1 justify-center gap-20 md:grid-cols-2">
                @foreach ($vouchers as $voucher)
                    <div
                        class="voucher-card {{ $voucher->points_required > 0 ? "bg-gradient-to-r from-sky-500 to-emerald-500" : "" }} my-2">
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
@endsection
@section("styles")
    <link rel="stylesheet" href="{{ asset("css/vouchers/collection.css") }}">
@endsection
