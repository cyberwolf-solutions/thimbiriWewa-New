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
                <form method="POST" class="ajax-form" action="{{ route('checkout.store') }}">
                    @csrf
                    {{-- @if ($is_edit)
                    @method('PATCH')
                    @endif --}}



                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Customer Name</label>
                            <select name="customer_id" class="form-control js-example-basic-single" id="customer-select"
                                required>
                                <option value="">Select...</option>
                                @foreach ($data as $record)
                                    @if ($record->customer)
                                        <option value="{{ $record->customer->id }}">{{ $record->customer->name }}</option>
                                    @endif
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
                            <input type="text" name="room_no" id="room-no" class="form-control" value=""
                                placeholder="Enter Room No" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Check In Date</label>
                            <input type="text" name="checkin" id="checkin" class="form-control" value=""
                                placeholder="Check In Date" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Check Out Date</label>
                            <input type="text" name="checkout" id="checkout" class="form-control" value=""
                                placeholder="Check In Date" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Total ammount (LKR.)</label>
                            <input type="text" name="total" id="total" class="form-control" value=""
                                placeholder="Total" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Payed amount (LKR.)</label>
                            <input type="text" name="payed" id="payed" class="form-control" value=""
                                placeholder="Payed" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Due amount (LKR.)</label>
                            <input type="text" name="due" id="due" class="form-control" value=""
                                placeholder="Due" required readonly />
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Additional payments</label>
                            <input type="text" name="additional" id="additional" class="form-control" value=""
                                placeholder="Additional" required readonly />
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="additional" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Additional" required></textarea>
                        </div>



                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Full due ammount</label>
                            <input type="text" name="fd" id="fd" class="form-control" value=""
                                placeholder="Full due" required readonly />
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Paying ammmount</label>
                            <input type="text" name="tot" id="tot" class="form-control" value=""
                                placeholder="Ammount" required />
                        </div>

                        <input type="hidden" name="checkincheckout_id" id="checkincheckout_id" readonly>

                    </div>




                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('checkout.index') }}'">Cancel</button>
                            <button class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="response"></div>


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

            $(document).ready(function() {
                $('#booking-room-select').change(function() {
                    var customerId = $('#customer-select').val();
                    var bookingId = $('#booking_id').val();


                    // Send AJAX request to fetch checkincheckout ID
                    $.ajax({
                        url: '/get-checkincheckout-id',
                        type: 'GET',
                        data: {
                            customer_id: customerId,
                            booking_id: bookingId
                        },
                        success: function(response) {
                            $('#checkincheckout_id').val(response.checkincheckout_id);
                            // alert(JSON.stringify(response));
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
            // var pvalue;






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
                var bookingId = $('#booking_id').val();

                // Send AJAX request to fetch paid and due amounts for the selected booking
                $.ajax({
                    url: '/get-booking-payment-details/' + bookingId,
                    type: 'GET',
                    success: function(response) {
                        $('#payed').val(response.payed);
                        // $('#due').val(response.due);

                        //   var   pvalue = parseFloat(response.payed);

                        calculateDue();
                        calculateFullDue();


                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });


            $('#booking-room-select').change(function() {
                var roomNo = $(this).find(':selected').data('room-no');
                $('#room-no').val(roomNo);
            });
            $('#booking-room-select').change(function() {
                var totalAmount = $(this).find(':selected').data('total-ammount');
                $('#total').val(totalAmount);
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
                // function calculateDue() {
                //     var total = parseFloat($('#total').val());
                //     var payed = parseFloat($('#payed').val());


                //     va due = total - payed;


                //     $('#due').val(due.toFixed(2));r
                // }


                $('#payed').on('input', function() {
                    // calculateDue();
                });


                // calculateDue();
            });




            //uuuu





            $('#customer-select').change(function() {
                var customerId = $(this).val();


                $.ajax({
                    url: '/get-customer-orders/' + customerId,
                    type: 'GET',
                    success: function(response) {
                        // Display the orders in an alert
                        // var orders = '';
                        // if (response.orders.length > 0) {
                        //     response.orders.forEach(function(order) {
                        //         orders += 'Order ID: ' + order.id + ', Type: ' + order
                        //             .type + '\n';
                        //     });
                        // } else {
                        //     orders = 'No orders found for this customer.';
                        // }





                        // Access orderIds from the response
                        // var orderIds = response.orderIds;
                        var unpaidOrders = response.unpaidOrders;

                        var totalSum = 0;

                        var unpaidOrdersDetails = '';
                        if (unpaidOrders.length > 0) {

                            unpaidOrders.forEach(function(order) {
                                var amount = parseFloat(order.total);
                                totalSum += amount;
                            });

                            unpaidOrdersDetails = totalSum;
                        }



                        $('#additional').val(totalSum);





                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });


            function calculateDue() {
                var total = parseFloat($('#total').val()) || 0;
                var payed = parseFloat($('#payed').val()) || 0;
                var due = total - payed;



                $('#due').val(due.toFixed(2));





            }

            // Function to calculate full due amount
            function calculateFullDue() {
                var additional = parseFloat($('#additional').val()) || 0;

                var due = parseFloat($('#due').val()) || 0;
                var fullDue = additional + due;

                $('#fd').val(fullDue.toFixed(2));



            }

            $('form.ajax-form').submit(function(event) {
                event.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (response.success) {

                            // window.location.href = response.redirect;

                            window.open(response.redirect, '_blank');

                            // Redirect the current page to checkout index after opening invoice
                            window.location.href = '{{ route('checkout.index') }}';







                        } else {

                            console.error(response.errors);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });




        });
    </script>
@endsection
