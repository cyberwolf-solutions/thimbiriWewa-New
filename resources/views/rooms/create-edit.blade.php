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
                                        {{ $is_edit ?? $data->RoomFacility_id == $data11->id ? 'selected' : '' }}>
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

                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="" rows="1" placeholder="Enter Description">{{ $is_edit ? $data->description : '' }}</textarea>
                        </div>

                        {{-- <div class="col-md-6 mb-3 required">
                            <label for="" class="form-label">Boarding Type</label>
                            <select name="bordingtype" class="form-control" id="boarding_type" required
                                @if ($is_edit) disabled @endif>
                                <option value="">Select...</option>
                                @foreach ($boarding as $boardings)
                                    <option value="{{ $boardings->id }}" data-price="{{ $boardings->price }}"
                                        @if ($is_edit && $data->boardingtype == $boardings->id) selected @endif>
                                        {{ $boardings->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Room Details</h5>
                                        <button class="btn btn-success btn-sm" id="addPricingRow"><i class="ri-add-line"></i> Add Row</button>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="roomTable">
                                            <thead>
                                                <tr>
                                                    <th>Boarding type</th>
                                                    <th>Customer type</th>
                                                    <th>Currency</th>
                                                    <th>Rate</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="roomTableBody">
                                                <tr class="pricing-row" data-index="0">
                                                    <td>
                                                        <select name="pricing[0][boarding_type_id]" class="form-control" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($boarding as $boardings)
                                                                <option value="{{ $boardings->id }}">{{ $boardings->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="pricing[0][customer_type_id]" class="form-control" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($cus as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="pricing[0][currency]" class="form-control" required>
                                                            <option value="lkr">LKR</option>
                                                            <option value="usd">USD</option>
                                                            <option value="euro">EURO</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="pricing[0][rate]" required></td>
                                                    <td><button class="btn btn-danger btn-sm removeRow"><i class="ri-delete-bin-line"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            let rowIndex = 1;
                        
                            // Add new row
                            document.getElementById('addPricingRow').addEventListener('click', function () {
                                let row = document.querySelector('.pricing-row').cloneNode(true);
                                let rowBody = document.getElementById('roomTableBody');
                                row.setAttribute('data-index', rowIndex);
                                
                                // Update the name attributes for form submission
                                row.querySelector('select[name="pricing[0][boarding_type_id]"]').setAttribute('name', 'pricing[' + rowIndex + '][boarding_type_id]');
                                row.querySelector('select[name="pricing[0][customer_type_id]"]').setAttribute('name', 'pricing[' + rowIndex + '][customer_type_id]');
                                row.querySelector('select[name="pricing[0][currency]"]').setAttribute('name', 'pricing[' + rowIndex + '][currency]');
                                row.querySelector('input[name="pricing[0][rate]"]').setAttribute('name', 'pricing[' + rowIndex + '][rate]');
                                
                                // Append the new row to the table body
                                rowBody.appendChild(row);
                                
                                rowIndex++;
                            });
                        
                            // Remove row
                            document.getElementById('roomTable').addEventListener('click', function (event) {
                                if (event.target && event.target.matches('button.removeRow')) {
                                    let row = event.target.closest('tr');
                                    row.remove();
                                }
                            });
                        </script>
                        
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
        <!-- Table with Add Row Button -->
      


    </div>

    <script>
       $(document).ready(function () {
    let index = 1;

    $("#addPricingRow").click(function () {
        let newRow = `
        <tr>
            <td>
                <select name="pricing[${index}][boarding_type_id]" class="form-control" required>
                    <option value="">Select...</option>
                    @foreach ($boarding as $boardings)
                        <option value="{{ $boardings->id }}">{{ $boardings->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="pricing[${index}][customer_type_id]" class="form-control" required>
                    <option value="">Select...</option>
                    @foreach ($cus as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->type }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="pricing[${index}][currency]" class="form-control" required>
                    <option value="lkr">LKR</option>
                    <option value="usd">USD</option>
                    <option value="euro">EURO</option>
                </select>
            </td>
            <td><input type="number" class="form-control" name="pricing[${index}][rate]" required></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
        </tr>`;
        $("#pricingTableBody").append(newRow);
        index++;
    });

    $(document).on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
    });
});

    </script>
    {{-- <script>
        function alertQuantity() {
            // Retrieve the value of the quantity input field
            var quantity = document.getElementById('quantity').value;
    
            // Display an alert with the quantity
            alert("Quantity entered: " + quantity);
        }
    </script> --}}
@endsection
