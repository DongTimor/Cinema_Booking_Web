@extends('layouts.admin')

@section('content')

<form method="post" action="{{ route('vouchers.update', $voucher->id) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="code" class="form-label">Code voucher</label>
        <input type="text" class="form-control" name="code" value="{{ $voucher->code }}" disabled>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="textarea" class="form-control" name="description" value="{{ $voucher->description }}">
    @if ($errors->has('description'))
        <span class="text-danger">{{ $errors->first('description') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control" name="quantity" value="{{ $voucher->quantity }}">
    @if ($errors->has('quantity'))
        <span class="text-danger">{{ $errors->first('quantity') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="points_required" class="form-label">Points Required</label>
        <input type="number" class="form-control" name="points_required" value="{{ $voucher->points_required }}">
    @if ($errors->has('points_required'))
        <span class="text-danger">{{ $errors->first('points_required') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" name="type">
            <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Percent</option>
            <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Value</label>
        <input type="number" class="form-control" name="value" value="{{ $voucher->value }}">
    @if ($errors->has('value'))
        <span class="text-danger">{{ $errors->first('value') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="expires_at" class="form-label">Expires at</label>
        <input type="date" class="form-control" name="expires_at" value="{{ \Carbon\Carbon::parse($voucher->expires_at)->format('Y-m-d') }}">
    @if ($errors->has('expires_at'))
        <span class="text-danger">{{ $errors->first('expires_at') }}</span>
    @endif
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection
