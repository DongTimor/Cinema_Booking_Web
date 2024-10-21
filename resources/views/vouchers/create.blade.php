@extends('layouts.admin')

@section('content')

<div class="container">
    <form  class="h-auto overflow-y:auto" method="post" action="{{ route('vouchers.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="code" class="form-label">Code voucher</label>
          <input value="{{$code}}" type="text" class="form-control text-uppercase" name="code" >
        @if ($errors->has('code'))
            <span class="text-danger">{{ $errors->first('code') }}</span>
        @endif
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="textarea" class="form-control" name="description" >
          @if ($errors->has('description'))
              <span class="text-danger">{{ $errors->first('description') }}</span>
          @endif
          </div>
          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" >
          @if ($errors->has('quantity'))
              <span class="text-danger">{{ $errors->first('quantity') }}</span>
          @endif
          </div>
          <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input type="number" class="form-control" name="value" >
          @if ($errors->has('value'))
              <span class="text-danger">{{ $errors->first('value') }}</span>
          @endif
          </div>
          <div>
            <label for="type" class="form-label">Type</label>
            <select class="form-select" name="type">
                <option value="percent">Percent</option>
                <option value="fixed">Fixed</option>
            </select>
          </div>
          <div>
            <label for="expires_at" class="form-label">Birth Date</label>
            <input type="date" class="form-control" name="expires_at" >
          @if ($errors->has('expires_at'))
              <span class="text-danger">{{ $errors->first('expires_at') }}</span>
          @endif
          </div>
        <button type="submit" class="btn btn-primary">Create</button>
      </form>
</div>

@endsection
