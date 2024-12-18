@extends('layouts.master-without-nav')

@section('title')
    Print Order
@endsection

@section('content')
    <style>
        body {
            background-color: #FFF !important;
            font-size: 10px; /* Smaller font for compact layout */
            width: 80mm; /* Thermal printer width */
            margin: 0 auto;
        }

        .container-fluid {
            padding: 5px;
        }

        .table {
            font-size: 10px;
            width: 100%;
        }

        .table th, .table td {
            padding: 3px;
            text-align: left;
        }

        h6, span, p {
            margin: 0;
            line-height: 1.2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <div class="container-fluid">
        <div class="row text-center">
            <img src="{{ asset('storage/' . $settings->logo_dark) }}" class="img-fluid w-50" alt="Logo">
            <span class="fw-bold">Thimbiri Wewa Resort</span>
        </div>
        <div class="row mt-3">
            <div class="col">
                <h6>Order No</h6>
                <span>#{{ $settings->invoice($data->id) }}</span>
            </div>
            <div class="col">
                <h6>Date</h6>
                <span>{{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</span>
            </div>
            <div class="col">
                <h6>Type</h6>
                <span>{{ $data->type }}</span>
            </div>
        </div>
        <hr>
        <div class="row">
            <h6>Customer</h6>
            @if ($data->customer_id == 0)
                <p>Walking Customer</p>
            @else
                <p>{{ $data->customer->name }}</p>
                <p>{{ $data->customer->contact }}</p>
            @endif
        </div>
        <hr>
        <div class="row">
            <h6>Items</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->meal->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price, 2) }}</td>
                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row">
            <table class="table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">{{ number_format($data->payment->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">{{ number_format($data->payment->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>VAT</td>
                    <td class="text-right">{{ number_format($data->payment->vat, 2) }}</td>
                </tr>
                <tr>
                    <td>Service Charge</td>
                    <td class="text-right">{{ number_format($data->payment->service, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($data->payment->total, 2) }}</strong></td>
                </tr>
            </table>
        </div>
        <hr>
        <div class="row text-center">
            <p>Thank you for your visit!</p>
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
