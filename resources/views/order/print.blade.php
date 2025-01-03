@extends('layouts.master-without-nav')

@section('title')
    Print Order
@endsection

@section('content')
    <style>
        
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        * {
            font-size: 14px;
            line-height: 19px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }

        h5 {
            font-size: 1em;
            font-weight: 400;
            line-height: 0.1em;
        }

        h2 {
            font-size: 1em;
            font-weight: 600;
            line-height: 0.1em;
        }

        .top, .mid, .bottom {
            border-bottom: 1px solid #000;
        }

        .top {
            min-height: 100px;
            text-align: center;
            padding-bottom: -5px;
        }

        .info {
            margin-left: 0;
        }

        td, th, table {
            border-collapse: collapse;
        }

        thead {
            font-size: 0.8em;
            border-bottom: 1px solid #000;
        }

        td, th {
            padding: 7px 0;
            width: 50%;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 18px;
            }

            td, th {
                padding: 4px;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>

    <div style="max-width:290px;margin:0 auto">
        <div class="hidden-print">
            <table>
                <tr>
                    <td><a href="{{ route('restaurant.index')}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a></td>
                    <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i>
                            Print</button></td>
                </tr>
            </table>
            <br>
        </div>

        <div id="receipt-data">
            <div class="top">
                @if ($settings->logo_dark)
                    <img src="{{ asset('storage/app/public/' . $settings->logo_dark) }}" height="41" width="183" style="margin:-5px 0;filter: brightness(0);" class="centered-image">
                @endif
                <br>
                <div class="centered mt-1">
                    <strong>Thimbiri Wewa Resort</strong>
                    <p>Contact : <strong>+94 76 118 7676</strong></p>
                </div>
            </div>

            <div class="mid">
                <h5 class="mt-1">Invoice No: <strong>#{{ $settings->invoice($data->id) }}</strong></h5>
                <p>Order Date: {{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</p>
                <p>Type: {{ $data->type }}</p>
                <p>
                    Customer: 
                    @if ($data->customer_id == 0)
                        Walking Customer
                    @else
                        {{ $data->customer->name }} - {{ $data->customer->contact }}
                    @endif
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $item)
                        <tr>
                            <td>{{ $item->meal->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bottom">
                <table>
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
                    <tr style="background-color: rgba(0, 0, 0, 0.05);">
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->payment->total, 2) }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="bottom">
                <div class="centered">
                    <p>Thank you for your visit!</p>
                </div>
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
