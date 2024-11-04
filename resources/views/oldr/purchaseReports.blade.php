@extends('layouts.master-without-nav')

@section('title')
    Print Purchase
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
        .purchase-report {
            margin-top: 20px; /* Add space above the purchase report */
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
        <div class="row purchase-report">
            <h4>Purchase Report</h4>
            <div class="col-12">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier Name</th>
                            <th>Contact</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Purchase Date</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $purchase)
                            @foreach ($purchase->items as $item)
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ $purchase->supplier->contact_primary ?? 'N/A' }}</td>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $purchase->date }}</td>
                                    <td>
                                        @foreach ($purchase->payments as $payment)
                                            LKR.{{ $payment->amount }}.00
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
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
