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

                {{-- <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('booking.Reports') }}">
                            <button class="btn btn-border btn-danger">Reports</button>
                        </a>
                    </div>
                </div> --}}
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
                            <th>Guest</th>
                            <th>Room No/s</th>
                            <th>Room Facility Type</th>
                            <th>Checkin</th>
                            <th>Checkout</th>
                            <th>No of Adults/Children</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                {{-- @php
                                    $room_ids = [];
                                    foreach ($item->rooms as $room) {
                                        array_push($room_ids, $room->room_no);
                                    }
                                    $rooms = implode(', ', $room_ids);

                                @endphp --}}

                                {{-- @php
                                    $room_numbers = [];
                                    $facility_ids = [];
                                    foreach ($item->rooms as $room) {
                                        $room_numbers[] = $room->room_no;
                                        $facility_ids[] = $room->RoomFacility_id;
                                    }
                                    $rooms = implode(', ', $room_numbers);
                                    $facilities = implode(', ', $facility_ids);
                                @endphp
                                --}}


                                @php
                                    $room_numbers = [];
                                    $facility_names = [];
                                    foreach ($item->rooms as $room) {
                                        $room_numbers[] = $room->room_no;
                                        
                                        $facility_name = \App\Models\RoomFacilities::where(
                                            'id',
                                            $room->RoomFacility_id,
                                        )->value('name');
                                        $facility_names[] = $facility_name;
                                    }
                                    $rooms = implode(', ', $room_numbers);
                                    $facilities = implode(', ', $facility_names);
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-url="{{ route('get-booking-customers') }}"
                                            data-id="{{ $item->customers->id }}" class="show-modal">
                                            {{ $item->customers->name }}
                                        </a>
                                    </td>
                                    <td>{{ $rooms }}</td>
                                    <td>{{ $facilities }}</td>
                                    <td>{{ date_format(new DateTime('@' . strtotime($item->checkin)), $settings->date_format) }}
                                    </td>
                                    <td>{{ date_format(new DateTime('@' . strtotime($item->checkout)), $settings->date_format) }}
                                    </td>
                                    <td>{{ $item->no_of_adults }} / {{ $item->no_of_children }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        @can('view rooms')
                                            <a data-url="{{ route('get-booking-rooms') }}" data-id="{{ $item->id }}"
                                                class="btn btn-primary btn-sm small btn-icon text-white show-modal">
                                                <i class="mdi mdi-room-service" data-bs-toggle="tooltip" title="View Rooms"></i>
                                            </a>
                                        @endcan
                                        @can('view bookings')
                                            <a data-url="{{ route('bookings.show', [$item->id]) }}"
                                                class="btn btn-light btn-sm small btn-icon text-white show-more">
                                                <i class="bi bi-eye" data-bs-toggle="tooltip" title="View"></i>
                                            </a>
                                        @endcan
                                        @if ($item->status != 'Complete')
                                            @can('edit bookings')
                                                <a href="{{ route('bookings.edit', [$item->id]) }}"
                                                    class="btn btn-secondary btn-sm small btn-icon">
                                                    <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete bookings')
                                                <a href="javascript:void(0)"
                                                    data-url="{{ route('bookings.destroy', [$item->id]) }}"
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
