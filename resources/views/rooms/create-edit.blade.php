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
                    {{-- <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
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
                    action="{{ $is_edit ? route('rooms.update', $data->id) : route('rooms.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter Name" required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room No</label>
                            <input type="text" name="room_no" id="" class="form-control"
                                value="{{ $is_edit ? $data->room_no : '' }}" placeholder="Enter Room No" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room Type</label>
                            <select name="type" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $is_edit ?? $data->type == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="facility" class="form-label">Facility</label>
                            <select name="facility" id="facility" class="form-select" required>
                                <option value="">Select Facility</option>
                                @foreach ($data1 as $data11)
                                    <option value="{{ $data11->id }}" 
                                        {{ $is_edit ?? $data->RoomFacility_id == $data11->id ? 'selected' : '' }} >
                                        {{ $data11->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


            
                        
                    </div>






                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Price</label>
                            <input type="text" name="price" id="" class="form-control"
                                value="{{ $is_edit ? $data->price : '' }}" placeholder="Enter Price" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Image</label>
                            <input type="file" accept="image/png, image/jpeg, image/gif" class="form-control"
                                name="image">
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Size (m2)</label>
                            <select name="size" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->size }}" 
                                        {{ $is_edit && $data->size == $size->size ? 'selected' : '' }}>
                                        {{ $size->size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Capacity</label>
                            <input type="text" name="capacity" id="" class="form-control"
                                value="{{ $is_edit ? $data->capacity : '' }}" placeholder="Enter Capacity" required />
                        </div>

                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Room Status</label>
                            <select name="status" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @php
                                    $types = ['Available', 'Reserved'];
                                @endphp
                                @foreach ($types as $item)
                                    <option value="{{ $item }}"
                                        {{ $is_edit && $data->status == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Rooms Quantity</label>
                            <input type="text" name="quantity" id="quantity" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter Quantity" required />
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="" rows="1" placeholder="Enter Description">{{ $is_edit ? $data->description : '' }}</textarea>
                            </div>
    
                        </div>

                    </div>

                   

                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('rooms.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <script>
        function alertQuantity() {
            // Retrieve the value of the quantity input field
            var quantity = document.getElementById('quantity').value;
    
            // Display an alert with the quantity
            alert("Quantity entered: " + quantity);
        }
    </script> --}}

@endsection
