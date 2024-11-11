<div class="row p-3">
    <div class="grid-cols-{{ $rows }} col-10 grid gap-2">
        @foreach ($seats as $seat)
            <div class="seat{{ $seat->tickets->first() && $seat->tickets->first()->status == "ordered" ? " bg-secondary-subtle" : "" }} bg-gradient rounded-md border"
                data-id="{{ $seat->id }}">
                <p class="my-auto p-3">{{ $seat->seat_number }}</p>
            </div>
        @endforeach
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
        <div class="row align-items-center w-50 gap-2">
            <div class="flex flex-col col-4">
                <span class="price">Price: 0 VND</span>
                <span class="discount text-red-500"></span>
                <input type="hidden" name="voucher_id" class="voucher-id">
            </div>
            <button type="button" class="btn btn-outline-primary w-25 col-8" data-bs-toggle="modal"
                data-bs-target="#voucher-modal">Select Voucher</button>
        </div>
        <hr>
        <p class="total my-2 text-lg font-bold text-end">Total: 0 VND</p>
        <input type="hidden" name="price" class="total-price">
    </div>
</div>
