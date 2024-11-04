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
                    {{-- <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                        title="Create">
                        <i class="ri-add-line fs-5"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form"
                    action="{{ $is_edit ? route('users.update', $data->id) : route('users.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter your name" />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" id="" class="form-control"
                                value="{{ $is_edit ? $data->email : '' }}" placeholder="Enter your email" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Role</label>
                            <select class="form-control js-example-basic-single" name="role" id="">
                                <option value="" selected>Select...</option>
                                @foreach ($roles as $role)
                                    @if ($role->name == 'Super Admin' && ($is_edit && $data->getRoleNames()[0] != 'Super Admin'))
                                        @continue
                                    @endif
                                    <option value="{{ $role->id }}"
                                        {{ ($is_edit && $data->hasRole($role->name) ? 'selected' : '') }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if (!$is_edit)
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="password" id="" class="form-control"
                                    @if ($is_edit) readonly @endif placeholder="Enter your password" />
                            </div>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('users.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
