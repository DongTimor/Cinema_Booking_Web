<div class="row p-3">
    <div class="col-10 grid-cols-{{ $rows }} grid gap-2">
        @foreach ($seats as $seat)
            <div class="seat{{ in_array($seat->id, $orderedSeats) ? " bg-secondary-subtle pe-none" : "" }} bg-gradient cursor-pointer rounded-md border py-3 text-center font-bold"
                id="{{ $seat->id }}">{{ $seat->seat_number }}
            </div>
        @endforeach
    </div>
    <div class="col-2">
        <div class="flex flex-col gap-2">
            <div class="flex">
                <span class="w-[20px] bg-light bg-gradient border"></span>
                <div class="ml-2">Empty</div>
            </div>
            <div class="flex">
                <span class="w-[20px] bg-secondary-subtle bg-gradient border"></span>
                <div class="ml-2">Ordered</div>
            </div>
            <div class="flex">
                <span class="w-[20px] bg-primary bg-gradient border"></span>
                <div class="ml-2">Selected</div>
            </div>
        </div>
    </div>
    <div class="mt-3 flex items-center justify-between">
        <p id="total" class="text-lg font-extrabold">Price: 0 VND</p>
        <button type="button" onclick="bookSeats()" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
            data-bs-dismiss="modal">Book
            Selected Seats</button>
    </div>
</div>
