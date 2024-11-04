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
                    {{-- <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Create">
                        <i class="ri-add-line"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card sticky-top " style="top:100px">
                <div class="list-group list-group-flush" id="useradd-sidenav">
                    @can('manage_system settings')
                        <a href="#general" class="list-group-item list-group-item-action active">System Setting
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    @endcan
                    @can('manage_mail settings')
                        <a href="#mail" class="list-group-item list-group-item-action">Email Setting
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @can('manage_system settings')
                <div id="general" class="card">
                    <div class="card-header">
                        <h5>System Setting</h5>
                        <small class="text-muted">Edit details about your Company</small>
                    </div>

                    <form method="POST" action="{{ route('update-settings') }}" class="ajax-form">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="site_currency" class="form-label">Currency</label>
                                    <input class="form-control font-style" name="site_currency" type="text"
                                        value="{{ $data ? $data->currency : '' }}" id="site_currency">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="site_title" class="form-label">Title</label>
                                    <input class="form-control font-style" name="site_title" type="text"
                                        value="{{ $data ? $data->title : '' }}" id="site_title">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control font-style" name="email" type="text"
                                        value="{{ $data ? $data->email : '' }}" id="email">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input class="form-control font-style" name="contact" type="text"
                                        value="{{ $data ? $data->contact : '' }}" id="contact">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="logo_light" class="form-label">Logo Light</label>
                                    <a target="_blank" href="{{ URL::asset('storage/' . $data ?? $data->logo_light) }}"
                                        class="small text-muted text-decoration-underline">View
                                        image</a>
                                    <input class="form-control font-style" name="logo_light" type="file" id="logo_light"
                                        accept="image/*">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="logo_dark" class="form-label">Logo Dark</label> <a target="_blank"
                                        href="{{ URL::asset('storage/' . $data ?? $data->logo_dark) }}"
                                        class="small text-muted text-decoration-underline">View
                                        image</a>
                                    <input class="form-control font-style" name="logo_dark" type="file"
                                        id="logo_dark"accept="image/*">
                                </div>

                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="site_date_format" class="form-label">Date Format</label>
                                    <select type="text" name="site_date_format" class="form-control selectric"
                                        id="site_date_format">
                                        <option value="M j, Y" @if ($data ? $data->date_format : '' == 'M j, Y') selected @endif>Jan 1,2015
                                        </option>
                                        <option value="d-m-Y" @if ($data ? $data->date_format : '' == 'd-m-Y') selected @endif>d-m-y</option>
                                        <option value="m-d-Y" @if ($data ? $data->date_format : '' == 'm-dY') selected @endif>m-d-y</option>
                                        <option value="Y-m-d" @if ($data ? $data->date_format : '' == 'Y-m-d') selected @endif>y-m-d</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="site_time_format" class="form-label">Time Format</label>
                                    <select type="text" name="site_time_format" class="form-control selectric"
                                        id="site_time_format">
                                        <option value="g:i A" @if ($data ? $data->date_format : '' == 'g:i A') selected @endif>10:30 PM
                                        </option>
                                        <option value="g:i a" @if ($data ? $data->date_format : '' == 'g:i a') selected @endif>10:30 pm
                                        </option>
                                        <option value="H:i" @if ($data ? $data->date_format : '' == 'H:i') selected @endif>22:30</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="invoice_prefix" class="form-label">Invoice Prefix</label>

                                    <input class="form-control" name="invoice_prefix" type="text"
                                        value="{{ $data ? $data->invoice_prefix : '' }}" id="invoice_prefix">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="bill_prefix" class="form-label">Bill Prefix</label>
                                    <input class="form-control" name="bill_prefix" type="text"
                                        value="{{ $data ? $data->bill_prefix : '' }}" id="bill_prefix">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="customer_prefix" class="form-label">Customer Prefix</label>
                                    <input class="form-control" name="customer_prefix" type="text"
                                        value="{{ $data ? $data->customer_prefix : '' }}" id="customer_prefix">
                                </div>
                                <div class="form-group col-md-6 mb-3 required">
                                    <label for="supplier_prefix" class="form-label">Supplier Prefix</label>
                                    <input class="form-control" name="supplier_prefix" type="text"
                                        value="{{ $data ? $data->supplier_prefix : '' }}" id="supplier_prefix">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-primary m-r-10" type="submit" value="Save Changes">
                            </div>
                        </div>
                    </form>

                </div>
            @endcan
            @can('manage_mail settings')
                <div id="mail" class="card">
                    <div class="card-header">
                        <h5>Email Setting</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('update-mail') }}" class="ajax-form">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_driver" class="form-label">Mail Driver</label>
                                        <input class="form-control" placeholder="Enter Mail Driver" name="mail_driver"
                                            type="text" value="{{ $mail ? $mail->driver : '' }}" id="mail_driver">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_host" class="form-label">Mail Host</label>
                                        <input class="form-control " placeholder="Enter Mail Host" name="mail_host"
                                            type="text" value="{{ $mail ? $mail->host : '' }}" id="mail_host">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_port" class="form-label">Mail Port</label>
                                        <input class="form-control" placeholder="Enter Mail Port" name="mail_port"
                                            type="text" value="{{ $mail ? $mail->port : '' }}" id="mail_port">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_username" class="form-label">Mail Username</label>
                                        <input class="form-control" placeholder="Enter Mail Username" name="mail_username"
                                            type="text" value="{{ $mail ? $mail->username : '' }}" id="mail_username">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_password" class="form-label">Mail Password</label>
                                        <input class="form-control" placeholder="Enter Mail Password" name="mail_password"
                                            type="text" value="{{ $mail ? $mail->password : '' }}" id="mail_password">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_encryption" class="form-label">Mail Encryption</label>
                                        <input class="form-control" placeholder="Enter Mail Encryption"
                                            name="mail_encryption" type="text"
                                            value="{{ $mail ? $mail->encryption : '' }}" id="mail_encryption">
                                    </div>
                                </div>



                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_from_address" class="form-label">Mail From Address</label>
                                        <input class="form-control" placeholder="Enter Mail From Address"
                                            name="mail_from_address" type="text"
                                            value="{{ $mail ? $mail->from_address : '' }}" id="mail_from_address">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label for="mail_from_name" class="form-label">Mail From Name</label>
                                        <input class="form-control" placeholder="Enter Mail From Name" name="mail_from_name"
                                            type="text" value="{{ $mail ? $mail->from_name : '' }}" id="mail_from_name">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="card-footer d-flex justify-content-end align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="sendTestMail"
                                            name="sendTestMail">
                                        <label class="form-check-label" for="sendTestMail">Send Test Mail</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" value="Save Changes">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Cache the side navigation links
            var sideNavLinks = $('.list-group-item');

            // Add click event listener
            sideNavLinks.click(function() {
                // Remove 'active' class from all links
                sideNavLinks.removeClass('active');

                // Add 'active' class to the clicked link
                $(this).addClass('active');
            });

            // Debounce scroll event
            var debounceScroll = debounce(function() {
                // Get the current scroll position
                var scrollPosition = $(window).scrollTop();

                // Iterate through each section and check if it is in the viewport
                sideNavLinks.each(function() {
                    var targetSection = $($(this).attr('href'));

                    if (targetSection.length) {
                        var sectionTop = targetSection.offset().top;
                        var sectionBottom = sectionTop + targetSection.outerHeight();

                        // Check if the scroll position is within or near the section
                        if (scrollPosition >= sectionTop - 500 && scrollPosition < sectionBottom) {
                            // Remove 'active' class from all links
                            sideNavLinks.removeClass('active');

                            // Add 'active' class to the corresponding link
                            $(this).addClass('active');
                        }
                    }
                });
            }, 200); // Adjust the debounce time as needed

            // Add debounced scroll event listener
            $(window).scroll(debounceScroll);
        });

        // Debounce function
        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this,
                    args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    </script>
@endsection
