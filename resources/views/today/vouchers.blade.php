@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl text-center font-bold mb-6 mt-4">Currently Valid Vouchers</h1>

    @if ($vouchers->isEmpty())
        <p class="text-center mt-4">No vouchers are valid at this time.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mx-2 mt-4">
            @foreach ($vouchers as $voucher)
            <form action="{{ route('vouchers.save') }}" method="POST" class="mx-2 container">
                @csrf
                <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                <div
                    class="max-w-sm rounded overflow-hidden shadow-lg bg-gradient-to-r from-blue-400 to-blue-600 my-4 relative ticket-style">
                    <div class="px-6 py-4">
                        <div class="font-bold text-2xl mb-2 font-mono text-center"
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
                        <span class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Saved</span>
                    @elseif($voucher->quantity == 0)
                        <span class="px-4 py-2 rounded-md bg-gray-400 absolute right-2 bottom-3 font-bold">Out of turn</span>
                    @else
                        <button type="submit" class="px-4 py-2 rounded-md bg-yellow-400 absolute right-2 bottom-3 font-bold">Save</button>
                    @endif
                </div>
            </form>
        @endforeach
        </div>
    @endif
@endsection
