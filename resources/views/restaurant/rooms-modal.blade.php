<div class="modal-body">
    <div class="row">
        <div class="row">
            @foreach ($rooms as $item)
                <div class="col-md-6">
                    <div class="card border rounded-3">
                        <div class="card-body">
                            <div class="row align-content-center">
                                <div class="col-12">
                                    <!-- Inline Radios -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input room" type="radio" name="room"
                                            id="room{{ $item->id }}" value="{{ $item->id }}"
                                            data-name="{{ $item->room->name }}"
                                            {{ $room == $item->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="room{{ $item->id }}">
                                            <h5 class="card-title">{{ $item->room->room_no }} - {{ $item->room->name }}
                                            </h5>
                                            <span>{{ $item->room->capacity }} persons</span>
                                        </label>
                                    </div>
                                </div>
                                {{-- <div class="col-6 text-end">
                                    <p class="card-text">{{ $item->room->status }}</p>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
