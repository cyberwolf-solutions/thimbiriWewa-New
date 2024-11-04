@extends('layouts.master-without-nav')

@section('title')
    Print Bookings
@endsection

@section('content')
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: Arial, sans-serif; /* Default font */
        }
        .container-fluid {
            padding: 20px;
        }
        .logo {
            max-width: 200px; /* Limit the logo size */
            margin-bottom: 10px; /* Add some space below the logo */
        }
        .resort-name {
            font-size: 24px; /* Larger font size for the resort name */
            color: #111316; /* Blue color */
            font-weight: bold; /* Bold text */
        }
        .booking-report {
            margin-top: 20px; /* Add space above the booking report */
        }
        .table {
            background-color: #fff; /* White background for the table */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }
        .table th,
        .table td {
            vertical-align: middle; /* Center content vertically */
        }
        .table th {
            background-color: #007bff; /* Blue header background */
            color: #fff; /* White text */
        }
        .table-hover tbody tr:hover {
            background-color: #f0f0f0; /* Light gray background on hover */
        }
    </style>
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <img src="{{ asset('storage/' . $settings->logo_dark) }}" class="logo img-fluid" >
            <div class="resort-name">Thimbiri Wewa Resort</div>
        </div>
        <hr>
        <div class="row booking-report">
            <h4>Booking Report</h4>
            <div class="col-12">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Facility</th>
                            <th>Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Number Of Adults</th>
                            <th>Number Of Children</th>
                            <th>Total Members</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->customer->name }}</td>
                                <td>{{ $booking->rooms->first()->name ?? 'N/A' }}</td>
                                <td>{{ $booking->rooms->first()->RoomFacility->name ?? 'N/A' }}</td>
                                <td>{{ $booking->rooms->first()->types->name ?? 'N/A' }}</td>
                                <td>{{ $booking->checkin }}</td>
                                <td>{{ $booking->checkout }}</td>
                                <td>{{ $booking->no_of_adults }}</td>
                                <td>{{ $booking->no_of_children }}</td>
                                <td>{{ $booking->no_of_children + $booking->no_of_adults }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            window.print();
        });
    </script>
@endsection
