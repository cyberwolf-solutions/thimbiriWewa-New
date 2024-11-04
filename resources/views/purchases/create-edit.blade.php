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
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form" id="purchase-form"
                    action="{{ $is_edit ? route('purchases.update', $data->id) : route('purchases.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Purchase Order #</label>
                            <input type="text" name="" id="" class="form-control"
                                value="{{ $settings->supplier($is_edit ? $data->id : $latest) }}" readonly disabled />
                        </div>

                        <div class="col-md-4 required">
                            <label for="" class="form-label">Date</label>
                            <input type="date" name="date" id="" class="form-control"
                                data-provider="flatpickr" data-date-format="{{ $settings->date_format }}"
                                data-deafult-date="{{ $is_edit ? $data->date : date($settings->date_format) }}" />
                        </div>
                        <div class="col-md-4 required">
                            <label for="" class="form-label">Supplier</label>
                            <select name="supplier" class="form-control js-example-basic-single" id="" required>
                                <option value="">Select...</option>
                                @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $is_edit ?? $data->supplier_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="" class="form-label">Note</label>
                            <textarea class="form-control" name="note" id="" rows="3">{{ $is_edit ? $data->note : '' }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3 mt-4">
                        <div class="col-12 required">
                            <label for="" class="form-label">Choose Ingredient</label>
                            <select name="" class="form-control js-example-basic-single" id="product-select"
                                required>
                                <option value="">Select...</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->unit_price }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 mt-5 px-2">
                        <div class="col-12 bg-light-subtle rounded-3">
                            <table class="table table-hover align-middle" id="product-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th width="15%">Quantity</th>
                                        <th>Total</th>
                                        <th width="5%">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($is_edit)
                                        @foreach ($data->items as $item)
                                            <tr data-id="{{ $item->product_id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td data-id="{{ $item->product_id }}">{{ $item->product->name }}</td>
                                                <td>{{ $settings->currency }} {{ number_format($item->price, 2) }}</td>
                                                <td>
                                                    <input type="number" class="form-control quantity"
                                                        value="{{ $item->quantity }}" min="1">
                                                </td>
                                                <td class="total">{{ $settings->currency }}
                                                    {{ number_format($item->total, 2) }}</td>
                                                <td class="text-center">
                                                    <button
                                                        class="btn btn-light bg-transparent border-0 text-danger delete-row">
                                                        <i class="ri-delete-bin-2-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4 mt-4 px-2 justify-content-end">
                        <div class="col-md-5 bg-light rounded-3 p-4">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span>Sub Total</span>
                                </div>
                                <div class="col-md-6">
                                    <span id="sub">{{ $settings->currency }}
                                        {{ number_format($is_edit ? $data->sub_total : '0', 2) }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span>Discount</span>
                                </div>
                                <div class="col-md-6">
                                    <span>{{ $settings->currency }} <input type="text" class="w-50 border"
                                            id="discount-input" value="{{ $is_edit ? $data->discount : '' }}"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span>VAT %</span>
                                </div>
                                <div class="col-md-6">
                                    <span><input type="text" class="w-50 border" id="vat-input"
                                            value="{{ $is_edit ? $data->vat : '' }}""> %</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Total</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 id="total">{{ $settings->currency }}
                                        {{ number_format($is_edit ? $data->total : '0', 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="products" id="products-input">
                    <input type="hidden" name="subtotal" id="subtotal-input">
                    <input type="hidden" name="discount" id="discount">
                    <input type="hidden" name="vat_percentage" id="vat">

                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('purchases.index') }}'">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="submitForm()">
                                {{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function submitForm() {
            // Update hidden input fields with calculated values
            $('#products-input').val(JSON.stringify(getProducts()));
            $('#subtotal-input').val($('#sub').data('value'));
            $('#discount').val(parseFloat($('#discount-input').val()) || 0);
            $('#vat').val(parseFloat($('#vat-input').val()) || 0);

            // Submit the form
            $('#purchase-form').submit();
        }

        function getProducts() {
            var products = [];
            $('#product-table tbody tr').each(function() {
                var product = {
                    id: $(this).find('td:eq(1)').data('id'),
                    price: parseFloat($(this).find('td:eq(2)').text().replace('{{ $settings->currency }}', '')
                        .trim().replace(/,/g, '')),
                    quantity: parseInt($(this).find('.quantity').val())
                };
                products.push(product);
            });
            return products;
        }
    </script>
    <script>
        $(document).ready(function() {
            var counter = 0;

            // Add product to table
            $('#product-select').change(function() {
                var productId = $(this).val();
                var productName = $(this).find('option:selected').text();
                var productPrice = $(this).find('option:selected').data('price');

                if (productId !== '') {
                    // Check if product already exists in table
                    if ($('#product-table tbody').find('[data-id="' + productId + '"]').length === 0) {
                        counter++;
                        var newRow = '<tr data-id="' + productId + '">' +
                            '<td>' + counter + '</td>' +
                            '<td data-id="' + productId + '">' + productName + '</td>' +
                            '<td>{{ $settings->currency }} ' + numberWithCommas(productPrice.toFixed(2)) +
                            '</td>' +
                            '<td><input type="number" class="form-control quantity" value="1" min="1"></td>' +
                            '<td class="total">{{ $settings->currency }} ' + numberWithCommas(productPrice
                                .toFixed(2)) + '</td>' +
                            '<td class="text-center"><button class="btn btn-light bg-transparent border-0 text-danger delete-row"><i class="ri-delete-bin-2-line"></i></button></td>' +
                            '</tr>';
                        $('#product-table tbody').append(newRow);
                    }
                    recalculateTotal();
                }
            });

            // Delete product row
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                recalculateTotal();
            });

            // Update total when quantity changes
            $(document).on('change', '.quantity', function() {
                var quantity = $(this).val();
                var price = $(this).closest('tr').find('td:nth-child(3)').text().replace(
                    '{{ $settings->currency }}', '').replace(/,/g, '');
                $(this).closest('tr').find('.total').text('{{ $settings->currency }} ' + numberWithCommas((
                    price *
                    quantity).toFixed(2)));
                recalculateTotal();
            });

            // Recalculate total price
            function recalculateTotal() {
                var total = 0;
                $('#product-table tbody tr').each(function() {
                    total += parseFloat($(this).find('.total').text().replace('{{ $settings->currency }}',
                        '').replace(/,/g, ''));
                });
                $('#sub').text('{{ $settings->currency }} ' + numberWithCommas(total.toFixed(2)));
                $('#total').text('{{ $settings->currency }} ' + numberWithCommas(total.toFixed(2)));

                $('#sub').data('value', total);
                $('#total').data('value', total);

                var subtotal = 0;
                var discount = parseFloat($('#discount-input').val()) || 0;
                var vatPercentage = parseFloat($('#vat-input').val()) || 0;

                // Calculate subtotal
                $('#product-table tbody tr').each(function() {
                    var price = parseFloat($(this).find('.total').text().replace(
                        '{{ $settings->currency }}', '').replace(/,/g, ''));
                    subtotal += price;
                });

                // Apply discount
                subtotal -= discount;

                // Apply VAT
                var vatAmount = (subtotal * vatPercentage) / 100;
                subtotal += vatAmount;

                // Update subtotal, total, and handle errors
                // $('#sub').text('{{ $settings->currency }} ' + numberWithCommas(subtotal.toFixed(2)));
                if (isNaN(subtotal)) {
                    $('#total').text('{{ $settings->currency }} 0.00');
                    // Handle error message
                } else {
                    $('#total').text('{{ $settings->currency }} ' + numberWithCommas(subtotal.toFixed(2)));
                    // Clear error message if previously displayed
                }
            }

            // Function to add commas for thousands separator
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to calculate total
            function calculateTotal() {
                var subtotal = 0;
                var discount = parseFloat($('#discount-input').val()) || 0;
                var vatPercentage = parseFloat($('#vat-input').val()) || 0;
                console.log(discount);
                // Calculate subtotal
                $('#product-table tbody tr').each(function() {
                    var price = parseFloat($(this).find('.total').text().replace(
                        '{{ $settings->currency }}', '').replace(/,/g, ''));
                    subtotal += price;
                });

                // Apply discount
                subtotal -= discount;

                // Apply VAT
                var vatAmount = (subtotal * vatPercentage) / 100;
                subtotal += vatAmount;

                // Update subtotal, total, and handle errors
                // $('#sub').text('{{ $settings->currency }} ' + numberWithCommas(subtotal.toFixed(2)));
                if (isNaN(subtotal)) {
                    $('#total').text('{{ $settings->currency }} 0.00');
                    // Handle error message
                } else {
                    $('#total').text('{{ $settings->currency }} ' + numberWithCommas(subtotal.toFixed(2)));
                    // Clear error message if previously displayed
                }
            }

            // Trigger calculateTotal function when discount or VAT input changes
            $('#discount-input, #vat-input').on('input', calculateTotal);

            // Function to add commas for thousands separator
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>
@endsection
