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
            <div class="row justify-content-between mt-5 mb-3">
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
            <hr>
            <div class="row">
                <h6>Items</h6>
                <div class="col-12">
                    <table class="table table-hover align-middle">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Quantity</th>
                        </thead>
                        <tbody>
                            @foreach ($data->items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->meal->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                                @if (!empty($item->modifiers))
                                    @foreach ($item->modifiers as $item2)
                                        <tr>
                                            <td>

                                            </td>
                                            <td>{{ $item2->modifier->name }} <br> <span
                                                    class="small text-muted">Modifier</span></td>
                                            <td>{{ $item2->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>

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
