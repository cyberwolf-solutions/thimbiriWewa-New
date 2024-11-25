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
                    action="{{ $is_edit ? route('daily-stock.update', $data->id) : route('daily-stock.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Ingredient</label>
                            <select name="ingredient" class="form-control js-example-basic-single" id="ingredient" required>
                                <option value="">Select...</option>
                                @foreach ($data as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->quantity }}">
                                        {{ $item->name }}</option>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Available Stock Quantity</label>
                            <input type="text" name="stock_quantity" id="stock_quantity" class="form-control"
                                placeholder="Stock Quantity" required readonly />
                        </div>



                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Kitchen consumption Quantity</label>
                            <input type="text" name="quanity" id="" class="form-control"
                                placeholder="Enter quantity" required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Products made</label>
                            <input type="text" name="product" id="" class="form-control"
                                placeholder="Enter quantity" required />
                        </div>
                        <div class="col-md-6 mb-3 required">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" required />
                        </div>

                    </div>



                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('daily-stock.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('ingredient').addEventListener('change', function() {
            // Get the selected option
            var selectedOption = this.options[this.selectedIndex];

            // Get the quantity from the data-price attribute
            var quantity = selectedOption.getAttribute('data-price');

            // Set the quantity to the stock_quantity input field
            document.getElementById('stock_quantity').value = quantity ? quantity : '';
        });


        document.getElementById('date').value = new Date().toISOString().split('T')[0];
    </script>
@endsection
