@extends('layouts.panel')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <style>
        .highlight {
            background-color: rgb(255, 255, 0);
            color: black
        }
    </style>
    <!-- start page title -->
    <div class="col-12">
        <div class="row position-relative   ">
            <div class="card  mb-1">
                <div class="card-body ">
                    <div class="row gy-2">
                        <a href="{{ route('home') }}" type="button"
                            class="btn btn-light btn-icon waves-effect waves-light form-control" data-bs-toggle="tooltip"
                            title="Home"><i class="mdi mdi-home label-icon align-middle fs-16"></i></a>
                        <div class="col">
                            <button type="button"
                                class="btn btn-soft-danger btn-label waves-effect waves-light form-control"
                                onclick="window.location.reload()"><i
                                    class="mdi mdi-cancel label-icon align-middle fs-16 me-2"></i>Clear All</button>
                        </div>
                        <div class="col border-end pe-2">
                            <select class="form-control js-example-basic-single" name="" id="type">
                                <option value="Dining" selected>Dining</option>
                                <option value="TakeAway">TakeAway</option>
                                <option value="RoomDelivery">Room Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Search product by names"
                                id="searchInput">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-light btn-label waves-effect waves-light form-control"
                                data-ajax-popup="true" data-title="Notes" data-size="xl"
                                data-url="{{ route('restaurant.note') }}"><i
                                    class="mdi mdi-book label-icon align-middle fs-16 me-2"></i>
                                Notes</button>
                        </div>
                        @can('manage kitchen')
                            <div class="col">
                                <a href="{{ route('kitchen.index') }}" target="_blank" type="button"
                                    class="btn btn-soft-info btn-label waves-effect waves-light form-control"><i
                                        class="mdi mdi-silverware label-icon align-middle fs-16 me-2"></i> Kitchen</a>
                            </div>
                        @endcan
                        @can('manage bar')
                            <div class="col">
                                <a href="{{ route('bar.index') }}" target="_blank" type="button"
                                    class="btn btn-soft-secondary btn-label waves-effect waves-light form-control"><i
                                        class="mdi mdi-glass-cocktail label-icon align-middle fs-16 me-2"></i> Bar</a>
                            </div>
                        @endcan
                        <div class="col">
                            <button type="button"
                                class="btn btn-soft-success btn-label waves-effect waves-light form-control"
                                data-ajax-popup="true" data-title="Orders in process" data-size="lg"
                                data-url="{{ route('restaurant.process') }}"><i
                                    class="mdi mdi-list-box label-icon align-middle fs-16 me-2"></i> In
                                Process</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row position-relative  ">
            <div class="card mb-0 ps-0">
                {{-- <div class="card-body"> --}}
                <div class="row">
                    <div class=" col-md-8">

                        <div class="row">

                            <div class="col-md-12 d-flex flex-wrap "
                                style="height: auto;padding-top:10px;padding-bottom:10px;margin-bottom:10px;">
                                @foreach ($categories as $item)
                                    @php
                                        $image = $item->image_url
                                            ? 'uploads/categories/' . $item->image_url
                                            : 'uploads/cutlery.png';
                                    @endphp
                                    <div class="col-md-1 cursor-pointer category-item border border rounded position-relative text-center mx-1"
                                        data-id="{{ $item->id }}" onclick="highlightCategoryItem(this)"
                                        id="clickable-span{{ $item->id }}" title="{{ $item->name }}"
                                        style="height: auto; width:100px; margin-top: 10px; color: black; background-color:#F5F5F5; display: block; text-align: center; padding: 10px; cursor: pointer;">
                                        {{-- <div class="col-md-1 cursor-pointer category-item border border rounded position-relative text-center mx-1" data-id="{{ $item->id }}" onclick="highlightCategoryItem(this)"  id="clickable-span"  title="{{ $item->name }}"
                                         style="height: auto;width:100px;margin-top: 10px; color: black; background-color:#DCDCDC; display: block; text-align: center; padding: 10px;"> --}}
                                        {{-- <div class="card border rounded  position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" > --}}
                                        {{-- <span class="text-center">{{ $item->name }}</span> --}}
                                        {{-- <span id="clickable-span" class="text-center" style="margin-top: 10px; color: black; background-color: gray; display: block; text-align: center; padding: 10px;">
                                              
                                            </span> --}}
                                        {{ $item->name }}
                                        {{-- <script>
                                            document.getElementById('clickable-span{{ $item->id }}').addEventListener('click', function() {
                                                this.style.color = 'white';
                                                this.style.backgroundColor = '#318CE7';
                                            });
                                        </script> --}}

                                        {{-- </div> --}}
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-12 mt-2 mt-md-0">
                                <div class="row">
                                    <div class="col-md-12 pe-2">
                                        <div class="d-flex flex-wrap overflow-auto gap-2 pe-3 p-3 bg-light rounded-4"
                                            style="max-height: 80vh; min-height: 80vh;background: #ffffff;">
                                            @foreach ($meals as $meal)
                                                @php
                                                    if ($meal->image_url != null) {
                                                        $image = 'uploads/meals/' . $meal->image_url;
                                                    } else {
                                                        $image = 'uploads/cutlery.png';
                                                    }
                                                @endphp
                                                <div class="col-md-3 cursor-pointer meal-item shadow rounded-3"
                                                    data-id="{{ $meal->id }}" style="height:100%; width: 25%"
                                                    data-category="{{ $meal->category_id }}"
                                                    data-image="{{ URL::asset($image) }}"
                                                    data-price="{{ floatval($meal->unit_price) }}">
                                                    <div class="card border mb-0 rounded-4"
                                                        style="height: 30vh; overflow: hidden;background: #ffffff;">
                                                        <!-- Set fixed dimensions for the image -->
                                                        <img src="{{ URL::asset($image) }}" alt=""
                                                            class="card-img-top"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div class="text-center"
                                                        style="background-color: #ffffff;margin-top:10px;border-radius: 10px;">

                                                        <div class="row">
                                                            <div class="col-6">
                                                                <span class="meal-name">{{ $meal->name }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <p style="color: #318CE7; font-weight: bold;">
                                                                    {{ $settings->currency }}
                                                                    {{ number_format($meal->unit_price, 2) }}</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach
                                            <div id="noResultsMessage" style="display: none;">
                                                <h1>No products found.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>

                    <div class="col-md-4 bg-light rounded-3 px-2 mt-5 mt-md-0 position-relative   d-flex"
                        style="min-height: 100%">
                        <div class="row p-1">
                            <div class="col-12">
                                <div class="row justify-content-between">
                                    <div class="col-4">
                                        <button id="customer-btn"
                                            class="btn btn-light btn-sm form-control"data-ajax-popup="true"
                                            data-title="Customers" data-size="lg" data-binding=""
                                            data-url="{{ route('restaurant.customer') }}"><i class="mdi mdi-account"></i>
                                            <span id="name"> Select Customer</span>
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button id="room-btn" class="btn btn-light btn-sm form-control"
                                            data-ajax-popup="true" data-title="Rooms" data-size="lg"
                                            data-url="{{ route('restaurant.rooms') }}" data-binding=""><i
                                                class="mdi mdi-room-service"></i>
                                            <span id="name"> Select Room</span>
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button id="table-btn" class="btn btn-light btn-sm form-control"
                                            data-ajax-popup="true" data-title="Tables" data-size="lg"
                                            data-url="{{ route('restaurant.tables') }}" data-binding=""><i
                                                class="mdi mdi-table"></i>
                                            <span id="name">
                                                Select
                                                Table</span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-1">
                                <div class="row overflow-auto gap-2" style="height: 50vh">
                                    <div class="col-12 border position-relative">
                                        <div id="cartBody"></div>
                                        <div class="select-none bg-blue-gray-100 rounded flex flex-wrap content-center justify-center opacity-25 mt-5"
                                            id="emptyCart">
                                            <div class="w-full text-center">
                                                <i class="ri-shopping-cart-2-line display-1"></i>
                                                <p class="text-xl">
                                                    Cart is empty!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4 p-2 border rounded-3 bg-blue-gray-100 small">
                                            <span>Subtotal</span><br>
                                            <span id="sub" value="0">LKR 0.00</span>
                                        </div>
                                        <div class="col-4 p-2 border rounded-3 bg-blue-gray-100 small">
                                            <span>Discount (<span id="discount_method_html">0%</span>)</span><br>
                                            <span><span id="discount_html">LKR 0.00</span> <a href="javascript:void(0)"
                                                    class="float-end" id="discount-edit" data-ajax-popup="true"
                                                    data-title="Discount Edit" data-size="lg"
                                                    data-url="{{ route('restaurant.discount') }}">
                                                    (Edit)
                                                </a></span>
                                        </div>
                                        <div class="col-4 p-2 border rounded-3 bg-blue-gray-100 small">
                                            <span>VAT (<span id="vat_method_html">0%</span>)</span><br>
                                            <span><span id="vat_html">LKR 0.00</span> <a href="javascript:void(0)"
                                                    class="float-end" id="vat-edit" data-ajax-popup="true"
                                                    data-title="VAT Edit" data-size="lg"
                                                    data-url="{{ route('restaurant.vat') }}">
                                                    (Edit)
                                                </a></span>
                                        </div>
                                        <div class="col-12 p-2 border bg-body rounded-3">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="fs-4">Total</span>
                                                </div>
                                                <div class="col text-end">
                                                    <span class="fs-5" id="total" value="0">LKR
                                                        0.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 rounded-3">
                                            <div class="row align-items-center p-2 ">
                                                <button class="btn btn-primary text-dark fw-bold"
                                                    style="background-color: #378CE7;"
                                                    onclick="checkout()">Checkout</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- </div> --}}
            </div>
        </div>
    @endsection


    @section('script')
        {{-- <script>
        function highlightCategoryItem(element) {

           
            // Remove 'selected' class from all category items
            const items = document.querySelectorAll('.category-item');
            items.forEach(item => item.classList.remove('selected'));
    
            // Add 'selected' class to the clicked category item
            element.classList.add('selected');
        }
    </script> --}}
        <script>
            function beep() {
                var context = new AudioContext();
                var oscillator = context.createOscillator();
                oscillator.type = "sine";
                oscillator.frequency.value = 800;
                oscillator.connect(context.destination);
                oscillator.start();
                // Beep for 50 milliseconds
                setTimeout(function() {
                    oscillator.stop();
                }, 50);
            }
        </script>
        <script>
            var discount = 0;
            var discount_val = 0;
            var discount_method;

            var vat = 0;
            var vat_val = 0;
            var vat_method;

            var customer = 0;
            var table = 0;
            var room = 0;

            var kitchen_note
            var bar_note
            var staff_note
            var payment_note


            $(document).ready(function() {

                // When modal is hidden (closed), capture the input value
                $('#commonModal').on('hidden.bs.modal', function() {
                    kitchen_note = $('#kitchen_note').val();
                    bar_note = $('#bar_note').val();
                    staff_note = $('#staff_note').val();
                    payment_note = $('#payment_note').val();
                });


                // client-side filtering of products
                $('#searchInput').on('input', function() {
                    $('#loader').removeClass('d-none');

                    var searchText = $(this).val().toLowerCase();
                    var noResultsMessage = $('#noResultsMessage');

                    // Hide all meal items initially
                    $('.meal-item').hide();

                    $('.meal-item').each(function() {
                        var mealName = $(this).find('.meal-name');
                        var mealNameText = mealName.text().toLowerCase();

                        if (mealNameText.includes(searchText)) {
                            // Highlight matching text
                            var highlightedText = mealNameText.replace(new RegExp(searchText, 'gi'),
                                match => `<span class="highlight">${match}</span>`);
                            mealName.html(highlightedText);

                            $(this).show(); // Show matching meal item
                        } else {
                            mealName.html(mealNameText); // Reset original text
                        }
                    });

                    // Show no results message if no items are displayed
                    if ($('.meal-item:visible').length === 0) {
                        noResultsMessage.show();
                    } else {
                        noResultsMessage.hide();
                    }
                    $('#loader').addClass('d-none');

                });

                $('.category-item').click(function() {
                    $('#loader').removeClass('d-none');

                    var categoryId = $(this).data('id');
                    // Hide all menu items initially
                    $('.meal-item').hide();

                    if (categoryId === 0) {
                        // Show all menu items if categoryId is 0
                        $('.meal-item').show();
                    } else {
                        // Show menu items related to the selected category
                        $('.meal-item').each(function() {
                            var itemCategoryId = $(this).data('category');
                            if (itemCategoryId === categoryId) {
                                $(this).show();
                            }
                        });
                    }

                    // Show no results message if no items are displayed
                    if ($('.meal-item:visible').length === 0) {
                        $('#noResultsMessage').show();
                    } else {
                        $('#noResultsMessage').hide();
                    }
                    $('#loader').addClass('d-none');
                });


                $(document).on('change', '#customer', function() {
                    $('#customer-btn #name').text($(this).find(':selected').data('name'));
                    customer = $(this).find(':selected').val();

                    // Update the data-url attribute with the customer variable
                    var binding = "?customer=" + customer;
                    $('#customer-btn').attr('data-binding', binding); // Update the HTML attribute
                });

                $(document).on('change', '.table', function() {
                    $('#table-btn #name').text($(this).data('name'));
                    table = $(this).val();
                    // Update the data-url attribute with the table variable
                    var binding = "?table=" + table;
                    $('#table-btn').attr('data-binding', binding); // Update the HTML attribute
                });
                $(document).on('change', '.room', function() {
                    $('#room-btn #name').text($(this).data('name'));
                    room = $(this).val();
                    // Update the data-url attribute with the room variable
                    var binding = "?room=" + room;
                    $('#room-btn').attr('data-binding', binding); // Update the HTML attribute
                });

            });
        </script>
        <script>
            var cart = [];

            var sub = 0;
            var total = 0;

            function loadCart() {
                sub = 0;
                total = 0;
                $('#loader').removeClass('d-none');
                beep()

                $('#emptyCart').hide();
                // Assuming cart is an array of objects with 'name' and 'price' properties
                var cartItemHtml = '';
                cart.forEach((element, key) => {

                    sub += element.price * element.quantity;

                    // Add price for each modifier
                    if (element.modifiers && element.modifiers.length > 0) {
                        element.modifiers.forEach(modifier => {
                            sub += modifier.price * element.quantity;
                        });
                    }


                    // Create the HTML structure for each cart item
                    cartItemHtml += `
                            <div class="row p-2 align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <img loading="lazy" src="${element.image}" alt="Image of ${element.name}"
                                                class="img-fluid rounded-3 card-img w-50">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="row justify-content-center">
                                                <div class="col-md-11">
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-dark btn-sm decrementBtn" type="button" id="decrementBtn" data-id="${key}">-</button>
                                                        <input type="text" class="form-control form-control-sm text-center p-1 spinnerInput_${key}"
                                                            id="spinnerInput" value="${element.quantity}" readonly>
                                                        <button class="btn btn-dark btn-sm incrementBtn" type="button" id="incrementBtn"  data-id="${key}">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 mt-2 mt-md-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 text-start">
                                            <h5 class="card-title">${element.name}</h5>
                                            <div class="border-bottom pb-1 mb-1">
                                                <span class="price_${key}" data-price="${element.price}">{{ $settings->currency }} ${element.price.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}</span>
                                                <span class="quantity_${key}">x ${element.quantity}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <h4 class="total_${key}">{{ $settings->currency }} ${(element.price * element.quantity).toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}</h4>
                                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                                    <a href="javascript:void(0)" class="link-info fs-5" data-ajax-popup="true"
                                                                data-title="Add Modifiers" data-binding="?id=${element.id}"
                                                                data-url="{{ route('restaurant.modifiers') }}"><i class="bi bi-node-plus"
                                                            data-bs-toggle="tooltip" title="Modify"></i></a>
                                                    <a href="javascript:void(0)"  class="link-danger deleteBtn" data-key="${key}"><i class="bi bi-trash3" data-bs-toggle="tooltip"
                                                            title="Delete"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    // Append the HTML for the cart item to the cartBody element
                });
                $('#cartBody').html(cartItemHtml);

                if (cartItemHtml == '') {
                    $('#emptyCart').show();
                }

                $('#sub').html(
                    `{{ $settings->currency }} ${sub.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}`
                );

                $('#sub').val(sub)

                //discount
                if (discount_method == 'precentage') {
                    $('#discount_method_html').html(`${discount_val}%`);
                    discount = sub * discount_val / 100;
                } else {
                    discount = parseFloat(discount_val);
                    $('#discount_method_html').html(
                        `LKR ${discount_val}`
                    );
                }

                // // Update the data-url attribute with the discount variable and the method
                var binding = "?discount=" + discount_val + "&discount_method=" + discount_method;
                $('#discount-edit').attr('data-binding', binding); // Update the HTML attribute

                $('#discount_html').html(
                    `{{ $settings->currency }} ${discount.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}`
                );


                //vat
                if (vat_method == 'precentage') {
                    $('#vat_method_html').html(`${vat_val}%`);
                    vat = sub * vat_val / 100;
                } else {
                    vat = parseFloat(vat_val);
                    $('#vat_method_html').html(
                        `LKR ${vat_val}`
                    );
                }

                // // Update the data-url attribute with the vat variable and the method
                var binding = "?vat=" + vat_val + "&vat_method=" + vat_method;
                $('#vat-edit').attr('data-binding', binding); // Update the HTML attribute

                $('#vat_html').html(
                    `{{ $settings->currency }} ${vat.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}`
                );

                if (sub > 0) {
                    total = sub - discount + vat;
                } else {
                    total = 0;
                }

                $('#total').html(
                    `{{ $settings->currency }} ${total.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}`
                );
                $('#total').val(total)


                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                    tooltipTriggerEl))
                $('#loader').addClass('d-none');

            }
            $(document).ready(function() {

                $(document).on('click', '.meal-item', function() {
                    $('#loader').removeClass('d-none');

                    var id = $(this).data('id');
                    var mealName = $(this).find('.meal-name');
                    mealName = mealName.text();
                    var image = $(this).data('image');
                    var price = $(this).data('price');
                    var quantity = 1;

                    if (cart.find(e => e.id == id)) {
                        display_error('Item is already in the cart');
                        $('#loader').addClass('d-none');
                        return;
                    }

                    cart.push({
                        id: id,
                        name: mealName,
                        image: image,
                        price: price,
                        quantity: quantity,
                    });
                    $('#loader').addClass('d-none');
                    loadCart();
                });

                $(document).on('click', '.deleteBtn', function(e) {
                    e.preventDefault();
                    $('#loader').removeClass('d-none');

                    var keyToDelete = $(this).data('key');
                    cart.splice(keyToDelete, 1);
                    $('#loader').addClass('d-none');
                    loadCart();
                });

                // Decrement function
                $(document).on('click', '.decrementBtn', function() {
                    let id = $(this).data('id');
                    let value = parseInt($(`.spinnerInput_${id}`).val());
                    if (value > 1) {
                        value--;
                        $(`.spinnerInput_${id}`).val(value);
                    }
                    cart[id]['quantity'] = value;
                    var price = cart[id]['price'];
                    loadCart()
                });

                // Increment function
                $(document).on('click', '.incrementBtn', function() {
                    let id = $(this).data('id');
                    let value = parseInt($(`.spinnerInput_${id}`).val());
                    value++;
                    $(`.spinnerInput_${id}`).val(value);
                    cart[id]['quantity'] = value;
                    var price = cart[id]['price'];
                    loadCart()
                });


            });
        </script>

        <script>
            function checkout() {
                beep();
                $('#loader').removeClass('d-none');

                if (cart.length == 0) {
                    display_error('Add one or more items to the cart');
                    $('#loader').addClass('d-none');
                    return
                }

                var formData = new FormData();
                formData.append('cart', JSON.stringify(cart));
                formData.append('customer', customer);
                formData.append('room', room);
                formData.append('table', table);
                formData.append('sub', sub);
                formData.append('discount', discount);
                formData.append('vat', vat);
                formData.append('total', total);
                formData.append('kitchen_note', kitchen_note);
                formData.append('bar_note', bar_note);
                formData.append('staff_note', staff_note);
                formData.append('payment_note', payment_note);
                formData.append('type', $('#type').val());
                // alert($('#type').val());

                $.ajax({
                    type: 'POST',
                    url: "{{ route('restaurant.checkout') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response.message); // replace with your success message
                            setTimeout(function() {
                                window.location.href = response.url
                            }, 1500);
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            window.open(response.url, '_blank');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            }
        </script>

        <script>
            var previouslyClickedItem = null;

            function highlightCategoryItem(item) {
                // Check if there's a previously clicked item
                if (previouslyClickedItem && previouslyClickedItem !== item) {
                    // Reset styles of the previously clicked item
                    previouslyClickedItem.style.color = 'black';
                    previouslyClickedItem.style.backgroundColor = '#F5F5F5';
                }

                // Apply styles to the clicked item
                item.style.color = 'white';
                item.style.backgroundColor = '#318CE7';

                // Update the previously clicked item to the current item
                previouslyClickedItem = item;


            }
        </script>
    @endsection
