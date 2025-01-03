@extends('layouts.master-without-nav')

@section('title')
    Print Order
@endsection

@section('content')
    <style>
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

        .top,
        .mid,
        .bottom {
            border-bottom: 1px solid #000;
        }

        .top {
            min-height: 100px;
            text-align: center;
            padding-bottom: -5px;
        }

        .min {
            min-height: 80px;
        }

        .info {
            display: block;
            margin-left: 0;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        thead {
            font-size: 0.8em;
            margin: 2px;
            border-bottom: solid 1px #000;
        }

        .product-colspan {
            border-bottom: none;
        }

        tr {
            border-bottom: 1.5px dotted #ddd;
        }

        td,
        th {
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

        small {
            font-size: 11px;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 18px;
            }

            td,
            th {
                padding: 4px;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                size: 80mm auto; /* Adjust to your thermal printer size */
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
                    <td><a href="{{ route('kitchen.index')}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a></td>
                    <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i>
                            Print</button></td>
                </tr>
            </table>
            <br>
        </div>

        <div id="receipt-data">
            <div class="top">
                @if ($settings->logo_dark)
                    <img src="{{ asset('storage/app/public/' . $settings->logo_dark) }}" height="41" width="183"
                        style="margin:-5px 0;filter: brightness(0);">
                @endif
                <br>
                <div style="text-align: center;margin-top: 5px;margin-bottom: 1px;">
                    <strong>Thimbiri Wewa Resort</strong>
                </div>
                <div style="text-align: center;margin-bottom: -3px;">
                    Contact : <strong>+94 76 118 7676</strong>
                </div>
            </div>

            <div class="mid">
                <div class="info">
                    <h5 style="margin-bottom: -8px; margin-top: 10px;">Invoice No :
                        <strong>#{{ $settings->invoice($data->id) }}</strong></h5>
                    <br>
                    <h5>Order Date : {{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</h5>
                    <br>
                </div>
            </div>

            <table class="table-data">
                <thead>
                    <tr>
                        <br>
                        <td class="item">
                            <h2>Item Name</h2>
                        </td>
                        <td class="qty">
                            <h2>Qty</h2>
                        </td>
                        <td class="total">
                            <h2>Total</h2>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $key => $item)
                        <tr>
                            <td>{{ $item->meal->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bottom">
                <table class="table-data">
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
