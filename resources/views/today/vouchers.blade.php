@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl text-center font-bold mb-6 mt-4">Currently Valid Vouchers</h1>

    @if ($vouchers->isEmpty())
        <p class="text-center mt-4">No vouchers are valid at this time.</p>
    @else
        <div class="">
            <div class="mx-auto max-w-4xl mt-10">
                <div>
                    @if ($vouchers->isEmpty())
                        <div class="flex flex-col items-center justify-center">
                            <img src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif" class="w-50"
                                alt="404">
                            <h1 class="text-xl font-bold text-center mt-4">Run out of vouchers, wait for new voucher to update..
                            </h1>
                        </div>
                    @endif
                    <div class="my-3 grid grid-cols-1 justify-center gap-20 md:grid-cols-2">
                        @foreach ($vouchers->where('points_required', 0) as $voucher)
                            <form action="{{ route('vouchers.save', $voucher->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                <div class="voucher-card my-2">
                                    <div class="voucher-title">{{ $voucher->description }}</div>
                                    <div class="text-uppercase h3 text-white">{{ $voucher->code }}</div>
                                    <div class="voucher-details flex flex-wrap gap-3">
                                        <span class="voucher-badge">Quantity: {{ $voucher->quantity }}</span>
                                        <span class="voucher-badge badge-discount">Discount:
                                            {{ $voucher->type == 'percent' ? $voucher->value . '%' : number_format($voucher->value) . ' VND' }}</span>
                                        <span class="voucher-badge">Expiry:
                                            {{ \Carbon\Carbon::parse($voucher->expires_at)->format('d/m/Y') }}</span>
                                    </div>
                                    @if ($customerVouchers->contains($voucher->id))
                                        <span
                                            class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Saved</span>
                                    @elseif($voucher->quantity == 0)
                                        <span class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Out of turn</span>
                                    @else
                                        <button type="submit"
                                            class="px-4 py-2 rounded-md bg-yellow-400 absolute right-2 bottom-3 font-bold hover:bg-yellow-500">Save</button>
                                    @endif
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vouchers/collection.css') }}">
@endsection
