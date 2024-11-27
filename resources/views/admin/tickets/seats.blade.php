<div class="row p-3">
    <div class="grid-cols-{{ $rows }} col-10 grid gap-2">
        @foreach ($seats as $seat)
            <div class="seat{{ in_array($seat->id, $orderedSeats) ? " bg-secondary-subtle" : "" }} bg-gradient rounded-md border"
                data-id="{{ $seat->id }}" data-name="{{ $seat->seat_number }}">
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
        <div class="align-items-center flex gap-3">
            <div class="col-4 text-md">
                <div class="align-items-center row">
                    <span class="col-3">Price: </span>
                    <span class="price col-4 text-right">0 VND</span>
                    <span class="event-price text-decoration-line-through col-4 text-sm text-gray-400 text-left pl-0"></span>
                </div>
                <div class="row hidden">
                    <span class="col-3">Discount: </span>
                    <span class="col-4 discount text-right text-red-500"></span>
                </div>
            </div>
            <div class="voucher col-4">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#voucher-modal">Select Voucher</button>
            </div>
        </div>
        <hr>
        <p class="total my-2 text-end text-lg font-bold">Total: 0 VND</p>
    </div>
</div>
