@extends('layouts.master-without-nav')

@section('title')
    Print Order
@endsection

@section('content')
    <style>
        body {
            background-color: #FFF !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="row my-2 justify-content-center text-center">
                <img src="{{ asset('storage/' . $settings->logo_dark) }}" class="img-fluid w-25" alt="">
                <span class="fs-5">Thimbiri Wewa Resort</span>
            </div>
            <div class="row justify-content-between mt-5">
                <div class="col">
                    <h6>Order No</h6>
                    <span>#{{ $settings->invoice($data->id) }}</span>
                </div>
                <div class="col">
                    <h6>Order Date</h6>
                    <span>{{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</span>
                </div>
                <div class="col">
                    <h6>Order Type</h6>
                    <span>{{ $data->type }}</span>
                </div>
            </div>
            {{-- <hr> --}}
            <div class="row mt-4">
                <div class="col">
                    <h6>Customer</h6>
                    @if ($data->customer_id == 0)
                        <p>Walking Customer</p>
                    @else
                        <p>{{ $data->customer->name }},</p>
                        <p>{{ $data->customer->contact }},</p>
                        <p>{{ $data->customer->email }},</p>
                        <p>{{ $data->customer->address }}.</p>
                    @endif
                </div>
                @if ($data->room_id != 0)
                    <div class="col">
                        <h6>Room</h6>
                        <p>{{ $data->room->name }}</p>
                    </div>
                @endif
                @if ($data->table_id != 0)
                    <div class="col">
                        <h6>Table</h6>
                        <p>{{ $data->table->name }}</p>
                    </div>
                @endif
            </div>
            <hr>
            <div class="row">
                <h6>Items</h6>
                <div class="col-12">
                    <table class="table table-hover align-middle">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @foreach ($data->items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->meal->name }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @if (!empty($item->modifiers))
                                    @foreach ($item->modifiers as $item2)
                                        <tr>
                                            <td>

                                            </td>
                                            <td>{{ $item2->modifier->name }} <br> <span
                                                    class="small text-muted">Modifier</span></td>
                                            <td>{{ $settings->currency }} {{ number_format($item2->price, 2) }}</td>
                                            <td>{{ $item2->quantity }}</td>
                                            <td>{{ $settings->currency }} {{ number_format($item2->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    Sub Total
                                </td>
                                <td>
                                    {{ $settings->currency }}
                                    {{ number_format($data->payment ? $data->payment->sub_total : 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    Discount
                                </td>
                                <td>
                                    {{ $settings->currency }}
                                    {{ number_format($data->payment ? $data->payment->discount : 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    VAT
                                </td>
                                <td>
                                    {{ $settings->currency }}
                                    {{ number_format($data->payment ? $data->payment->vat : 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    <h5 class="fw-bold">Total</h5>
                                </td>
                                <td>
                                    <h5 class="fw-bold">{{ $settings->currency }}
                                        {{ number_format($data->payment ? $data->payment->total : 0, 2) }}</h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
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
