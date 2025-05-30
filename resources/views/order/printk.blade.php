@extends('layouts.master-without-nav')

@section('title')
    Kitchen Order Ticket
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
                    <td><a href="{{ route('restaurant.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a></td>
                    <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> Print</button></td>
                </tr>
            </table>
            <br>
        </div>

        <div id="receipt-data">
            <div class="top">
                @if ($settings->logo_dark)
                    <img src="{{ asset('storage/app/public/' . $settings->logo_dark) }}" height="41" width="183" style="margin:-5px 0;filter: brightness(0);">
                @endif
                <br>
                <div class="centered mt-1">
                    <strong>Thimbiri Wewa Resort</strong>
                    <p>Kitchen Order Ticket</p>
                </div>
            </div>

            <div class="mid">
                <h5 class="mt-1">Order No: <strong>#{{ $settings->invoice($data->id) }}</strong></h5>
                <p>Order Date: {{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</p>
                <p>Type: {{ $data->type }}</p>
            </div>

            <table>
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
            <hr class="bottom">

            {{-- <div class="bottom">
                <div class="centered">
                    <p>Thank you for your order!</p>
                </div>
            </div> --}}
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
