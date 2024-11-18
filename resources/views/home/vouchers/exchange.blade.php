@extends("layouts.customer")
@section("content")
    <div class="mx-auto mt-10 max-w-4xl">
        <p>
        <h1 class="h1 flex justify-center text-center font-extrabold">Exchange Vouchers</h1>
        @if ($vouchers->isEmpty())
            <div class="flex flex-col items-center justify-center">
                <img src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif" class="w-50"
                    alt="404">
                <h1 class="empty text-center text-xl font-bold">Currently, there are no vouchers available for exchange.
                </h1>
            </div>
        @else
            <div class="h5 mb-0">Available Points: <span class="text-orange-500"><i class="fas fa-ticket-alt"></i>
                    {{ $customerPoint->points_earned }}</span>
            </div>
            <div class="my-3 grid grid-cols-1 justify-center gap-20 md:grid-cols-2">
                @foreach ($vouchers as $voucher)
                    <form action="{{ route("customer.vouchers.exchange", $voucher->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="points" value="{{ $voucher->points_required }}">
                        <div class="voucher-card my-2 bg-gradient-to-r from-sky-500 to-emerald-500">
                            <div class="voucher-title">{{ $voucher->description }}</div>
                            <div class="text-uppercase h3 text-white">{{ $voucher->code }}</div>
                            <div class="voucher-details flex flex-wrap gap-3">
                                <span class="voucher-badge">Quantity: {{ $voucher->quantity }}</span>
                                <span class="voucher-badge badge-discount">Discount:
                                    {{ $voucher->type == "percent" ? $voucher->value . "%" : number_format($voucher->value) . " VND" }}</span>
                                <span class="voucher-badge">Expiry:
                                    {{ \Carbon\Carbon::parse($voucher->expires_at)->format("d/m/Y") }}</span>
                            </div>
                            <button type="submit"
                                class="h5 {{ in_array($voucher->id, $customerVouchers) ? "pe-none bg-gray-500" : "bg-orange-500" }} absolute bottom-3 right-3 rounded-md p-2 font-bold text-white hover:bg-orange-400"><i
                                    class="fas fa-ticket-alt"></i> {{ $voucher->points_required }}</button>
                        </div>
                    </form>
                @endforeach
            </div>
        @endif
    </div>
@endsection
@section("styles")
    <link rel="stylesheet" href="{{ asset("css/vouchers/collection.css") }}">
@endsection
