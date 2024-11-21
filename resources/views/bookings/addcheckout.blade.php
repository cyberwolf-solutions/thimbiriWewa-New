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
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Booking Id</label>
                            <input type="text" name="booking_id" id="booking_id" class="form-control"
                                placeholder="Room Facility" required readonly />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Room Facility</label>
                            <input type="text" name="room_facility" id="room-facility" class="form-control"
                                placeholder="Room Facility" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Room No</label>
                            <input type="text" name="room_no" id="room-no" class="form-control" value=""
                                placeholder="Enter Room No" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Check In Date</label>
                            <input type="text" name="checkin" id="checkin" class="form-control" value=""
                                placeholder="Check In Date" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Check Out Date</label>
                            <input type="text" name="checkout" id="checkout" class="form-control" value=""
                                placeholder="Check In Date" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Total Room Charge (LKR.)</label>
                            <input type="text" name="total" id="total" class="form-control" value=""
                                placeholder="Total" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Payed amount (LKR.)</label>
                            <input type="text" name="payed" id="payed" class="form-control" value=""
                                placeholder="Payed" required readonly hidden />
                        </div>
                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Due amount (LKR.)</label>
                            <input type="text" name="due" id="due" class="form-control" value=""
                                placeholder="Due" required readonly hidden />
                        </div>

                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Additional payments</label>
                            <input type="text" name="additional" id="additional" class="form-control" value=""
                                placeholder="Additional" required readonly hidden />
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="additional" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Additional" required></textarea>
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="payment-method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment-method" class="form-control" required>
                                <option value="">Select...</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="travel_agent">Travel Agent</option>
                            </select>
                        </div>




                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Full due ammount</label>
                            <input type="text" name="fd" id="fd" class="form-control" value=""
                                placeholder="Full due" readonly hidden />
                        </div>


                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Discount (%)</label>
                            <input type="text" name="dis" id="dis" class="form-control" value=""
                                placeholder="Discount" required />
                        </div>


                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Sub Total</label>
                            <input type="text" name="stot" id="stot" class="form-control" value=""
                                placeholder="Discount" required readonly hidden />
                        </div>


                        <div class="col-md-6 mb-3 required d-none">
                            <label for="" class="form-label">Paying ammmount</label>
                            <input type="text" name="tot" id="tot" class="form-control" value=""
                                placeholder="Ammount" />
                        </div>

                        <input type="hidden" name="checkincheckout_id" id="checkincheckout_id" readonly>

                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered" id="room-details-table">
                            <thead>
                                <tr>
                                    <th>Room No</th>
                                    <th>Room Facility</th>
                                    {{-- <th>Check In</th>
                                    <th>Check Out</th> --}}
                                    <th>Total Room Charge (LKR)</th>

                                    {{-- <th>Due Amount (LKR)</th> --}}
                                    <th>Additional Payments (LKR)</th>
                                    <th>Paid (LKR)</th>
                                    <th>Due (LKR)</th>
                                    <th>Sub Total (LKR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamically added room details will appear here -->
                            </tbody>
                        </table>

                        <input type="hidden" name="rooms_data" id="rooms-data">
                    </div>


                    <div class="col-md-6 mb-3 required ">
                        <label for="" class="form-label">Total (LKR)</label>
                        <input type="text" name="Total" id="Total" class="form-control" value=""
                            placeholder="Ammount" required />
                    </div>


                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('checkout.index') }}'">Cancel</button>
                            <button class="btn btn-primary" id="create-btn">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="response"></div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <Script>
        $(document).ready(function() {
            // Function to calculate and update the due amount, subtotal, and total
            function updateDiscountedPrices() {
                var discountPercentage = parseFloat($('#dis').val()) || 0;
                var totalSum = 0; // To store the sum of all subtotals

                $('#room-details-table tbody tr').each(function() {
                    var totalRoomCharge = parseFloat($(this).find('td:eq(2)').text()) || 0;
                    var additionalPayments = parseFloat($(this).find('td:eq(3)').text()) || 0;
                    var paidAmount = parseFloat($(this).find('td:eq(4)').text()) || 0;

                    // Calculate subtotal after applying discount
                    var subtotal = (totalRoomCharge + additionalPayments) * (1 - discountPercentage / 100);
                    var dueAmount = subtotal - paidAmount;

                    // Handle potential negative due amounts
                    dueAmount = dueAmount < 0 ? 0 : dueAmount;

                    // Update the Due and Subtotal fields in the table
                    $(this).find('td:eq(5)').text(dueAmount.toFixed(2)); // Update Due Amount
                    $(this).find('td:eq(6)').text(subtotal.toFixed(2)); // Update Subtotal

                    // Add the subtotal to the total sum
                    totalSum += subtotal;
                });

                // Update the Total field with the sum of all subtotals
                $('#Total').val(totalSum.toFixed(2));
            }

            // Event listener for discount input
            $('#dis').on('input', function() {
                updateDiscountedPrices(); // Recalculate prices whenever discount changes
            });


            // Handle adding a new room (existing code for adding a room)
            $('#booking-room-select').change(function() {
                setTimeout(function() {
                    var selectedRoom = $('#booking-room-select').find(':selected');
                    var roomNo = selectedRoom.data('room-no');
                    var roomFacility = $('#room-facility').val();
                    var totalAmount = parseFloat($('#total').val()) || 0;
                    var paidAmount = parseFloat($('#payed').val()) || 0;
                    var additionalPayments = parseFloat($('#additional').val()) || 0;


                    var roomExists = false;
                $('#room-details-table tbody tr').each(function() {
                    if ($(this).find('td:eq(0)').text() == roomNo) {
                        roomExists = true;
                        return false; // Exit loop
                    }
                });

                if (roomExists) {
                    alert('This room is already added to the table. Please remove it first to add again.');
                    return; // Prevent adding duplicate room
                }



                    var dueAmount = totalAmount - paidAmount + additionalPayments;
                    var subtotal = totalAmount +
                        additionalPayments; // Initial subtotal before discount

                    // Create a new row with the selected room details
                    var rowHtml = `<tr>
            <td>${roomNo}</td>
            <td>${roomFacility}</td>
            <td>${totalAmount.toFixed(2)}</td>
            <td>${additionalPayments.toFixed(2)}</td>
            <td>${paidAmount.toFixed(2)}</td>
            <td>${dueAmount.toFixed(2)}</td>
            <td>${subtotal.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger remove-room">Remove</button></td>
        </tr>`;

                    // Append the new row to the table
                    $('#room-details-table tbody').append(rowHtml);

                    // Clear form fields
                    $('#room-facility').val('');
                    $('#total').val('');
                    $('#payed').val('');
                    $('#additional').val('');

                    // Recalculate discounted prices after adding the new row
                    updateDiscountedPrices();
                }, 2000); // 2000ms = 2 seconds
            });

            // Handle removing a room from the table
            $('#room-details-table').on('click', '.remove-room', function() {
                $(this).closest('tr').remove();
                updateDiscountedPrices(); // Recalculate after removing a row
            });

            // When the Create button is clicked, store table data and alert it
            $('#create-btn').click(function() {
                var tableData = [];

                // Loop through each row in the table and collect the data
                $('#room-details-table tbody tr').each(function() {
                    var rowData = {
                        roomNo: $(this).find('td:eq(0)').text(),
                        roomFacility: $(this).find('td:eq(1)').text(),
                        totalRoomCharge: $(this).find('td:eq(2)').text(),
                        additionalPayments: $(this).find('td:eq(3)').text(),
                        paidAmount: $(this).find('td:eq(4)').text(),
                        dueAmount: $(this).find('td:eq(5)').text(),
                        subtotal: $(this).find('td:eq(6)').text()
                    };
                    tableData.push(rowData);
                });

                // Store the table data in the hidden input field
                $('#rooms-data').val(JSON.stringify(tableData));

                // Alert the table data (you can also log it to the console if you prefer)
                // alert('Table Data: ' + JSON.stringify(tableData));
            });

            updateDiscountedPrices();
        });
    </Script>



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



            // $('#booking-room-select').change(function() {
            //     var bookingId = $('#booking_id').val();

            //     // Send AJAX request to fetch paid and due amounts for the selected booking
            //     $.ajax({
            //         url: '/get-booking-payment-details/' + bookingId,
            //         type: 'GET',
            //         success: function(response) {
            //             $('#payed').val(response.payed);
            //             $('#total').val(response.total);

            //             //   var   pvalue = parseFloat(response.payed);

            //             calculateDue();
            //             calculateFullDue();


            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // });



            $('#booking-room-select').change(function() {
                var bookingId = $('#booking_id').val();

                // Delay the retrieval of roomId by 4 seconds (4000 milliseconds)
                setTimeout(function() {
                    var roomId = $('#room-no').val();

                    // alert(roomId); // Display the roomId value in an alert

                    // Send AJAX request to fetch paid and due amounts for the selected booking
                    $.ajax({
                        url: '/get-booking-payment-details/' + bookingId + '/' + roomId,
                        type: 'GET',
                        success: function(response) {
                            $('#payed').val(response.payed);
                            $('#total').val(response.total);

                            calculateDue();
                            calculateFullDue();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }, 1000); // 4 seconds delay
            });




            $('#booking-room-select').change(function() {
                var roomNo = $(this).find(':selected').data('room-no');
                $('#room-no').val(roomNo);
            });
            $('#booking-room-select').change(function() {
                var totalAmount = $(this).find(':selected').data('total-ammount');
                // $('#total').val(totalAmount);
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



                $('#payed').on('input', function() {
                    // calculateDue();
                });


                // calculateDue();
            });




            //uuuu





            let customerSelected = false;
            let roomSelected = false;

            $('#customer-select').change(function() {
                customerSelected = true;
                triggerCustomerOrders();
            });

            $('#booking-room-select').change(function() {
                roomSelected = true;
                triggerCustomerOrders();
            });

            function triggerCustomerOrders() {
                if (customerSelected && roomSelected) {
                    var customerId = $('#customer-select').val();
                    var roomId = $('#room-no').val();

                    if (customerId && roomId) {
                        $.ajax({
                            url: '/get-customer-orders/' + customerId + '/' + roomId,
                            type: 'GET',
                            success: function(response) {
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
                    }
                }
            }



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


            function calculateDiscountedSubtotal() {
                var total = parseFloat($('#total').val()) || 0;
                var additional = parseFloat($('#additional').val()) || 0;
                var payed = parseFloat($('#payed').val()) || 0;
                var discountPercentage = parseFloat($('#dis').val()) || 0;

                // Calculate discounted amount
                var discountAmount = (total + additional) * (discountPercentage / 100);
                var discountedTotal = (total + additional) - discountAmount;

                // Calculate subtotal after subtracting the payed amount
                var subtotal = discountedTotal - payed;

                $('#stot').val(subtotal.toFixed(2));
            }

            $('#dis').on('input', calculateDiscountedSubtotal);
            $('#total, #additional, #payed').on('input', calculateDiscountedSubtotal);

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
