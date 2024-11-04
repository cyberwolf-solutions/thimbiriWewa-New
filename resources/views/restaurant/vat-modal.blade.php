<div class="modal-body">
    <form action="" id="vatForm">
        <div class="row">
            <div class="row">
                <div class="col-md-6">
                    <label for="">VAT Rate</label>
                    <input type="number" step="any" min="0" name="" value="{{ $vat ?? 0 }}"
                        id="vat" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="">VAT Method</label>
                    <select name="" id="vat_method" class="form-control">
                        <option value="precentage" {{ $vat_method == 'precentage' ? 'selected' : '' }}>Precentage
                        </option>
                        <option value="amount" {{ $vat_method == 'amount' ? 'selected' : '' }}>Solid Amount
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
    $('#vatForm').submit(function(e) {
        e.preventDefault();
        calVat()
    });

    function calVat() {
        vat_val = $('#vat').val();
        vat_method = $('#vat_method').val();
        $('#commonModal').modal('hide');
        loadCart()
    }
</script>
