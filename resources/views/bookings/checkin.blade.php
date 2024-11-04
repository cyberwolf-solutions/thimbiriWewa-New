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
                <div>
                    <a href="{{route('checkin.create')}}">
                        <button  class="btn btn-border btn-danger">Add checkin</button>
                    </a>
                    
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
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Room No</th>
                            <th>Room Facility Type</th>
                            <th>Room Type</th>
                            <th>Checkin Date</th>
                            {{-- <th>Checkout Date</th> --}}
                            <th>Status</th>
                            <th>Room Cost</th>
                            <th>Payed Ammount</th>
                            <th>Due Ammount</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                

                                

                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->customer->name}}</td>
                                    <td>{{$item->room_no}}</td>
                                    <td>{{$item->room_facility_type}}</td>
                                    <td>{{$item->roomType->name}}</td>
                                    <td>{{$item->checkin}}</td>
                                    {{-- <td>{{$item->status}}</td> --}}
                                    <td>Checked-In</td>
                                    <td>{{$item->total_amount}}</td>
                                    <td>{{$item->paid_amount}}</td>
                                    <td>{{$item->due_amount}}</td>
                                    <td>
                                        @can('view rooms')
                                            <a data-url="{{ route('get-booking-rooms') }}" data-id="{{ $item->id }}"
                                                class="btn btn-primary btn-sm small btn-icon text-white show-modal">
                                                <i class="mdi mdi-room-service" data-bs-toggle="tooltip" title="View Rooms"></i>
                                            </a>
                                        @endcan
                                        {{-- @can('view bookings')
                                            <a data-url="{{ route('bookings.show', [$item->id]) }}"
                                                class="btn btn-light btn-sm small btn-icon text-white show-more">
                                                <i class="bi bi-eye" data-bs-toggle="tooltip" title="View"></i>
                                            </a>
                                        @endcan --}}
                                        @if ($item->status != 'Complete')
                                            {{-- @can('edit bookings')
                                                <a href="{{ route('bookings.edit', [$item->id]) }}"
                                                    class="btn btn-secondary btn-sm small btn-icon">
                                                    <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                                </a>
                                            @endcan --}}
                                            @can('delete bookings')
                                                <a href="javascript:void(0)"
                                                    data-url="{{ route('checkin.destroy', [$item->id]) }}"
                                                    class="btn btn-danger btn-sm small btn-icon delete_confirm">
                                                    <i class="bi bi-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                                </a>
                                            @endcan
                                        @endif
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
@include('bookings.view-customers-rooms-modal')

@section('script')
    <script>
        $(document).on('click', '.show-modal', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "id": id
                },
                success: function(result) {
                    $('.custRoomBody').html(result);
                }
            });
            $('#viewCustomersRoomsModal').modal('show');
        });
    </script>
@endsection
