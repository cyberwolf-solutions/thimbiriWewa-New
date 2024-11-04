<form method="post" class="ajax-form" action="{{ route('purchases.payment.add') }}">
    @csrf
    <input type="hidden" name="id"value="{{ encrypt($data->id) }}">
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6 required">
                <label for="" class="form-label">Date</label>
                <input type="date" name="date" id="" class="form-control" data-provider="flatpickr"
                    data-date-format="{{ $settings->date_format }}"
                    data-deafult-date="{{ $is_edit ? $data->date : date($settings->date_format) }}" />
            </div>
            <div class="col-md-6 required">
                <label for="" class="form-label">Amount</label>
                <input type="number" step="any" name="amount" id="" class="form-control"
                    value="{{ $is_edit ? $data->amount : $due }}" />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="" class="form-label">Reference</label>
                <input type="text" name="reference" id="" class="form-control"
                    value="{{ $is_edit ? $data->reference : '' }}" />
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">Payment Receipt</label>
                @if ($is_edit && $data->receipt)
                    <a href="{{ $is_edit ? asset('storage/' . $data->receipt) : 'javascript:void(0)' }}"
                        class="small text-muted ps-2 text-decoration-underline">View File</a>
                @endif
                <input type="file" name="receipt" id="" class="form-control" />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="" cols="30" rows="3">{{ $is_edit ? $data->description : '' }}</textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
            </div>
        </div>
    </div>
</form>
