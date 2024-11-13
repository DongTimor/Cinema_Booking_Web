@extends("layouts.customer")

@section("content")
    <div class="flex w-full justify-end">
        <div class="mr-3 mt-3 w-[500px]">
            <div class="text-xl font-bold" style="color: {{ $color }}">
                {{ $customerPoint->ranking_level }} Member
            </div>
            <div class="mb-2 mt-2 h-4 w-full rounded-full bg-gray-200">
                <div class="h-4 rounded-full bg-green-500"
                    style="width: {{ ($customerPoint->total_points / $points) * 100 }}%">
                </div>
            </div>
            <div class="text-sm">
                {{ $customerPoint->total_points }} / {{ $points }} points to
                {{ $nextLevel }}
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
                    <div class="voucher-card my-2">
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
                            class="btn use-btn {{ $voucher->pivot->status == 1 ? "pe-none bg-gray-600" : "" }} mb-2 rounded-lg border-0 bg-green-500 px-4 py-1 text-lg font-extrabold text-white hover:bg-green-700"
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
