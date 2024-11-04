<div class="modal-body">
    <div class="row">
        <div class="row">
            <div class="col-12">
                <label for="">Select a Guest</label>
                <select name="" id="customer" class="form-control js-example-basic-single">
                    <option value="0" {{ $customer == 0 ? 'selected' : '' }} data-name="Walking Customer">Walking
                        Customer</option>
                    @foreach ($customers as $item)
                        <option value="{{ $item->id }}" {{ $customer == $item->id ? 'selected' : '' }}
                            data-name="{{ $item->name }}">{{ $item->name }} |
                            {{ $item->contact }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <a href="javascript:void(0)" class="link-info" data-ajax-popup="true" data-title="Add Customer"
                data-size="lg" data-url="{{ route('restaurant.customer-add') }}">Need to add a new customer? Click
                Here!</a>
        </div>
    </div>
</div>
