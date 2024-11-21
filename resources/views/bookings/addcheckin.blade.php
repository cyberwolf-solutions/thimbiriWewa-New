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

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                {{-- <form method="POST" class="ajax-form" action=" {{ route('checkin.store') }}"> --}}
                <form method="POST" class="ajax-form"
                    action="{{ $is_edit ? route('checkin.update', $data->id) : route('checkin.store') }}">

                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Customer </label>
                            <select name="customer_id" class="form-control js-example-basic-single" id="customer-select"
                                required @if ($is_edit) disabled @endif>
                                <option value="">Select...</option>
                                @foreach ($customers as $customer)
                                    @php
                                        $booking = $customer->bookings->where('status', 'OnGoing')->first();
                                    @endphp
                                    <option value="{{ $customer->id }}" data-booking-id="{{ $booking ? $booking->id : '' }}"
                                        @if ($is_edit && $data->customer_id == $customer->id) selected @endif>
                                        {{ $customer->name }} | {{ $customer->contact }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Boarding Type</label>
                            <select name="bordingtype" class="form-control" id="boarding_type" required
                                @if ($is_edit) disabled @endif>
                                <option value="">Select...</option>
                                @foreach ($boarding as $boardings)
                                    <option value="{{ $boardings->id }}" data-price="{{ $boardings->price }}"
                                        @if ($is_edit && $data->boardingtype == $boardings->id) selected @endif>
                                        {{ $boardings->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Boarding Price (One Day)</label>
                            <input type="text" name="boarding_price" id="boarding_price" class="form-control"
                                placeholder="Boarding Price"
                                value="{{ $is_edit ? $boarding->firstWhere('id', $data->boardingtype)->price ?? '' : '' }}"
                                required readonly />
                        </div>
                        @if (!$is_edit)
                            <div class="col-md-6 mb-3 required ">
                                <label for="" class="form-label">Booking Id</label>
                                <input type="text" name="booking_id" id="booking_id" class="form-control"
                                    placeholder="Room Facility" required readonly />
                            </div>
                        @endif
                        @if (!$is_edit)
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Booking Rooms</label>
                                <select name="booking_room_id" class="form-control" id="booking-room-select" required>
                                    <option value="">Select...</option>
                                </select>
                            </div>
                        @endif

                        @if ($is_edit)
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Booking Id</label>
                                <input type="text" name="booking_id" id="booking_id" class="form-control"
                                    placeholder="Room Facility" required readonly />
                            </div>
                        @endif

                        @if ($is_edit)
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Room Facility</label>
                                <input type="text" name="room_facility" id="room-facility" class="form-control"
                                    placeholder="Room Facility" required readonly />
                            </div>
                        @endif

                        @if ($is_edit)
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Room No</label>
                                <input type="text" name="room_no" id="room-no" class="form-control"
                                    value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Enter Room No" required
                                    readonly />
                            </div>
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Check In Date</label>
                                <input type="text" name="checkin" id="checkin" class="form-control"
                                    value="{{ $is_edit ? $data->checkin : '' }}" placeholder="Check In Date" required
                                    readonly />
                            </div>
                            {{-- <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Check Out Date</label>
                            <input type="text" name="checkout" id="checkout" class="form-control"
                                value="{{ $is_edit ? $data->checkout : '' }}" placeholder="Check In Date" required
                                readonly />
                        </div> --}}
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Check Out Date</label>
                                <input type="text" name="checkout" id="checkout" class="form-control"
                                    value="{{ $is_edit ? $data->checkout : '' }}" placeholder="Check Out Date" required
                                    @if ($is_edit) value="{{ $data->checkout }}"
                                @else 
                                    readonly @endif />
                            </div>

                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Boarding Type</label>
                                <select name="bordingtype" class="form-control" id="boarding_type" required
                                    @if ($is_edit) disabled @endif>
                                    <option value="">Select...</option>
                                    @foreach ($boarding as $boardings)
                                        <option value="{{ $boardings->id }}" data-price="{{ $boardings->price }}"
                                            @if ($is_edit && $data->boardingtype == $boardings->id) selected @endif>
                                            {{ $boardings->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Boarding Price (One Day)</label>
                                <input type="text" name="boarding_price" id="boarding_price" class="form-control"
                                    placeholder="Boarding Price"
                                    value="{{ $is_edit ? $boarding->firstWhere('id', $data->boardingtype)->price ?? '' : '' }}"
                                    required readonly />
                            </div>
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Boarding Price for the Stay</label>
                                <input type="text" name="boarding_price_sum" id="boarding_price_sum"
                                    class="form-control" placeholder="Boarding Price" required readonly />
                            </div>
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Room Price For One Day (LKR.)</label>
                                <input type="text" name="total1" id="total1" class="form-control"
                                    value="{{ $is_edit ? $roomPrice : '' }}" placeholder="Total" required readonly />
                            </div>

                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Total charge for the days (LKR.)</label>
                                <input type="text" name="total" id="total" class="form-control"
                                    placeholder="Total" required readonly />
                            </div>

                            {{-- <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Full Charge</label>
                            <input type="text" name="full" id="full" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Total" required readonly />
                        </div> --}}


                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Payed ammount (LKR.)</label>
                                <input type="text" name="payed" id="payed" class="form-control"
                                    value="{{ $is_edit ? $data->paid_amount : '' }}" placeholder="Payed"
                                    @if ($is_edit) readonly @endif required />
                            </div>
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Due ammount (LKR.)</label>
                                <input type="text" name="due" id="due" class="form-control"
                                    placeholder="Due" required readonly />
                            </div>
                        @endif
                    </div>




                    @if (!$is_edit)
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3" id="rooms-table">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Room Facility</th>
                                        <th>Check-In Date</th>
                                        <th>Check-Out Date</th>
                                        <th>Price (LKR.)</th>
                                        <th>Boarding Price for Stay</th>
                                        <th>Total Charge</th>
                                        <th>Advance Amount</th>
                                        <th>Due Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Room rows will be appended here dynamically -->
                                </tbody>
                            </table>

                            <input type="hidden" name="rooms_data" id="rooms-data">
                        </div>


                        <div class="col-md-6 mb-3 required">
                            <label for="totalSum" class="form-label">Total Sum of Charges (LKR.)</label>
                            <input type="text" name="totalSum" id="totalSum" class="form-control"
                                placeholder="Total Charges" readonly />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="dueSum" class="form-label">Total Due Amount (LKR.)</label>
                            <input type="text" name="dueSum" id="dueSum" class="form-control"
                                placeholder="Total Due" readonly />
                        </div>
                    @endif

            </div>




            <div class="row mb-3">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-light me-2"
                        onclick="window.location='{{ route('checkin.index') }}'">Cancel</button>
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
            $('#customer-select').on('change', function() {
                var bookingId = $(this).find(':selected').data('booking-id');
                $('#booking_id').val(bookingId ? bookingId : '');
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Disable the Booking Room Selector initially
            const boardingType = $('#boarding_type').val();
            if (!boardingType) {
                $('#booking-room-select').prop('disabled', true);
            }

            // Enable or disable the Booking Room Selector based on Boarding Type selection
            $('#boarding_type').change(function() {
                const selectedBoardingType = $(this).val();
                if (selectedBoardingType) {
                    // Enable the Booking Room Selector
                    $('#booking-room-select').prop('disabled', false);
                } else {
                    // Disable the Booking Room Selector
                    $('#booking-room-select').prop('disabled', true);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Add a new room to the table and update the dropdown
            $('#booking-room-select').change(function() {
                const selectedOption = $(this).find(':selected');
                const roomId = selectedOption.val(); // Room ID
                var roomNo = selectedOption.data('room-no'); // Room Name
                const facilityId = selectedOption.data('facility-id');
                const checkin = selectedOption.data('checkin');
                const checkout = selectedOption.data('checkout');
                const roomPrice = parseFloat(selectedOption.data('total-ammount')) || 0;
                const boardingPrice = parseFloat($('#boarding_price').val()) || 0;

                if ($('#rooms-table tbody').find(`tr[data-room-id="${roomId}"]`).length > 0) {
                    alert("This room has already been added.");
                    return; // Exit function to prevent adding the same room again
                }


                if (roomNo && checkin && checkout) {
                    const checkinDate = new Date(checkin);
                    const checkoutDate = new Date(checkout);
                    const days = Math.ceil((checkoutDate - checkinDate) / (1000 * 3600 * 24));
                    const totalBoardingPrice = days * boardingPrice;
                    const totalCharge = roomPrice + totalBoardingPrice;

                    // Initialize paid amount and due amount
                    const paidAmount = parseFloat($('#payed').val()) || 0;
                    const dueAmount = totalCharge - paidAmount;




                    // Append data to the table
                    $.ajax({
                        url: '/get-room-facility/' + facilityId,
                        type: 'GET',
                        success: function(response) {
                            $('#rooms-table tbody').append(`
                                <tr data-room-id="${roomId}">
                                    <td>${roomNo}</td>
                                    <td>${response.name}</td>
                                    <td>${checkin}</td>
                                    <td>${checkout}</td>
                                    <td>${roomPrice.toFixed(2)}</td>
                                    <td>${totalBoardingPrice.toFixed(2)}</td>
                                    <td>${totalCharge.toFixed(2)}</td>
                                    <td><input type="number" class="form-control paid-amount" value="${paidAmount.toFixed(2)}" step="0.01" /></td>
                                    <td class="due-amount">${dueAmount.toFixed(2)}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-room">Remove</button></td>
                                </tr>
                            `);

                            // Remove the selected room from the dropdown
                            //  selectedOption.remove();

                            calculateTotalChargesAndDue();

                            // Recalculate the total due after adding a new room
                            calculateTotalDue();
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            // Remove room from the table and add it back to the dropdown
            $('#rooms-table').on('click', '.remove-room', function() {
                const row = $(this).closest('tr');
                const roomId = row.data('room-id'); // Get the room ID from the row data
                const roomNo = row.find('td:eq(0)').text(); // Get the room number (room name) from the row

                row.remove();
                calculateTotalChargesAndDue();
                // Recalculate the total due
                calculateTotalDue();

            });





            function calculateTotalChargesAndDue() {
                let totalCharges = 0;
                let totalDue = 0;

                $('#rooms-table tbody tr').each(function() {
                    totalCharges += parseFloat($(this).find('td:eq(6)').text()) || 0; // Total Charge column
                    totalDue += parseFloat($(this).find('.due-amount').text()) || 0; // Due Amount column
                });

                // Update the fields
                $('#totalSum').val(totalCharges.toFixed(2));
                $('#dueSum').val(totalDue.toFixed(2));
            }

            $(document).ready(function() {
                $('#rooms-table').on('input', '.paid-amount', function() {
                    const row = $(this).closest('tr');
                    const totalCharge = parseFloat(row.find('td:eq(6)').text()) || 0;
                    const paidAmount = parseFloat($(this).val()) || 0;
                    const newDueAmount = totalCharge - paidAmount;

                    row.find('.due-amount').text(newDueAmount.toFixed(2));

                    calculateTotalChargesAndDue();
                });

                $('#rooms-table').on('click', '.remove-room', function() {
                    $(this).closest('tr').remove();
                    calculateTotalChargesAndDue();
                });

                // Call calculation function after appending new room row
                $('#booking-room-select').change(function() {
                    // Your existing code to append a new row
                    calculateTotalChargesAndDue(); // Update the totals after adding a row
                });
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to collect table data and update the hidden input field
            function updateRoomsData() {
                const tableData = [];

                $('#rooms-table tbody tr').each(function() {
                    const row = $(this);
                    const rowData = {
                        roomId: row.data('room-id'),
                        roomNo: row.find('td:eq(0)').text(),
                        facility: row.find('td:eq(1)').text(),
                        checkin: row.find('td:eq(2)').text(),
                        checkout: row.find('td:eq(3)').text(),
                        price: parseFloat(row.find('td:eq(4)').text()) || 0,
                        boardingPrice: parseFloat(row.find('td:eq(5)').text()) || 0,
                        totalCharge: parseFloat(row.find('td:eq(6)').text()) || 0,
                        paidAmount: parseFloat(row.find('.paid-amount').val()) || 0,
                        dueAmount: parseFloat(row.find('.due-amount').text()) || 0,
                    };
                    tableData.push(rowData);
                });

                // Update the hidden input field
                $('#rooms-data').val(JSON.stringify(tableData));
            }

            // Function to handle form submission
            $('.ajax-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from actually submitting

                // Update the hidden input field with the latest table data
                updateRoomsData();

            });

            // Call updateRoomsData whenever the table changes
            $('#rooms-table').on('input', '.paid-amount', updateRoomsData);
            $('#rooms-table').on('click', '.remove-room', updateRoomsData);
            $('#booking-room-select').change(function() {
                // Your existing logic for adding a new row
                updateRoomsData();
            });
        });
    </script>






    <script>
        document.getElementById('boarding_type').addEventListener('change', function() {
            // Get the selected option
            var selectedOption = this.options[this.selectedIndex];

            // Get the price from the data-price attribute
            var price = selectedOption.getAttribute('data-price');

            // Set the price to the boarding_price input field
            document.getElementById('boarding_price').value = price ? price : '';
            calculateTotalPrice();
        });


        document.getElementById('checkin').addEventListener('change', calculateTotalPrice);
        document.getElementById('checkout').addEventListener('change', calculateTotalPrice);



        function calculateTotalPrice() {
            var checkinDate = new Date(document.getElementById('checkin').value);
            var checkoutDate = new Date(document.getElementById('checkout').value);
            var boardingPrice = parseFloat(document.getElementById('boarding_price').value) || 0;
            var totalAmountPerDay = parseFloat(document.getElementById('total1').value) || 0;
            var paidAmount = parseFloat(document.getElementById('payed').value) || 0;

            if (checkinDate && checkoutDate && boardingPrice && checkoutDate > checkinDate) {
                // Calculate the difference in days
                var timeDifference = checkoutDate - checkinDate;
                var days = Math.ceil(timeDifference / (1000 * 3600 * 24));

                // Calculate the total boarding price and total room price for the stay
                var boardingPriceSum = days * boardingPrice;
                var roomTotalPrice = days * totalAmountPerDay;

                // Set the total prices in the respective fields
                document.getElementById('boarding_price_sum').value = boardingPriceSum.toFixed(2);

                // Calculate final total charge for the stay
                var finalTotal = boardingPriceSum + roomTotalPrice;
                document.getElementById('total').value = finalTotal.toFixed(2);

                // Calculate the due amount
                var dueAmount = finalTotal - paidAmount;
                document.getElementById('due').value = dueAmount.toFixed(2);
            } else {
                // Clear the total prices if dates are invalid or incomplete
                document.getElementById('boarding_price_sum').value = '';
                document.getElementById('total').value = '';
                document.getElementById('due').value = '';
            }
        }



        document.addEventListener('DOMContentLoaded', function() {
            // Set the boarding type event listener
            document.getElementById('boarding_type').addEventListener('change', function() {
                // Get the selected option
                var selectedOption = this.options[this.selectedIndex];

                // Get the price from the data-price attribute
                var price = selectedOption.getAttribute('data-price');

                // Set the price to the boarding_price input field
                document.getElementById('boarding_price').value = price ? price : '';
                calculateTotalPrice(); // Call this to update the calculations
            });

            // Call calculateTotalPrice when the check-in or check-out dates change
            document.getElementById('checkin').addEventListener('change', calculateTotalPrice);
            document.getElementById('checkout').addEventListener('change', calculateTotalPrice);

            // If in edit mode, call calculateTotalPrice to pre-fill calculations
            @if ($is_edit)
                // Trigger boarding_type change to calculate on load
                const boardingType = document.getElementById('boarding_type');
                boardingType.dispatchEvent(new Event('change')); // simulate change event

                // Manually call calculateTotalPrice if necessary
                calculateTotalPrice();
            @endif
        });
    </script>




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
                                            text: room.name + ' | ' +
                                                room.room_no,
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
                // $('#total').val(Math.round(totalAmountForDays));


                // Get the boarding price for the stay
                var boardingPrice = parseFloat($('#boarding_price_sum').val()) ||
                    0; // Default to 0 if empty

                // Calculate the final total by adding boarding price
                var finalTotal = totalAmountForDays + boardingPrice;

                //alert(finalTotal).value;

                // Set the value of the total amount field
                $('#total').val(Math.round(finalTotal));




                // alert('The difference between check-in and check-out dates is ' + differenceDays +
                // ' days.');
            });


        });
    </script>
@endsection
