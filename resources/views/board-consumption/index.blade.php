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

                <div class="page-title-right">
                    {{-- Add Buttons Here --}}
                    @can('create boardconsumption')
                        <a href="{{ route('boardconsumption.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                            title="Create">
                            <i class="ri-add-line"></i>
                        </a>
                    @endcan

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
                            <th>Date</th>
                            <th>Full board</th>
                            <th>Half board</th>
                            <th>BB</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)

                                <tr>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->full_board }}</td>
                                    <td>{{ $item->half_board }}</td>
                                    <td>{{ $item->bb }}</td>

                                
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
