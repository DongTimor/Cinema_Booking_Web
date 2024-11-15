@extends("layouts.admin")

@section("content")
    <div class="d-flex justify-content-end p-3">
        <h2 class="col-11">Voucher</h2>
        <a class="btn btn-outline-success my-auto" href="{{ route("vouchers.create") }}" role="button"><i
                class="fas fa-plus"></i>
            Create</a>
    </div>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Value</th>
            <th>Description</th>
            <th>Code</th>
            <th>Points Required</th>
            <th>Expires at</th>
            <th class="text-center">Action</th>
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
                    {{ $item->type == "percent" ? $item->value . "%" : number_format($item->value) . "VNĐ" }}
                </td>
                <td>
                    {{ $item->description }}
                </td>
                <td class="text-uppercase">
                    {{ $item->code }}
                </td>
                <td>
                    {{ $item->points_required}}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($item->expires_at)->format("d/m/Y") }}
                </td>
                <td>
                    <ul class="d-flex justify-content-center mb-0">
                        <li>
                            <a class="btn btn-outline-primary mr-2" href="{{ route("vouchers.edit", $item->id) }}"
                                role="button"><i class="fas fa-tools"></i> Edit</a>
                        </li>
                        <li>
                            <form action="{{ route("vouchers.destroy", $item->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this voucher: {{ $item->code }}?');">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-outline-danger"><i class="far fa-trash-alt"></i>
                                    Delete</button>
                            </form>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
