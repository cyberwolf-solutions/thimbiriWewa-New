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
                <div class="table-responsive mb-3">
                    <table class="table align-middle" id="example">
                        <thead class="table-light">
                            <th>#</th>
                            <th>Name</th>
                            <th>Created By/At</th>
                            <th>Includes</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        {{ $item->createdBy->name }} /
                                        {{ date_format(new DateTime('@' . strtotime($item->created_at)), $settings->date_format) }}
                                    </td>
                                    <td>{{$item->List}}</td>
                                    <td>
                                        @can('edit rooms')
                                            <a href="javascript:void(0)" data-url="{{ route('room-facility.update', $item->id) }}"
                                                data-name="{{ $item->name }}"
                                                class="btn btn-secondary btn-sm small btn-icon edit_data">
                                                <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete rooms')
                                            <a href="javascript:void(0)"
                                                data-url="{{ route('room-facility.destroy', [$item->id]) }}"
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
                <div class="row">
                    <h4 class="topic">Add Facilities</h4>
                    <div class="mt-3">
                        <form method="POST" class="ajax-form" action="{{ route('room-facility.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3 required">
                                    <label for="" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="" placeholder="Enter Name" required />
                                </div>
                                <div class="col-md-6 mb-3 required">
                                    <label for="" class="form-label">List</label>
                                    <textarea name="list" id="list" class="form-control" placeholder="Enter List" required></textarea>
                                </div>
                            </div>
                            

                            <div class="row mb-3">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-action">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.edit_data', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            var list = $(this).data('list');
            
            $('.ajax-form').attr('action', url).append('<input type="hidden" name="_method" value="PATCH">');
            $('.topic').html('Edit Room Type');
            $('.btn-action').html('Update');
            $('#name').val(name);
            $('#list').val(list);
        });
    </script>
@endsection
