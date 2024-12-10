@extends('layouts.master-without-nav')

@section('title')
    Invoice
@endsection

@section('content')
    <style>
        body {
            background-color: #FFF !important;
        }
        .invoice-header img {
            width: 150px;
        }
        .invoice-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table tfoot td {
            font-weight: bold;
        }
        .border-top {
            border-top: 2px solid #dee2e6;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="text-center my-4">
                <img src="{{ asset('storage/' . $settings->logo_dark) }}" class="invoice-header img-fluid" alt="Logo">
                <div class="invoice-title">Thimbiri Wewa Resort</div>
                <p>{{ $settings->address }}</p>
            </div>

            @foreach ($data as $row)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="fw-bold">Invoice #:</span> {{ $settings->invoice($row->id) }}
                    </div>
                    <div>
                        <span class="fw-bold">Checkout Date:</span> 
                        {{ \Carbon\Carbon::parse($data1->checkout)->format($settings->date_format) }}
                    </div>
                </div>
            @endforeach

            <div class="mb-4">
                <h6 class="fw-bold">Customer Details</h6>
                <p>
                    @if ($data1->customer_id == 0)
                        Walking Customer
                    @else
                        {{ $data1->customer->name }}<br>
                        {{ $data1->customer->contact }}<br>
                        {{ $data1->customer->email }}<br>
                        {{ $data1->customer->address }}
                    @endif
                </p>
            </div>

            @if ($data1->room_no != 0)
                <div class="mb-4">
                    <h6 class="fw-bold">Room Details</h6>
                    <p>Room No: {{ $data1->room_no }}</p>
                    {{-- <p>Room Name: {{ $roomName }}</p> --}}
                </div>
            @endif

            <div class="border-top my-4"></div>

            <h6 class="fw-bold">Payment Details</h6>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Name</th>
                        <th>Room Payment (LKR)</th>
                        <th>Paid at Check-In (LKR)</th>
                        <th>Additional Payment (LKR)</th>
                        <th>Discount (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            {{-- <td>{{ $roomName }}</td> --}}
                            <td>{{ number_format($data->total_amount, 2) }}</td>
                            <td>{{ number_format($data->paid_amount, 2) }}</td>
                            <td>{{ number_format($data->additional_payment, 2) }}</td>
                            <td>{{ $data->discount }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Sub Total</td>
                        <td colspan="2">{{ $settings->currency }} {{ number_format($data->additional_payment + $data->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Due Amount</td>
                        <td colspan="2">{{ $settings->currency }} {{ number_format($data->additional_payment + ($data->total_amount - $data->paid_amount), 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Discount</td>
                        <td colspan="2">{{ $data->discount }}%</td>
                    </tr>
                    <tr>
                        <td colspan="4">VAT</td>
                        <td colspan="2">{{ $settings->currency }} {{ number_format($data->payment ? $data->payment->vat : 0, 2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td colspan="4"><h5 class="fw-bold">Total Payment</h5></td>
                        <td colspan="2"><h5 class="fw-bold">{{ $settings->currency }} {{ number_format($data->sub_total, 2) }}</h5></td>
                    </tr>
                </tfoot>
            </table>
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
