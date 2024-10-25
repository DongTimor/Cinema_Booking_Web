@extends('layouts.admin')

@section('styles')

<link rel="stylesheet" href="{{asset('css/voucher/voucher.css')}}">
@stop
@section('content')

<table class="table">
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Value</th>
        <th>Description</th>
        <th>Code</th>
        <th>Expires at</th>
    </tr>
     @foreach ($voucherStock as $item)
        <tr>
          <td>
            {{ $item->id }}
          </td>
          <td>
            {{ $item->type }}
          </td>
          <td>
            {{ $item->type == 'percent' ? $item->value . '%': number_format($item->value) . 'VNĐ' }}
          </td>
          <td>
            {{ $item->description }}
          </td>
          <td class="text-uppercase">
            {{ $item->code }}
          </td>
          <td>
            {{ \Carbon\Carbon::parse($item->expires_at)->format('d/m/Y') }}
          </td>
        </tr>
        @endforeach
</table>

@endsection
