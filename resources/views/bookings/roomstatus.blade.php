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
        <div class=" align-items-center justify-content-between">
            <div>

                <div class="row p-3 ">
                    <div class="col-sm-12 col-lg-12 mb-3   ">
                        <div class="row">

                            @foreach ($data as $data1)
                                <div class="col-lg-4 col-sm-12">
                                    <div class=" position-relative d-flex justify-content-center  ">
                                        <div class="hotel-image shadow rounded border border-3 shadow-lg ">
                                            <img src="{{ asset('uploads/rooms/' . $data1->image_url) }}"
                                                class="image-inner "
                                                style="height: 300px; width: 250px; border-radius: 5px;" alt="">
                                        </div>
                                        <div
                                            class="scroll-bar overlay-black px-4 py-3 text-center text-white position-absolute">
                                            <h2 class="fs-21 mt-3 font-weight-bold">
                                                {{ $data1->name }}</h2>
                                            <h3 class="fs-21 mt-3 font-weight-bold">
                                                Room No : {{ $data1->room_no }}</h3>
                                            <p class="mb-1">
                                                Capacity : {{ $data1->capacity }}</p>
                                            {{-- <p class="mb-1">
                                                Type : {{ $data1->type }}</p> --}}

                                            @foreach ($type as $roomType)
                                                @if ($roomType->id == $data1->type)
                                                    <p class="mb-1">{{ $roomType->name }}</p>
                                                @endif
                                            @endforeach

                                            @if ($data1->status == 'Reserved')
                                                <p class="mb-1 ">Check Out : {{ $data1->checkout }}</p>
                                            @else
                                                <p class="mb-1 d-none">Check Out : {{ $data1->checkout }}</p>
                                            @endif

                                            {{-- <p class="mb-1 countdown-text">0.0</p> --}}
                                            <input type="hidden" id="date_time" value="2024-05-06 15:31:13">
                                            <button type="button" class="btn btn-success mb-2 font-weight-bold"
                                                id="" value="130100" data-toggle="modal"
                                                data-target="#exampleModal1"
                                                fdprocessedid="ac7aon">{{ $data1->status }}</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
