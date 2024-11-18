@foreach ($vouchers as $voucher)
<div class="voucher-card my-2">
    <div class="voucher-title">{{ $voucher->description }}</div>
    <div class="text-uppercase h3 text-white">{{ $voucher->code }}</div>
    <div class="voucher-details flex flex-wrap gap-3">
        <span class="voucher-badge">Quantity: {{ $voucher->quantity }}</span>
        <span class="voucher-badge badge-discount">Discount:
            {{ $voucher->type == 'percent' ? $voucher->value . '%' : number_format($voucher->value) . 'VND' }}</span>
        <span class="voucher-badge">Expiry:
            {{ \Carbon\Carbon::parse($voucher->expires_at)->format('d/m/Y') }}</span>
    </div>
    <button id="voucher-btn-{{ $voucher->id }}" type="button"
        class="btn save-btn text-uppercase rounded-md border-0 shadow-sm"
        data-bs-dismiss="modal" onclick="selectVoucher({{ $voucher}})">Use</button>
    </div>
@endforeach
