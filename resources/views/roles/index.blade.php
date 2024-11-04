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

                <div class="page-title-right mt-2 mt-md-0">
                    {{-- Add Buttons Here --}}
                    {{-- <a class="btn btn-primary me-2 align-middle text-center btn-icon" data-bs-toggle="collapse"
                        href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                        <i class="mdi mdi-filter-outline fs-5" data-bs-toggle="tooltip" title="Filters"></i>
                    </a> --}}
                    {{-- <a href="#" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" title="Export">
                        <i class="mdi mdi-export fs-5"></i>
                    </a> --}}
                    @can('create roles')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary text-center align-middle btn-icon"
                            data-bs-toggle="tooltip" title="Create">
                            <i class="ri-add-line fs-5"></i>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="collapse" id="collapseExample">
        <div class="card mb-0">
            <div class="card-body">

            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle" id="example">
                        <thead class="table-light">
                            <th>Role</th>
                            <th width="70%">Permissions</th>
                            <th width="10%">Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                @if ($item->name == 'Super Admin')
                                    @continue
                                @endif
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @foreach ($item->permissions as $permission)
                                            <span
                                                class="badge bg-primary-subtle fw-normal rounded-pill m-1">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('edit roles')
                                            <a href="{{ route('roles.edit', [$item->id]) }}"
                                                class="btn btn-secondary btn-sm small btn-icon">
                                                <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete roles')
                                            <a href="javascript:void(0)" data-url="{{ route('roles.destroy', [$item->id]) }}"
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
@endsection
