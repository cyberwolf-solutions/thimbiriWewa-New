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
                                    data-booking-id="{{ $item->bookings->id ?? '' }}"  
                                    data-customer-id="{{ $item->bookings->customerss->id ?? '' }}"  
                                    {{ $room == $item->id ? 'checked' : '' }}>
                                
                                    <label class="form-check-label" for="room{{ $item->id }}">
                                        <h5 class="card-title">{{ $item->room->room_no }} - {{ $item->room->name }}</h5>
                                        <span style="color: gray">{{ $item->room->capacity }} persons</span>
                                        <p style="margin-top: 3px">Customer Name:
                                            @if ($item->bookings)
                                                {{ $item->bookings->customerss->name ?? 'No customer' }}
                                            @else
                                                No booking
                                            @endif
                                        </p>
                                        <p style="margin-top: -13px">Customer contact:
                                            @if ($item->bookings)
                                                {{ $item->bookings->customerss->contact ?? 'No customer' }}
                                            @else
                                                No booking
                                            @endif
                                        </p>
                                        <p style="margin-top: -13px">Booking ID:
                                            @if ($item->bookings)
                                                {{ $item->bookings->id ?? 'No booking' }}
                                            @else
                                                No booking
                                            @endif
                                        </p>
                                        <p style="margin-top: -13px">Customer ID:
                                            @if ($item->bookings)
                                                {{ $item->bookings->customerss->id ?? 'No customer' }}
                                            @else
                                                No customer
                                            @endif
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        

        </div>
    </div>
</div>
