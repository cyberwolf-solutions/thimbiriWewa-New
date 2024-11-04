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
                    @can('create rooms')
                        <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
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
                        <thead class="table-light sticky-top">
                            <th>#</th>
                            <th>&nbsp;</th>
                            <th>Name</th>
                            <th>Facilty</th>
                            <th>Room No</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Capacity</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                @php
                                    if($item->image_url != null){
                                        $image = 'uploads/rooms/'.$item->image_url;
                                    }else{
                                        $image = 'uploads/cutlery.png';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="zoom-box">
                                            <img class="room" src="{{ URL::asset($image) }}" alt="" height="40">
                                        </div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{$item->roomFacility->name}}</td>
                                    <td>{{ $item->room_no }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->size }}</td>
                                    <td>{{ $item->capacity }}</td>
                                    <td>{{ $item->types->name }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        @can('view rooms')
                                            <a data-url="{{ route('rooms.show', [$item->id]) }}"
                                                class="btn btn-light btn-sm small btn-icon text-dark show-more">
                                                <i class="bi bi-eye" data-bs-toggle="tooltip" title="View"></i>
                                            </a>
                                        @endcan
                                        @can('edit rooms')
                                            <a href="{{ route('rooms.edit', [$item->id]) }}"
                                                class="btn btn-secondary btn-sm small btn-icon">
                                                <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete rooms')
                                            <a href="javascript:void(0)"
                                                data-url="{{ route('rooms.destroy', [$item->id]) }}"
                                                class="btn btn-danger btn-sm small btn-icon delete_confirm">
                                                <i class="bi bi-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                            </a>
                                        @endcan
                                    </td>
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
    <script>
        $(function(){
            $(".room").jqZoom({
                selectorWidth: 30,
                selectorHeight: 30,
                viewerWidth: 200,
                viewerHeight: 90
            });
        })
    </script>
@endsection
