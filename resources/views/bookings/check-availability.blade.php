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
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form" action="{{ route('bookings.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Checkin</label>
                            <input type="date" name="checkin" id="" class="form-control"
                                data-provider="flatpickr" data-date-format="{{ $settings->date_format }}"
                                data-minDate="today" data-deafult-date="{{ date($settings->date_format) }}" />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Checkout</label>
                            <input type="date" name="checkout" id="" class="form-control"
                                data-provider="flatpickr" data-date-format="{{ $settings->date_format }}"
                                data-minDate="today" data-deafult-date="{{ date($settings->date_format) }}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">No of Adults</label>
                            <input type="number" step="any" class="form-control bg-transparent" id=""
                                value="0" name="no_of_adults">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">No of Children</label>
                            <input type="number" step="any" class="form-control bg-transparent" id=""
                                value="0" name="no_of_children">
                        </div>
                    </div>
                    <div class="row show-rooms visually-hidden mt-3">
                        <h5>Available Room Types</h5>
                        <div class="row room-type"></div>
                        <h5>Available Rooms</h5>
                        <div class="row room-details"></div>
                    </div>
                    <div class="row customer-details visually-hidden mt-3">
                        <h4>Guest Details</h4>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3 required">
                                <label for="" class="form-label">Guest Name</label>
                                <input type="text" name="name" id="" class="form-control cust" value=""
                                    placeholder="Enter Name" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Contact No</label>
                                <input type="text" name="contact" id="" class="form-control cust" value=""
                                    placeholder="Enter Contact No" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="text" name="email" id="" class="form-control cust" value=""
                                    placeholder="Enter Email" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Address</label>
                                <textarea class="form-control cust" name="address" id="" rows="1" placeholder="Enter Address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('check-availability') }}'">Cancel</button>
                            <button class="btn btn-primary check-availability">Check Availability</button>
                            <button class="btn btn-primary cbtn" disabled>Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.check-availability', function(e) {
            e.preventDefault();
            $('form').submit(false);
            var formData = $('form').serialize();
            const searchParams = new URLSearchParams(formData);
            formData = Object.fromEntries(searchParams)

            $.ajax({
                url: '{{ route('get-available-rooms') }}',
                type: 'GET',
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    formData
                },
                success: function(result) {



                    $('.room-type').append(result['roomTypeDetails']);
                    $('.room-details').append(result['availableRoomDetails']);

                    // alert(JSON.stringify(result));

                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert("Error: " + xhr.responseText);
                }
            });
            $('.show-rooms').removeClass('visually-hidden');
            $('.customer-details').removeClass('visually-hidden');
            $('.cbtn').removeAttr('disabled');
        });

        $(document).on('click', '.cbtn', function(e) {
            e.preventDefault();
            var form = $('.ajax-form');
            var formData = new FormData(form[0]);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: "JSON",
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    $('#loader').addClass('d-none');
                    if (response.success) {
                        display_success(response.message);
                    } else {
                        display_error(response.message);
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

        $(document).on('change', '.cust', function(e) {
            e.preventDefault();
            $('.check-availability').attr('disabled', 'disabled');
        });
    </script>
@endsection
