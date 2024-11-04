@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-sm-0">{{ $title }}</h3>

                    <ol class="breadcrumb m-0 mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}">
                                @if (!$breadcrumb['active'])
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                                @else
                                    {{ $breadcrumb['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="page-title-right">
                    {{-- Add Buttons Here --}}
                    {{-- <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                        title="Create">
                        <i class="ri-add-line fs-5"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form" action="{{ route('checkin.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Customer Name</label>
                            <select name="customer_id" class="form-control js-example-basic-single" id="customer-select"
                                required>

                                <option value="">Select...</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Booking Rooms</label>
                            <select name="booking_room_id" class="form-control" id="booking-room-select" required>
                                <option value="">Select...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Booking Id</label>
                            <input type="text" name="booking_id" id="booking_id" class="form-control"
                                placeholder="Room Facility" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room Facility</label>
                            <input type="text" name="room_facility" id="room-facility" class="form-control"
                                placeholder="Room Facility" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room No</label>
                            <input type="text" name="room_no" id="room-no" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Enter Room No" required
                                readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Check In Date</label>
                            <input type="text" name="checkin" id="checkin" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Check In Date" required
                                readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Check Out Date</label>
                            <input type="text" name="checkout" id="checkout" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Check In Date" required
                                readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room Price For One Day (LKR.)</label>
                            <input type="text" name="total1" id="total1" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Total" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Total ammount for the days (LKR.)</label>
                            <input type="text" name="total" id="total" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Total" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Payed ammount (LKR.)</label>
                            <input type="text" name="payed" id="payed" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Payed" required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Due ammount (LKR.)</label>
                            <input type="text" name="due" id="due" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Due" required readonly />
                        </div>
                    </div>




                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('rooms.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <div id="responseContainer"></div> --}}


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





    <script>
        $(document).ready(function() {
            $('#customer-select').change(function() {
                var customerId = $(this).val();

                // Send AJAX request to fetch booking rooms
                $.ajax({
                    url: '/get-booking-rooms/' + customerId,
                    type: 'GET',
                    success: function(response) {
                        $('#booking-room-select').empty();
                        $('#responseContainer').html(JSON.stringify(response));

                        $('#booking-room-select').append($('<option>', {
                            value: '',
                            text: 'Select Room'
                        }));


                        if (response.length > 0) {
                            $.each(response, function(index, booking) {
                                $.each(booking.rooms, function(index, room) {
                                    $('#booking-room-select').append($(
                                        '<option>', {
                                            value: room.id,
                                            text: room.name,
                                            'data-total-ammount': room
                                                .price,
                                            'data-room-no': room
                                                .room_no,
                                            'data-facility-id': room
                                                .RoomFacility_id,
                                            'data-checkin': booking
                                                .checkin,
                                            'data-checkout': booking
                                                .checkout,
                                            'data-id': booking
                                                .id
                                        }));
                                });
                            });
                        } else {
                            // If no booking rooms found
                            $('#booking-room-select').append($('<option>', {
                                value: '',
                                text: 'No booking rooms available'
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });



            $('#booking-room-select').change(function() {
                var facilityId = $(this).find(':selected').data('facility-id');

                // Send AJAX request to fetch room facility
                $.ajax({
                    url: '/get-room-facility/' + facilityId,
                    type: 'GET',
                    success: function(response) {
                        $('#room-facility').val(response.name);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
            $('#booking-room-select').change(function() {
                var Id = $(this).find(':selected').data('id');
                $('#booking_id').val(Id);
            });
            $('#booking-room-select').change(function() {
                var roomNo = $(this).find(':selected').data('room-no');
                $('#room-no').val(roomNo);
            });
            $('#booking-room-select').change(function() {
                var totalAmount = $(this).find(':selected').data('total-ammount');
                $('#total1').val(totalAmount);
            });
            $('#booking-room-select').change(function() {
                var checkinDate = $(this).find(':selected').data('checkin');
                // alert('Check-in Date:', checkinDate);
                $('#checkin').val(checkinDate);
            });
            $('#booking-room-select').change(function() {
                var checkiOutDate = $(this).find(':selected').data('checkout');
                // alert('Check-in Date:', checkinDate);
                $('#checkout').val(checkiOutDate);
            });








            $(document).ready(function() {
                // Function to calculate due amount
                function calculateDue() {
                    var total = parseFloat($('#total').val());
                    var payed = parseFloat($('#payed').val());


                    var due = total - payed;


                    $('#due').val(due.toFixed(2));
                }


                $('#payed').on('input', function() {
                    calculateDue();
                });


                // calculateDue();
            });



            $('#booking-room-select').change(function() {
                var totalAmountPerDay = parseFloat($(this).find(':selected').data('total-ammount'));
                var checkinDate = $(this).find(':selected').data('checkin');
                var checkoutDate = $(this).find(':selected').data('checkout');

                $('#checkin').val(checkinDate);
                $('#checkout').val(checkoutDate);


                var checkin = new Date(checkinDate);
                var checkout = new Date(checkoutDate);


                var differenceMs = checkout - checkin;

                var differenceDays = differenceMs / (1000 * 60 * 60 * 24);

                var totalAmountForDays = totalAmountPerDay * differenceDays;

                // Set the value of the total amount field
                // $('#total').val(totalAmountForDays.toFixed(2));
                $('#total').val(Math.round(totalAmountForDays));




                // alert('The difference between check-in and check-out dates is ' + differenceDays +
                // ' days.');
            });


        });
    </script>
@endsection
