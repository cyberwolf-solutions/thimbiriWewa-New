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
                    @can('create users')
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                            title="Create">
                            <i class="ri-add-line"></i>
                        </a>
                    @endcan

                    {{-- <a href="{{route('users.Reports')}}" >
                        <button  class="btn btn-border btn-danger">Reports</button>
                    </a> --}}
                 
                </div>
                
                    
            </div>
        </div>
    </div>

    <div class="row px-3 mt-3 gy-2">
        @foreach ($data as $item)
            <div class="col-md-6">
                <div class="card text-start rounded-4 py-3">
                    <div class="card-body">
                        <div class="row position-relative">
                            <div class="col-3">
                                <img class="card-img-top img-fluid rounded-circle"
                                    src="{{ $item->avatar != '' ? asset('storage/logos/' . $item->avatar) : asset('build/images/users/user-dummy-img.jpg') }}"
                                    alt="Image" />
                            </div>
                            <div class="col-9">
                                <div class="position-absolute end-0 pe-3">
                                    <button type="button" class="btn btn-light" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ri-more-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can('edit users')
                                            <li><a class="dropdown-item" href="{{ route('users.edit', [$item->id]) }}">Edit</a>
                                            </li>
                                        @endcan
                                        @can('change_password users')
                                            <li><a class="dropdown-item change_password" data-id="{{ $item->id }}"
                                                    href="javascript:void(0)">Change Password</a></li>
                                        @endcan
                                        @can('change_status users')
                                            @if ($item->getRoleNames()[0] != 'Super Admin')
                                                @if ($item->id != Auth::user()->id)
                                                    @can('change_status users')
                                                        <li><a class="dropdown-item  @if ($item->is_active) link-danger @else link-success @endif post_confirm"
                                                                href="javascript:void(0)"
                                                                data-url="{{ route('users.status', ['id' => $item->id, 'status' => $item->is_active]) }}"
                                                                data-title="Are you want to deactive this user!">
                                                                @if ($item->is_active)
                                                                    Deactive
                                                                @else
                                                                    Active
                                                                @endif
                                                            </a></li>
                                                    @endcan
                                                @endif
                                            @endif
                                        @endcan
                                    </ul>
                                </div>
                                <span class="card-title fs-2 fw-semibold">{{ $item->name }} </span>
                                <span class="card-text">{{ $item->email }}</span>
                                <br>
                                <span class="card-text">{{ $item->getRoleNames()->first() }}</span>
                                <br>
                                <span class="card-text small">
                                    @if ($item->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Deactive</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- <div class="col-md-6">
            <div class="card text-center rounded-4 py-2">
                <div class="card-body">
                    <h4 class="fs-2 fw-semibold">Create New </h4>
                    <button type="button" class="btn btn-primary fs-3 btn-icon btn-sm">
                        <i class="ri-add-fill"></i>
                    </button>
                    <br>
                    <p>Click here to create a new user</p>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                    <form method="POST" action="{{ route('reset-password') }}" class="ajax-form">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <input id="password" type="password" class="form-control " name="password"
                                        required="" autocomplete="new-password">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required="" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).on('click', '.change_password', function(e) {
            e.preventDefault();
            var id = $(this).data('id')
            $('#id').val(id);
            $('#passwordModal').modal('show');
        });
    </script>
@endsection
