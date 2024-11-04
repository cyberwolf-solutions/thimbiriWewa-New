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
                    {{-- <a href="{{ route('meals.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
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
                    action="{{ $is_edit ? route('meals.update', $data->id) : route('meals.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-4 mb-3 required">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter Name" required />
                        </div>
                        <div class="col-md-4 mb-3 required">
                            <label for="" class="form-label">Category</label>
                            <select name="category" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $is_edit ?? $data->category_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 required">
                            <label for="" class="form-label">Unit Price</label>
                            <input type="text" name="unit_price" id="" class="form-control"
                                value="{{ $is_edit ? $data->unit_price : '' }}" placeholder="Enter Unit Price" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Image</label>
                            <input type="file" accept="image/png, image/jpeg, image/gif" class="form-control"
                                name="image">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="" rows="1" placeholder="Enter Description">{{ $is_edit ? $data->description : '' }}</textarea>
                        </div>
                    </div>
                    <div class="required mt-2">
                        <label for="">Products</label>
                        <div class="row mb-4">
                            <select name="product" id="product" class="form-control js-example-basic-single">
                                <option value="">Select...</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-4 px-3">
                            <table class="table table-hover align-middle" id="product_tbl">
                                <thead class="table-light">
                                    <th>Name</th>
                                    <th>Consumption Qty</th>
                                    <th>Unit Price</th>
                                    <th>Cost</th>
                                    <th width="5%"></th>
                                    <th width="5%"></th>
                                </thead>
                                <tbody>
                                    @if ($is_edit)
                                        @for ($x = 0; $x < count($prod_array); $x++)
                                            <tr>
                                                <td><span class="iname">{{ $prod_array[$x]['name'] }}</span></td>
                                                <td>
                                                    <input type="number" step="any" class="form-control bg-transparent"
                                                        id="quantity" name="quantity[]"
                                                        value="{{ $prod_array[$x]['cons_qty'] }}">
                                                </td>
                                                <td>{{ $prod_array[$x]['currency'] }} <span
                                                        class="uprice">{{ $prod_array[$x]['unit_price'] }}</span></td>
                                                <td>{{ $prod_array[$x]['currency'] }} <span
                                                        class="cost">{{ $prod_array[$x]['cost'] }}</span></td>
                                                <td><button class="btn bg-transparent border-0 text-danger remove_row"><i
                                                            class="ri-delete-bin-2-line"></i></button></td>
                                                <td><input type="hidden" step="any"
                                                        class="form-control bg-transparent prod_id"
                                                        value="{{ $prod_array[$x]['id'] }}" name="prod_id[]"></td>
                                            </tr>
                                        @endfor
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th>Total</th>
                                        <th>{{ $settings->currency }} <span
                                                class="total">{{ $is_edit ? $total : '0.00' }}</span></th>
                                        <th colspan="2">&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('meals.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#product").change(function(e) {
            e.preventDefault();
            var el = $(this);
            var prod_id = el.val();
            $.ajax({
                url: '{{ route('get-products') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "prod_id": prod_id
                },
                success: function(result) {
                    var cols = result.col;
                    $('#product_tbl tbody').append(cols);
                    $("#product option:selected").remove();
                    $('#product_tbl tbody tr:last #quantity').focus();
                }
            });
        });

        $(document).on('change', '#quantity', function(e) {
            e.preventDefault();
            var el = $(this);
            var quantity = el.val();
            var unit_price_str = el.closest('tr').find('span.uprice').text();

            // Remove commas from unit_price_str
            var unit_price = parseFloat(unit_price_str.replace(/,/g, ''));
            // var unit_price = el.closest('tr').find('span.uprice').text();
            var cost = Number(quantity) * Number(unit_price);
            el.closest('tr').find('span.cost').html(Number(cost).toFixed(2));
            getTotal();
        });

        $(document).on('click', '.remove_row', function(e) {
            e.preventDefault();
            var id = $(this).closest('tr').find('.prod_id').val();
            var name = $(this).closest('tr').find('span.iname').text();
            $(this).closest("tr").remove();
            $("#product").append('<option value="' + id + '">' + name + '</option>');
            getTotal();
        });

        function getTotal() {
            var sum = 0;
            $('.cost').each(function() {
                sum += +$(this).text() || 0;
            });
            $(".total").text(sum.toFixed(2));
        }
    </script>
@endsection
