<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
{{-- @yield('css') --}}
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

<!-- select2 -->
{{-- <link href="{{ asset('build/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
<!-- Loader CSS -->
<link href="{{ URL::asset('build/css/loader.css') }}" rel="stylesheet" type="text/css" />
<!--bootstrap icons-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
{{-- @yield('css') --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.16/sweetalert2.min.css" />
<!--datatable css-->
<!-- Remix Icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.7.0/fonts/remixicon.css" rel="stylesheet">
<!-- Magnifier -->
{{-- <link href="{{ URL::asset('build/magnify-image/css/jquery.jqZoom.css') }}" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
<style>
    [data-bs-theme="dark"] {
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            border-radius: 50px;
            background-color: #3a3a3a;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 50px;
            background-color: #818181;
        }

        ::-webkit-scrollbar-track {
            border-radius: 50px;
            background-color: #3a3a3a;
        }

        .swal2-popup {
            color: #fff !important;
            background: #3d3d3d !important;
        }
    }

    [data-bs-theme="light"] {
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            border-radius: 50px;
            background-color: #dcdcdc;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 50px;
            background-color: #afafaf;
        }

        ::-webkit-scrollbar-track {
            border-radius: 50px;
            background-color: #dcdcdc;
        }

        .swal2-popup {
            background: #fff !important;
            color: #3d3d3d !important;
        }
    }
</style>

<style>
    .required label::after {
        content: ' *';
        color: red
    }
</style>
<style>
    .header-profile-user {
        object-fit: cover;
    }
</style>
<style>
    /* Custom widget styles */
    .card {
        border: none;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1rem;
        font-weight: normal;
    }

    .text-danger {
        color: #e74c3c !important;
    }

    .text-primary {
        color: #3498db !important;
    }

    .text-success {
        color: #2ecc71 !important;
    }

    @media (max-width: 767.98px) {
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
<style>
    .gradient-bg {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
        /* Define your gradient */
    }

    .bg-c-green {
        background: linear-gradient(45deg, #2ed8b6, #59e0c5);
    }

    .bg-c-yellow {
        background: linear-gradient(45deg, #FFB64D, #ffcb80);
    }

    .bg-c-pink {
        background: linear-gradient(45deg, #FF5370, #ff869a);
    }

    .bg-c-purple {
        background: linear-gradient(45deg, #8e54e9, #4776e6);
    }

    .bg-c-orange {
        background: linear-gradient(45deg, #FF7A18, #ffb44e);
    }

    .bg-c-blue {
        background: linear-gradient(45deg, #00c6fb, #005bea);
    }

    .titanium {
        background: #eea990;
    }

    .bg1 {
        background: #f3f3f3;
    }

    .bg-c-light-red {
        background: linear-gradient(45deg, #ff7979, #ff6b6b);
    }
</style>
<style>
    .selected {
        background-color: rgb(35, 80, 228);
        /* Example highlight color */
        border-color: red;
        /* Example border color */
    }
</style>

@yield('css')
