@extends('layouts.master')
@section('title')
    @lang('translation.profile')
@endsection
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="@if (Auth::user()->cover != '') {{ URL::asset('storage/app/public/' . Auth::user()->cover) }}@else{{ URL::asset('build/images/profile-bg.jpg') }} @endif"
                class="profile-wid-img" style="width: 150px; height: 150px; object-fit: cover;" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="@if (Auth::user()->avatar != '') {{ URL::asset('storage/app/public/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/multi-user.jpg') }} @endif"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" style="width: 150px; height: 150px; object-fit: cover;" alt="" >
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                    name="profile_img">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ Auth::user()->name }}</h5>
                        @if (Auth::check())
                            @php
                                $roles = Auth::user()->getRoleNames()->toArray();
                                $Role = !empty($roles) ? ucwords($roles[0]) : 'No Role';
                            @endphp
                        @endif
                        <p class="text-muted mb-0">{{ $Role }}</p>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i> Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('profile.update') }}" method="POST" class="ajax-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">
                                                Name</label>
                                            <input type="text" class="form-control" id="firstnameInput" name="name"
                                                placeholder="Enter your name" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Email
                                                Address</label>
                                            <input type="email" class="form-control" id="emailInput" name="email"
                                                placeholder="Enter your email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <button type="button" class="btn btn-soft-info">Cancel</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="{{ route('password.change') }}" method="POST" class="ajax-form">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control" name="oldpasswordInput"
                                                placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control" name="newpasswordInput"
                                                placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input type="password" class="form-control"
                                                name="newpasswordInput_confirmation" placeholder="Confirm password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-info">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#profile-img-file-input').change(function() {
                $('#loader').removeClass('d-none');

                var formData = new FormData();
                formData.append('image', $(this)[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('image.update') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response
                                .message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            setTimeout(function() {
                                window.location.href = response.url;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            });
            $('#profile-foreground-img-file-input').change(function() {
                $('#loader').removeClass('d-none');

                var formData = new FormData();
                formData.append('image', $(this)[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('cover.update') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response
                                .message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            setTimeout(function() {
                                window.location.href = response.url;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            });
        });
    </script>
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
