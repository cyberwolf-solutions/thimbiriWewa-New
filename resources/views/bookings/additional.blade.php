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
                    <h6>Checkout No</h6>
                    <span>#{{ $settings->invoice($checkinCheckout->id) }}</span>
                </div>
                <div class="col">
                    <h6>Checkout Date</h6>
                    <span>{{ \Carbon\Carbon::parse($checkinCheckout->checkin)->format($settings->date_format) }}</span>
                </div>

                <div class="col">
                    <h6>Checkout Date</h6>
                    <span>{{ \Carbon\Carbon::parse($checkinCheckout->checkout)->format($settings->date_format) }}</span>
                </div>

            </div>
            {{-- <hr> --}}
            <div class="row mt-4">
                <div class="col">
                    <h6>Customer</h6>
                    @if ($checkinCheckout->customer_id == 0)
                        <p>Walking Customer</p>
                    @else
                        <p>{{ $checkinCheckout->customer->name }},</p>
                        <p>{{ $checkinCheckout->customer->contact }},</p>
                        <p>{{ $checkinCheckout->customer->email }},</p>
                        <p>{{ $checkinCheckout->customer->address }}.</p>
                    @endif
                </div>
                @if ($checkinCheckout->room_no != 0)
                    <div class="col">
                        <h6>Room No</h6>
                        <p>{{ $checkinCheckout->room_no }}</p>
                    </div>

                    <div class="col">
                        <h6>Room</h6>
                        @php

                            $roomName = App\Models\Room::where('room_no', $checkinCheckout->room_no)->value('name');
                        @endphp
                        <p>{{ $roomName }} - {{ $checkinCheckout->roomfacility->name }}</p>
                    </div>
                @endif

            </div>
            <hr>
            <div class="row">
                <h6>Additional Payment</h6>
                <div class="col-12">
                    <table class="table table-hover align-middle">
                        <thead>
                            {{-- <th>#</th> --}}
                            <th>Room No</th>
                            <th>Order Item</th>
                            <th>Quantity</th>
                            <th>Order Date</th>
                            <th>Item Price</th>
                            <th>Total Cost</th>


                        </thead>
                        <tbody>

                            @php
                                $subtotal = 0;
                            @endphp
                            @foreach ($orders as $order)
                                @foreach ($order->items as $item)
                                    <tr>
                                        {{-- <td>{{ $item->id }}</td> --}}
                                        <td>{{ $order->room_id }}</td>
                                        <td>{{ $item->meal->name }}</td>
                                        <td>1 * {{ $item->quantity }}</td>
                                        <td>{{ $order->order_date }}</td>
                                        <td>{{ $settings->currency }} {{ $item->price }}.00</td>
                                        <td>{{ $settings->currency }} {{ $item->total }}.00</td>
                                        @php
                                            $subtotal += $item->total;
                                        @endphp

                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        
                        <tr>
                            <td colspan="6"><hr></td> 
                        </tr>


                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                                <td>
                                    <h5 class="fw-bold">Sub Total</h5>

                                </td>
                                <td>


                                    <h5 class="fw-bold"> {{ $settings->currency }} {{ $subtotal }}.00</h5>

                                </td>
                            </tr>

                            <tr>
                                <td colspan="6"><hr></td> 
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
