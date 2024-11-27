<div class="row my-3">
    <input type="hidden" name="customer_id" id="customer" value="{{ $customer->id }}" />
    <div class="col-4">
        <label for="name">Name</label>
        <div class="input-group">
            <input class="form-control" type="text" name="name" value="{{ $customer->name }}" disabled>
        </div>
    </div>
    <div class="col-4">
        <label for="birth_date">Birth Date</label>
        <div class="input-group">
            <input class="form-control" type="text" name="birth_date"
                value="{{ \Carbon\Carbon::parse($customer->birth_date)->format("d/m/Y") }}" disabled>
        </div>
    </div>
    <div class="col-4">
        <label for="email">Email</label>
        <div class="input-group">
            <input class="form-control" type="text" name="email" value="{{ $customer->email }}" disabled>
        </div>
    </div>
</div>
