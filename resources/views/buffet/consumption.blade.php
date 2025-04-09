@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-sm-0">{{ $title }}</h3>

                    <ol class="breadcrumb m-0 mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}">
                                @if (!$breadcrumb['active'])
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                                @else
                                    {{ $breadcrumb['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle" id="example">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Breakfast buffet (outside customers)</th>
                                <th>Breakfast buffet (checked in customers)</th>
                                <th>Breakfast buffet (Total)</th>
                                <th>Lunch buffet (outside customers)</th>
                                <th>Lunch buffet (checked in customers)</th>
                                <th>Lunch buffet (Total)</th>
                                <th>Dinner buffet (outside customers)</th>
                              
                               
                                <th>Dinner buffet (checked in customers)</th>
                              
                             
                                <th>Dinner buffet (Total)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $date => $types)
                                <tr>
                                    <td>{{ $date }}</td>
                    
                                    @for ($i = 1; $i <= 3; $i++)
                                        <td>{{ $types[$i]['order_items'] ?? 0 }}</td>
                                        <td>{{ $types[$i]['customer_board_meals'] ?? 0 }}</td>
                                        <td>{{ ($types[$i]['order_items'] ?? 0) + ($types[$i]['customer_board_meals'] ?? 0) }}</td>
                                    @endfor
                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')

@endsection
