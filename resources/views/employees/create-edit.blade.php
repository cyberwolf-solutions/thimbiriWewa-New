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
                    {{-- <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
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
                    action="{{ $is_edit ? route('employees.update', $data->id) : route('employees.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">First Name</label>
                            <input type="text" name="fname" id="" class="form-control"
                                value="{{ $is_edit ? $data->fname : '' }}" placeholder="Enter your First name" required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Last Name</label>
                            <input type="text" name="lname" id="" class="form-control"
                                value="{{ $is_edit ? $data->lname : '' }}" placeholder="Enter your Last name" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Contact Primary</label>
                            <input type="text" name="contact_primary" id="" class="form-control"
                                value="{{ $is_edit ? $data->contact_primary : '' }}"
                                placeholder="Enter your primary contact" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Contact Secondary</label>
                            <input type="text" name="contact_secondary" id="" class="form-control"
                                value="{{ $is_edit ? $data->contact_secondary : '' }}"
                                placeholder="Enter your secondary contact" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" id="" class="form-control"
                                value="{{ $is_edit ? $data->email : '' }}" placeholder="Enter your email" />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">NIC</label>
                            <input type="text" name="nic" id="" class="form-control"
                                value="{{ $is_edit ? $data->nic : '' }}" placeholder="Enter your NIC" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="" rows="1"
                                      placeholder="Enter your Address" required>{{ $is_edit ? $data->address : '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">City</label>
                            <input type="text" name="city" id="" class="form-control"
                                value="{{ $is_edit ? $data->city : '' }}" placeholder="Enter your city" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Emergency Name</label>
                            <input type="text" name="emergency_name" id="" class="form-control"
                                value="{{ $is_edit ? $data->emergency_name : '' }}" placeholder="Enter your emergency name"
                                required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="" class="form-control"
                                value="{{ $is_edit ? $data->emergency_contact : '' }}"
                                placeholder="Enter your emergency contact" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Designation</label>
                            <select name="designation" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @foreach ($designations as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $is_edit ?? $data->designation == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('employees.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
