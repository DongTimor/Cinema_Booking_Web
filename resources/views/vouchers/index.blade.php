@extends('layouts.admin')

@section('styles')

<link rel="stylesheet" href="{{asset('css/voucher/voucher.css')}}">
@stop
@section('content')
<div class="d-flex justify-content-end p-3 row">
    <h2 class="col-11">Voucher</h2>
    <button type="button" class="btn btn-outline-primary col-1">
        <a href="{{route('vouchers.create')}}">
            Create
        </a>
    </button>
</div>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Value</th>
        <th>Description</th>
        <th>Code</th>
        <th>Expires at</th>
        <th>Action</th>
    </tr>
     @foreach ($vouchers as $item)
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
          <td>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('vouchers.edit', $item->id) }}">Edit</a></li>
                    <li>
                        <form action="{{ route('vouchers.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this voucher: {{ $item->code }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item">Delete</button>
                        </form>
                    </li>
                </ul>
            </div>
          </td>
        </tr>
        @endforeach
</table>

@endsection
