<div class="modal-body">
    <form action="" id="discountForm">
        <div class="row">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Discount Rate</label>
                    <input type="number" step="any" min="0" name="" id="discount" class="form-control"
                        value="{{ $discount ?? 0 }}">
                </div>
                <div class="col-md-6">
                    <label for="">Discount Method</label>
                    <select name="" id="discount_method" class="form-control">
                        <option value="precentage" {{ $discount_method == 'precentage' ? 'selected' : '' }}>Precentage
                        </option>
                        <option value="amount" {{ $discount_method == 'amount' ? 'selected' : '' }}>Solid Amount
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mt-3 mb-2">
                <div class="col text-end">
                    <button class="btn btn-primary" type="submit">Apply</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#discountForm').submit(function(e) {
        e.preventDefault();
        calDiscount()
    });

    function calDiscount() {
        discount_val = $('#discount').val();
        discount_method = $('#discount_method').val();

        $('#commonModal').modal('hide');
        loadCart()
    }
</script>
