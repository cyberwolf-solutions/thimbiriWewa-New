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
                    <span>#{{ $settings->invoice($data->id) }}</span>
                </div>
                <div class="col">
                    <h6>Checkout Date</h6>
                    <span>{{ \Carbon\Carbon::parse($data->checkin)->format($settings->date_format) }}</span>
                </div>

                <div class="col">
                    <h6>Checkout Date</h6>
                    <span>{{ \Carbon\Carbon::parse($data->checkout)->format($settings->date_format) }}</span>
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
                @if ($data->room_no != 0)
                    <div class="col">
                        <h6>Room No</h6>
                        <p>{{ $data->room_no }}</p>
                    </div>

                    <div class="col">
                        <h6>Room</h6>
                        @php

                            $roomName = App\Models\Room::where('room_no', $data->room_no)->value('name');
                        @endphp
                        <p>{{ $roomName }}  - {{ $data->roomfacility->name }}</p>
                    </div>
                @endif

            </div>
            <hr>
            <div class="row">
                <h6>Payment</h6>
                <div class="col-12">
                    <table class="table table-hover align-middle">
                        <thead>
                            <th>#</th>
                            <th>Room Name</th>
                            <th>Room Payment</th>
                            <th>Payment when Check In</th>
                            <th>Additional payment</th>
                            {{-- <th>Due ammmount</th> --}}
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{ $data->id}}</td>
                                    <td>{{$roomName  }}</td>
                                    <td>LKR.{{$data->total_amount }}.00 </td>
                                    <td>LKR.{{$data->paid_amount }}.00 </td>
                                    <td>LKR.{{$data->additional_payment }}.00</td>
                                    {{-- <td>LKR.{{$data->additional_payment + $data->due_amount }}.00</td> --}}
                                    
                                </tr>
                                
                           
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    Sub Total
                                </td>
                                <td>
                                    {{ $settings->currency }}
                                    {{ $data->additional_payment + $data->total_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    Due Amount
                                </td>
                                <td>
                                    {{ $settings->currency }}
                                    {{ $data->additional_payment + $data->due_amount }}
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
                                    <h5 class="fw-bold">Full Payment</h5>
                                </td>
                                <td>
                                    <h5 class="fw-bold">{{ $settings->currency }}
                                        {{ $data->additional_payment + $data->total_amount }} .00</h5>
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
