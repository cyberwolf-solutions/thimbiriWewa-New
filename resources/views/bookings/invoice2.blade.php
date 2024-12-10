@extends('layouts.master-without-nav')

@section('title')
    Invoice
@endsection

@section('content')
    <style>
        body {
            background-color: #FFF !important;
        }

        .invoice-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header img {
            width: 150px;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .invoice-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }

        h6 {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .table thead th {
            background-color: #f1f1f1;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table tfoot td {
            font-weight: bold;
        }

        .total-row h5 {
            font-size: 1.1rem;
        }

        .border-top {
            border-top: 2px solid #dee2e6 !important;
        }
    </style>

    <div class="container-fluid invoice-container">
        <div class="row text-center mb-4">
            <img src="{{ asset('storage/' . $settings->logo_dark) }}" class="invoice-header img-fluid" alt="Logo">
            <div class="invoice-title">Thimbiri Wewa Resort</div>
            <p class="invoice-meta">{{ $settings->address }}</p>
        </div>

        <div class="row justify-content-between align-items-center mb-4">
            <div>
                <span class="fw-bold">Invoice #:</span> {{ $settings->invoice($data->id) }}
            </div>
            <div>
                <span class="fw-bold">Check-In Date:</span> {{ \Carbon\Carbon::parse($data->checkin)->format($settings->date_format) }}
            </div>
            <div>
                <span class="fw-bold">Check-Out Date:</span> {{ \Carbon\Carbon::parse($data->checkout)->format($settings->date_format) }}
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h6>Customer Details</h6>
                @if ($data->customer_id == 0)
                    <p>Walking Customer</p>
                @else
                    <p>{{ $data->customer->name }}</p>
                    <p>{{ $data->customer->contact }}</p>
                    <p>{{ $data->customer->email }}</p>
                    <p>{{ $data->customer->address }}</p>
                @endif
            </div>
            @if ($data->room_no != 0)
                <div class="col">
                    <h6>Room Details</h6>
                    <p><strong>Room No:</strong> {{ $data->room_no }}</p>
                    {{-- <p><strong>Room Name:</strong> {{ $roomName }}</p> --}}
                </div>
            @endif
        </div>

        <div class="border-top my-4"></div>

        <div class="row">
            <h6>Payment Summary</h6>
            <div class="col-12">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>Room Name</th> --}}
                            <th>Room Payment</th>
                            <th>Paid at Check-In</th>
                            <th>Additional Payment</th>
                            <th>Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $data->id }}</td>
                            {{-- <td>{{ $roomName }}</td> --}}
                            <td>LKR. {{ number_format($data->total_amount, 2) }}</td>
                            <td>LKR. {{ number_format($data->paid_amount, 2) }}</td>
                            <td>LKR. {{ number_format($data->additional_payment, 2) }}</td>
                            <td>{{ $data->discount }}%</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td>Sub Total:</td>
                            <td>{{ $settings->currency }} {{ number_format($data->additional_payment + $data->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Due Amount:</td>
                            <td>{{ $settings->currency }} {{ number_format($data->additional_payment + ($data->total_amount - $data->paid_amount), 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>VAT:</td>
                            <td>{{ $settings->currency }} {{ number_format($data->payment ? $data->payment->vat : 0, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="4"></td>
                            <td>Total Payment:</td>
                            <td>{{ $settings->currency }} {{ number_format($data->sub_total, 2) }}</td>
                        </tr>
                    </tfoot>
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
