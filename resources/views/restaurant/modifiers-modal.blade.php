<div class="modal-body">
    <div class="row">
        <div class="row">
            @foreach ($modifiers as $item)
                <div class="col-md-12">
                    <div class="card border rounded-3">
                        <div class="card-body">
                            <div class="row align-content-center">
                                <div class="col-6">
                                    <!-- Inline Radios -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input modifier" type="checkbox" name="modifier"
                                            id="modifier{{ $item->modifier->id }}" value="{{ $item->modifier->id }}"
                                            data-name="{{ $item->modifier->name }}" data-meal="{{ $id }}"
                                            data-price="{{ $item->modifier->unit_price }}">
                                        <label class="form-check-label" for="modifier{{ $item->modifier->id }}">
                                            <h5 class="card-title">{{ $item->modifier->name }}</h5>
                                            <span>{{ $settings->currency }}
                                                {{ number_format($item->modifier->unit_price, 2) }}</span><br>
                                            <span class="small">{{ $item->modifier->description }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($modifiers->isEmpty())
                <div class="col-md-12">
                    <p class="fs-5 fw-light">
                        No Modifiers found for this meal category
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    var mealId = {{ $id }}
    $(document).on('click', '.modifier', function() {
        $('#loader').removeClass('d-none');

        var modifiers = [];

        var id = $(this).val();
        var meal = $(this).data('meal');
        var name = $(this).data('name');
        var price = $(this).data('price');
        if ($(this).is(':checked')) {
            modifiers.push({
                id: id,
                price: price,
            });
        } else {
            modifiers.slice(id, 1);
        }

        const itemIndex = cart.findIndex(item => item.id === meal);
        if (itemIndex !== -1) {
            cart[itemIndex].modifiers = modifiers;
            // Recalculate cart prices and update UI
            $('#loader').addClass('d-none');
            loadCart();
        }
    });

    $(document).ready(function() {
        const itemIndex = cart.findIndex(item => item.id === mealId);

        var selectedModifiers = cart[itemIndex].modifiers || [];
        selectedModifiers.forEach(element => {
            $(`#modifier${element.id}`).prop('checked', true);
        });
    });
</script>
