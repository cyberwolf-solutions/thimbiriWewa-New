<?php

use App\Http\Controllers\BarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\ModifiersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuffetController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BordingTypeCOntroller;
use App\Http\Controllers\CheckinCheckoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DailyStockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableArrangementsController;
use App\Http\Controllers\EmployeeDesignationsController;
use App\Http\Controllers\RoomFacilityController;
use App\Http\Controllers\RoomSizeController;
use App\Http\Controllers\RoomTypesController;
use App\Http\Controllers\StockController;
use App\Models\CustomerType;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
// return view('welcome');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
});
Auth::routes();

Route::middleware(['auth'])->group(function () {

    //reports
    // Route::get('/users/report', [UserController::class, 'userReport'])->name('users.Reports')->middleware('can:manage report');
    // Route::get('/customers/report', [CustomerController::class, 'customerReport'])->name('customers.Reports')->middleware('can:manage report');
    // Route::get('/employees/report', [EmployeeController::class, 'employeesReport'])->name('employees.Reports')->middleware('can:manage report');
    // Route::get('/suppliers/report', [SupplierController::class, 'suppliersReport'])->name('suppliers.Reports')->middleware('can:manage report');
    // Route::get('/purchase/report', [PurchaseController::class, 'purchaseReport'])->name('purchase.Reports')->middleware('can:manage report');
    // Route::get('/product/report', [ProductController::class, 'productReport'])->name('product.Reports')->middleware('can:manage report');
    // Route::get('/booking/report', [BookingController::class, 'bookingReport'])->name('booking.Reports')->middleware('can:manage report');

    Route::get('/user', [ReportsController::class, 'user'])->name('users.ReportsIndex')->middleware('can:manage report');
    Route::get('/customer', [ReportsController::class, 'customer'])->name('customers.ReportsIndex')->middleware('can:manage report');
    Route::get('/employee', [ReportsController::class, 'employee'])->name('employees.ReportsIndex')->middleware('can:manage report');
    Route::get('/supplier', [ReportsController::class, 'supplier'])->name('supplier.ReportsIndex')->middleware('can:manage report');
    Route::get('/purchase', [ReportsController::class, 'purchase'])->name('purchase.ReportsIndex')->middleware('can:manage report');
    Route::get('/product', [ReportsController::class, 'product'])->name('product.ReportsIndex')->middleware('can:manage report');
    Route::get('/booking', [ReportsController::class, 'booking'])->name('booking.ReportsIndex')->middleware('can:manage report');
    Route::get('/order', [ReportsController::class, 'order'])->name('order.ReportsIndex')->middleware('can:manage report');
    Route::get('/search-by-type', [ReportsController::class, 'searchByType'])->name('search.by.type');




    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/change/mode', [UserController::class, 'changeMode'])->name('change.mode');
    Route::resource('settings', SettingsController::class)->middleware('can:manage settings');
    Route::resource('users', UserController::class)->middleware('can:manage users');
    Route::post('/change-status', [UserController::class, 'status'])->name('users.status');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    Route::resource('roles', RoleController::class)->middleware('can:manage roles');
    Route::post('/update-settings', [SettingsController::class, 'updateSettings'])->name('update-settings');
    Route::post('/update-mail', [SettingsController::class, 'updateMail'])->name('update-mail');
    Route::resource('employees', EmployeeController::class)->middleware('can:manage employees');
    Route::resource('employee-designations', EmployeeDesignationsController::class)->middleware('can:manage employees');
    Route::resource('customers', CustomerController::class)->middleware('can:manage customers');
    Route::resource('suppliers', SupplierController::class)->middleware('can:manage suppliers');
    Route::resource('purchases', PurchaseController::class)->middleware('can:manage purchases');
    Route::get('/purchase-payment/{id}', [PurchaseController::class, 'viewAddPayment'])->name('purchases.payment');
    Route::post('/purchase-payment', [PurchaseController::class, 'addPayment'])->name('purchases.payment.add');
    Route::get('/purchase-payments/view/', [PurchaseController::class, 'viewPayments'])->name('purchases.payments.view');
    Route::resource('categories', CategoryController::class)->middleware('can:manage categories');
    Route::resource('units', UnitController::class)->middleware('can:manage units');
    Route::resource('ingredients', IngredientsController::class)->middleware('can:manage ingredients');
    Route::resource('products', ProductController::class)->middleware('can:manage products');
    Route::resource('meals', MealsController::class)->middleware('can:manage meals');
    Route::resource('modifiers', ModifiersController::class)->middleware('can:manage modifiers');
    Route::resource('buffet', BuffetController::class)->middleware('can:manage modifiers');    
    // Route::resource('restaurant', RestaurantController::class)->middleware('can:manage pos');
    Route::resource('restaurant', RestaurantController::class);

    Route::get('/restaurant-note', [RestaurantController::class, 'note'])->name('restaurant.note');
    Route::get('/restaurant-in-process', [RestaurantController::class, 'process'])->name('restaurant.process');
    Route::get('/restaurant-tables', [RestaurantController::class, 'tables'])->name('restaurant.tables');
    Route::get('/restaurant-rooms', [RestaurantController::class, 'rooms'])->name('restaurant.rooms');
    Route::get('/restaurant-customer', [RestaurantController::class, 'customer'])->name('restaurant.customer');
    Route::get('/restaurant-customer-add', [RestaurantController::class, 'customerAdd'])->name('restaurant.customer-add');
    Route::get('/restaurant-discount', [RestaurantController::class, 'discount'])->name('restaurant.discount');
    Route::get('/restaurant-vat', [RestaurantController::class, 'vat'])->name('restaurant.vat');
    Route::get('/restaurant-service', [RestaurantController::class, 'service'])->name('restaurant.service');
    Route::get('/restaurant-modifiers', [RestaurantController::class, 'modifiers'])->name('restaurant.modifiers');
    Route::post('/restaurant/checkout', [RestaurantController::class, 'checkout'])->name('restaurant.checkout');
    Route::resource('kitchen', KitchenController::class)->middleware('can:manage kitchen');
    Route::resource('bar', BarController::class)->middleware('can:manage bar');
    Route::resource('tables', TablesController::class)->middleware('can:manage tables');
    Route::resource('table-arrangements', TableArrangementsController::class)->middleware('can:manage table-arrangements');
    Route::resource('orders', OrderController::class)->middleware('can:manage orders');
    Route::get('order/print/{id}', [OrderController::class, 'print'])->middleware('can:manage orders')->name('order.print');
    Route::get('order/printk/{id}', [OrderController::class, 'printk'])->middleware('can:manage orders')->name('order.printk');
    Route::get('kot/print/{id}', [KitchenController::class, 'print'])->name('kot.print');
    Route::post('kot/delete/{id}', [KitchenController::class, 'destroy'])->name('kot.delete');
    Route::get('bot/print/{id}', [BarController::class, 'print'])->name('bot.print');
    Route::resource('rooms', RoomController::class)->middleware('can:manage rooms');
    Route::resource('room-types', RoomTypesController::class)->middleware('can:manage rooms');
    Route::resource('bookings', BookingController::class)->middleware('can:manage bookings');
    Route::get('get-ingredients', [IngredientsController::class, 'getIngredients'])->name('get-ingredients');
    Route::get('get-product-ingredients', [IngredientsController::class, 'getProductIngredients'])->name('get-product-ingredients');
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
    Route::get('get-meal-products', [ProductController::class, 'getMealProducts'])->name('get-meal-products');
    Route::get('get-modifier-categories', [CategoryController::class, 'getModifierCategories'])->name('get-modifier-categories');
    Route::get('get-modifier-ingredients', [IngredientsController::class, 'getModifierIngredients'])->name('get-modifier-ingredients');
    Route::get('get-booking-customers', [BookingController::class, 'getBookingCustomers'])->name('get-booking-customers');
    Route::get('get-booking-rooms', [BookingController::class, 'getBookingRooms'])->name('get-booking-rooms');
    Route::get('check-availability', [BookingController::class, 'checkAvailability'])->name('check-availability')->middleware('can:manage bookings');
    Route::get('get-available-rooms', [BookingController::class, 'getAvailableRooms'])->name('get-available-rooms');
    Route::get('get-booking-customers', [BookingController::class, 'getBookingCustomers'])->name('get-booking-customers');
    Route::post('complete-meal', [RestaurantController::class, 'completeMeal'])->name('complete-meal');
    Route::post('order/complete', [RestaurantController::class, 'completeOrder'])->name('order.complete');
    Route::get('status', [BookingController::class, 'status'])->name('status')->middleware('can:manage bookings');

    //user profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'profileUpdate'])->name('profile.update');
    Route::post('/profile-image-update', [UserController::class, 'imageUpdate'])->name('image.update');
    Route::post('/profile-cover-update', [UserController::class, 'coverUpdate'])->name('cover.update');
    Route::post('/password-change', [UserController::class, 'passwordUpdate'])->name('password.change');



    //room size
    Route::resource('room-size', RoomSizeController::class)->middleware('can:manage rooms');
    Route::resource('room-facility', RoomFacilityController::class)->middleware('can:manage rooms');
    Route::resource('checkin', CheckinCheckoutController::class)->middleware('can:manage bookings');


    // routes/web.php
    Route::get('/get-booking-rooms/{customerId}', [CheckinCheckoutController::class, 'getBookingRooms'])->name('get.booking.rooms');
    Route::get('/get-room-facility/{facilityId}', [CheckinCheckoutController::class, 'getRoomFacility'])->name('get-room-facility');

    Route::resource('checkout', CheckoutController::class)->middleware('can:manage bookings');
    Route::get('/get-booking-payment-details/{bookingId}/{roomId}', [CheckoutController::class, 'getBookingPaymentDetails']);

    Route::get('/get-customer-orders/{customerId}/{roomId}', [CheckoutController::class, 'getCustomerOrders']);
    Route::get('/get-checkincheckout-id',  [CheckoutController::class, 'getCheckinCheckoutId'] )->name('get.checkincheckout.id');

    Route::get('/checkout/invoice/{checkincheckout_id}', [CheckoutController::class, 'invoice'])->name('checkout.invoice');
    Route::get('/checkout/invoicee/{checkincheckout_id}', [CheckoutController::class, 'invoicee'])->name('checkout.invoicee');
    Route::get('/checkout/additional/invoice/{customer_id}/{checkout_date}/{room_no}', [CheckoutController::class, 'additionalInvoice'])->name('checkout.additional.invoice');


    //bording type
    Route::resource('bording-type', BordingTypeCOntroller::class)->middleware('can:manage bording');


    Route::resource('stock', StockController::class)->middleware('can:manage stock');
    Route::resource('daily-stock', DailyStockController::class)->middleware('can:manage dailystock');


    Route::get('/customer-type', [CustomerTypeController::class, 'index'])->name('customer.type');
    Route::post('/customer-type-add', [CustomerTypeController::class, 'store'])->name('customertype.add');
    Route::get('/customer-type-index', [CustomerTypeController::class, 'create'])->name('customertype.index');
    Route::delete('/customer-type-delete/{checkincheckout_id}', [CustomerTypeController::class, 'destroy'])->name('customerstype.destroy');
    Route::delete('/buffet-delete/{checkincheckout_id}', [BuffetController::class, 'destroy'])->name('buffet.destroy');
    Route::get('/buffet-edit/{checkincheckout_id}', [BuffetController::class, 'update'])->name('buffet.edit');













    // Route::delete('/checkin/{id}', [CheckinCheckoutController::class , 'destroy'])->name('checkin.destroy');



    // Route::get('/roomsize' , [RoomSizeController::class , 'index'])->name('roomsize.index');
    // Route::post('/roomsize' , [RoomSizeController::class , 'store'])->name('roomsize.store');
    // Route::put('/roomsize/update/{id}' ,[RoomSizeController::class, 'update'])->name('roomsize.update');
});
