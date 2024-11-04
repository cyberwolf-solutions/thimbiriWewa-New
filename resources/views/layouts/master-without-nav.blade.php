<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-layout-style="detached"
    data-sidebar="light" data-topbar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Thimbiri Wewa Resort Wilpattu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Thimbiri Wewa Resort Wilpattu" name="description" />
    <meta content="CyberWolf Solutions (Pvt) Ltd." name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>

@yield('body')

@yield('content')

@include('layouts.vendor-scripts')
</body>

</html>
