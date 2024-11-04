<div class="modal-body">
    <div class="row">
        <div class="row">
            @foreach ($tables as $item)
                <div class="col-md-6">
                    <div
                        class="card border {{ $item->availability == 'Available' ? 'border-success' : 'border-danger bg-danger-subtle' }} rounded-3">
                        <div class="card-body">
                            <div class="row align-content-center">
                                <div class="col-6">
                                    <!-- Inline Radios -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input table" type="radio" name="table"
                                            id="table{{ $item->id }}" value="{{ $item->id }}"
                                            data-name="{{ $item->name }}" {{ $table == $item->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="table{{ $item->id }}">
                                            <h5 class="card-title">{{ $item->name }} - {{ $item->capacity }} persons
                                            </h5>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="card-text">{{ $item->availability }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
