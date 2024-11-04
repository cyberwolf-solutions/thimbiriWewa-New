@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-12 col-md-3 mb-3">
                <div class="card text-center p-3 gradient-bg " style=" border: none; border-radius: 20px; solid #007bff;  ">
                    <div class="card-body">
                        <h5 class="card-title ">{{ $todayBookings->count() }}</h5>
                        <p class="card-text text-white">Daily Bookings</p>
                        {{-- <p class="text-danger">20% increase <span class="text-danger">&#9650;</span></p> --}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <div class="card text-center p-3 bg-c-green" style="border: none; border-radius: 20px; ">
                    <div class="card-body">
                        <h5 class="card-title ">{{ $todayOrders->count() }}</h5>
                        
                        <p class="card-text  text-white">Daily Orders</p>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <div class="card text-center p-3 bg-c-yellow" style="border: none; border-radius: 20px; ">
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalOrders }}</h5>
                        <p class="card-text text-white">Total Orders</p>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <div class="card text-center p-3 bg-c-pink" style="border: none; border-radius: 20px; ">
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalBookings }}</h5>
                        <p class="card-text text-white">Total Bookings</p>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class=" container mt-2">
        <div class="row justify-content-center align-items-center">
            <div class="col-6 col-md-3 mb-3  d-flex ">
                <div class="card text-center p-3 titanium" style="border: none; border-radius: 100px; ">
                    <div class="card-body">
                        <h5 class="card-title stock-change">0</h5>
                        <p class="card-text text-white">Total Customers</p>
        
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3  d-flex">
                <div class="card text-center p-3" style="border: none; border-radius: 5px; ">
                    <div class="card-body">
                        <h5 class="card-title stock-change1">0</h5>
                        <p class="card-text text-white">Total Employees</p>
        
                    </div>
                </div>
            </div>
        </div>
    </div> --}}






    <div class="container mt-2">
        <div class="row">
            <div class="col-12 col-md-4 ">
                <div class="card stock-card   border-gradient" style="border: none; border-radius: 10px; ">
                    <div class="card-body">
                        <p class="stock-change text-center fs-24">0</p>
                        <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                        <h5 class="text-center">Total Guests</h5>
                        <div class="stock-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stock-card   border-gradient" style="border: none; border-radius: 10px; ">
                    <div class="card-body">
                        <p class="stock-change1 text-center fs-24">0</p>
                        <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                        <h5 class="text-center">Total Employees</h5>
                        <div class="stock-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stock-card   border-gradient" style="border: none; border-radius: 10px; ">
                    <div class="card-body">
                        <p class="stock-change2 text-center fs-24">0</p>
                        <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                        <h5 class="text-center">Total Suppliers</h5>
                        <div class="stock-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- <div class="container mt-2">
        <div class="row justify-content-center align-items-center ">
            <div class="col-6 col-md-6">
                <div class="card stock-card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Stock</h5>
                        <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                        <p class="stock-change text-danger text-center fw-bold fs-24">0</p>
                       
                        <div class="stock-chart"></div>
                    </div>
                </div>
            </div>


           

        </div>
        



    </div> --}}







    <div class="row mt-3">
        <div class="card border col-11  mx-auto">
            <div class="card-body  ">
                <div class="col-12 ">
                    <h5 class="card-title">Product Stock</h5>

                    <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                    <div class="table-responsive">
                        <table class="table align-middle table-borderless table-hover datatable">
                            <thead>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($Products as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>

                                        <td>{{ $item->name }}</td>

                                        <td>{{ $item->category->name }}</td>
                                        <td> {{ $item->productingredients->first()->quantity }}</td>
                                        <td>
                                            @foreach ($item->productingredients as $productingredient)
                                                @if ($productingredient->quantity <= 10)
                                                    Run Out Soon
                                                @else
                                                    Available
                                                @endif
                                            @endforeach
                                        </td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="row mt-3">
        <div class="card border col-11  mx-auto">
            <div class="card-body">
                <div class="col-12">
                    <h5 class="card-title">Today Orders</h5>
                    <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                    <div class="table-responsive">
                        <table class="table align-middle table-borderless table-hover datatable">
                            <thead>
                                <th>No.</th>
                                <th>Guest</th>
                                <th>Placed By</th>
                                <th>Total</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($todayOrders as $item)
                                    <tr>
                                        <td>#{{ $settings->invoice($item->id) }}</td>
                                        @if ($item->customer_id == 0)
                                            <td>Walking Customer</td>
                                        @else
                                            <td>{{ $item->customer->name }}</td>
                                        @endif
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $settings->currency }}
                                            {{ number_format($item->payment ? $item->payment->total : 0, 2) }}
                                        </td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="card border col-11  mx-auto">
            <div class="card-body">
                <div class="col-12">
                    <h5 class="card-title">Today Bookings</h5>
                    <hr style="border-top: 2px solid #007bff; font-weight: bold;">
                    <div class="table-responsive">
                        <table class="table align-middle table-borderless table-hover datatable">
                            <thead>
                                <th>No.</th>
                                <th>Guest</th>
                                <th>Placed By</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($todayBookings as $item)
                                    <tr>
                                        <td>#{{ $settings->invoice($item->id) }}</td>
                                        @if ($item->customer_id == 0)
                                            <td>Walking Customer</td>
                                        @else
                                            <td>{{ $item->customers->name }}</td>
                                        @endif
                                        <td>{{ $item->createdBy->name }}</td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Check if the browser supports notifications
            if ('Notification' in window) {
                // Request permission to display notifications
                Notification.requestPermission();
            } else {
                alert('This browser does not support desktop notifications.');
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>

    <script>
        $(document).ready(function() {
            var finalCount = {{ $customers->count() }};
            var currentCount = 0;
            var increment = 1;
            var speed = 10;

            var counter = setInterval(function() {
                $('.stock-change').text(currentCount);
                currentCount += increment;
                if (currentCount > finalCount) {
                    clearInterval(counter);
                }
            }, speed / finalCount);
        });
    </script>
    <script>
        $(document).ready(function() {
            var finalCount = {{ $employees->count() }};
            var currentCount = 0;
            var increment = 1;
            var speed = 10;

            var counter = setInterval(function() {
                $('.stock-change1').text(currentCount);
                currentCount += increment;
                if (currentCount > finalCount) {
                    clearInterval(counter);
                }
            }, speed / finalCount);
        });
    </script>
    <script>
        $(document).ready(function() {
            var finalCount = {{ $suppliers->count() }};
            var currentCount = 0;
            var increment = 1;
            var speed = 10;

            var counter = setInterval(function() {
                $('.stock-change2').text(currentCount);
                currentCount += increment;
                if (currentCount > finalCount) {
                    clearInterval(counter);
                }
            }, speed / finalCount);
        });
    </script>
@endsection
