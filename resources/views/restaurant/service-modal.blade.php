<div class="modal-body">
    <form action="" id="serviceForm">
        <div class="row">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Service Rate</label>
                    <input type="number" step="any" min="0" name="" value="{{ $service ?? 0 }}"
                        id="service" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="">service Method</label>
                    <select name="" id="service_method" class="form-control">
                        <option value="precentage" {{ $service_method == 'precentage' ? 'selected' : '' }}>Precentage
                        </option>
                        <option value="amount" {{ $service_method == 'amount' ? 'selected' : '' }}>Solid Amount
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mt-3 mb-2">
                <div class="col text-end">
                    <button class="btn btn-primary">Apply</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#serviceForm').submit(function(e) {
        e.preventDefault();
        calVat()
    });

    function calVat() {
        service_val = $('#service').val();
        service_method = $('#service_method').val();
        $('#commonModal').modal('hide');
        loadCart()
    }
</script>
