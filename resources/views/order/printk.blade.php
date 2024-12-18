@extends('layouts.master-without-nav')

@section('title')
    Kitchen Order Ticket
@endsection

@section('content')
    <style>
        body {
            background-color: #FFF !important;
            font-family: Arial, sans-serif;
            font-size: 13px;
            width: 80mm; /* Thermal printer width */
            margin: 0 auto;
            color: #000;
        }

        .container-fluid {
            padding: 10px;
            border: 1px solid #000;
            border-radius: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 50px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }

        .header p {
            margin: 0;
            font-size: 12px;
            font-weight: normal;
        }

        .order-info {
            margin-bottom: 10px;
        }

        .order-info p {
            margin: 2px 0;
            font-weight: bold;
        }

        .items-section {
            margin-bottom: 10px;
        }

        .items-section h2 {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 1px dashed #000;
        }

        .items-table {
            width: 100%;
            border-spacing: 0;
        }

        .items-table th, .items-table td {
            padding: 5px;
            text-align: left;
        }

        .items-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .items-table td {
            border-bottom: 1px dashed #ddd;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 10px;
        }
    </style>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ asset('storage/' . $settings->logo_dark) }}" alt="Logo">
            <h1>Thimbiri Wewa Resort</h1>
            <p>Kitchen Order Ticket</p>
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <p>Order No: #{{ $settings->invoice($data->id) }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</p>
            <p>Type: {{ $data->type }}</p>
        </div>

        <!-- Items Section -->
        <div class="items-section">
            <h2>Order Items</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th style="text-align: center;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->meal->name }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            Thank you for your order!
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
