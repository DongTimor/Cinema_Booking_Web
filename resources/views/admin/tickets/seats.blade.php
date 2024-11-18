@php
if($orderedSeats !== "null"){
    $orderedSeatsArray = json_decode($orderedSeats, true);
}else{
    $orderedSeatsArray = [];
}
@endphp
<div class="row p-3">
    <div class="grid-cols-{{ $rows }} col-10 grid gap-2">
        @if (count($orderedSeatsArray) > 0)
            @foreach ($seats as $seat)
                <div class="seat{{ in_array($seat->id, $orderedSeatsArray) ? ' bg-secondary-subtle' : '' }} bg-gradient rounded-md border"
                    data-id="{{ $seat->id }}">
                    <p class="my-auto p-3">{{ $seat->seat_number }}</p>
                </div>
            @endforeach
        @else
            @foreach ($seats as $seat)
                <div class="seat bg-gradient rounded-md border" data-id="{{ $seat->id }}">
                    <p class="my-auto p-3">{{ $seat->seat_number }}</p>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-2">
        <div class="flex flex-col">
            <div class="flex">
                <span class="square-20 bg-light bg-gradient border"></span>
                <div class="ml-2">Empty</div>
            </div>
            <div class="flex">
                <span class="square-20 bg-secondary-subtle bg-gradient border"></span>
                <div class="ml-2">Ordered</div>
            </div>
            <div class="flex">
                <span class="square-20 bg-primary bg-gradient border"></span>
                <div class="ml-2">Selected</div>
            </div>
        </div>
    </div>
    <div class="invoice-group my-3">
        <div class="row align-items-top w-50 gap-5">
            <div class="flex flex-col col-4">
                <div class="flex justify-between">
                    <span class="price">Price</span>
                    <span id="price" class="price">0 VND</span>
                </div>
                <div class="flex justify-between">
                    <span >Voucher</span>
                    <span id="voucher-discount" class="discount text-red-500"></span>
                </div>
                <div class="flex justify-between">
                    <span >Event</span>
                    <span id="event-discount" class="discount text-red-500"></span>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary w-25 col-8" style="height: 38px;" data-bs-toggle="modal"
                data-bs-target="#voucher-modal">Select Voucher</button>
        </div>
        <hr>
        <div class="flex justify-between">
            <p id="quantity" class="my-2 text-sm text-end" style="color: #0088ff;">Quantity: 0</p>
            <p id="total" class="total my-2 text-lg font-bold text-end">Total: 0 VND</p>
        </div>
        <input type="hidden" name="price" class="total-price">
        <input type="hidden" name="voucher_id" class="voucher-id">
    </div>
</div>
