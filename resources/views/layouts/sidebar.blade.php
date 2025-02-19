<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('storage/' . $settings->logo_dark) }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('storage/' . $settings->logo_dark) }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('home') }}">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
                <li class="menu-title"><span>Modules</span></li>
                @canany(['manage users', 'manage roles'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#user" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="user">
                            <i class="ri-account-circle-line"></i> <span>User</span>
                        </a>
                        <div class="collapse menu-dropdown" id="user">
                            <ul class="nav nav-sm flex-column">
                                @can('manage users')
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                                    </li>
                                @endcan
                                @can('manage roles')
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link">Roles</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['manage employees', 'manage customers'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#people" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="people">
                            <i class="ri-user-3-line"></i> <span>People</span>
                        </a>
                        <div class="collapse menu-dropdown" id="people">
                            <ul class="nav nav-sm flex-column">
                                @can('manage employees')
                                    <li class="nav-item">
                                        <a href="{{ route('employee-designations.index') }}" class="nav-link">Employee
                                            Designations</a>
                                    </li>
                                @endcan
                                @can('manage employees')
                                    <li class="nav-item">
                                        <a href="{{ route('employees.index') }}" class="nav-link">Employees</a>
                                    </li>
                                @endcan
                                @can('manage customers')
                                    <li class="nav-item">
                                        <a href="{{ route('customers.index') }}" class="nav-link">Guests</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('customer.type') }}" class="nav-link">Guest type</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['manage suppliers', 'manage purchases'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#purchase" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="purchase">
                            <i class="ri-book-3-line"></i> <span>Purchase</span>
                        </a>
                        <div class="collapse menu-dropdown" id="purchase">
                            <ul class="nav nav-sm flex-column">
                                @can('manage suppliers')
                                    <li class="nav-item">
                                        <a href="{{ route('suppliers.index') }}" class="nav-link">Suppliers</a>
                                    </li>
                                @endcan
                                @can('manage purchases')
                                    <li class="nav-item">
                                        <a href="{{ route('purchases.index') }}" class="nav-link">Purchases</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                @canany(['manage stock', 'manage dailystock'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#stock" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="stock">
                            <i class="mdi mdi-office-building"></i> <span>Stock</span>
                        </a>
                        <div class="collapse menu-dropdown" id="stock">
                            <ul class="nav nav-sm flex-column">
                                @can('manage stock')
                                    <li class="nav-item">
                                        <a href="{{ route('stock.index') }}" class="nav-link">Stock</a>
                                    </li>
                                @endcan
                                @can('manage dailystock')
                                    <li class="nav-item">
                                        <a href="{{ route('daily-stock.index') }}" class="nav-link">Kitchen</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan



                @canany(['manage categories', 'manage units'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#constants" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="constants">
                            <i class="ri-bubble-chart-line"></i> <span>Constants</span>
                        </a>
                        <div class="collapse menu-dropdown" id="constants">
                            <ul class="nav nav-sm flex-column">
                                @can('manage categories')
                                    <li class="nav-item">
                                        <a href="{{ route('categories.index') }}" class="nav-link">Categories</a>
                                    </li>
                                @endcan
                                @can('manage units')
                                    <li class="nav-item">
                                        <a href="{{ route('units.index') }}" class="nav-link">Units</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['manage ingredients', 'manage products', 'manage meals', 'manage modifiers'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#foods" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="foods">
                            <i class="mdi mdi-food-outline"></i> <span>Foods</span>
                        </a>
                        <div class="collapse menu-dropdown" id="foods">
                            <ul class="nav nav-sm flex-column">
                                @can('manage ingredients')
                                    <li class="nav-item">
                                        <a href="{{ route('ingredients.index') }}" class="nav-link">Ingredients</a>
                                    </li>
                                @endcan
                                @can('manage products')
                                    <li class="nav-item">
                                        <a href="{{ route('products.index') }}" class="nav-link">Products</a>
                                    </li>
                                @endcan
                                @can('manage meals')
                                    <li class="nav-item">
                                        <a href="{{ route('meals.index') }}" class="nav-link">Meals</a>
                                    </li>
                                @endcan
                                @can('manage modifiers')
                                    <li class="nav-item">
                                        <a href="{{ route('modifiers.index') }}" class="nav-link">Modifiers</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- @can('manage pos')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('restaurant.index') }}">
                            <i class="ri-menu-add-line"></i> <span>POS</span>
                        </a>
                    </li>
                @endcan --}}
                @canany(['manage pos', 'manage waiter'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('restaurant.index') }}">
                            <i class="ri-menu-add-line"></i> <span>POS</span>
                        </a>
                    </li>
                @endcanany


                @can('manage kitchen')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('kitchen.index') }}">
                            <i class="ri-menu-add-line"></i> <span>Kitchen</span>
                        </a>
                    </li>
                @endcan

                @canany(['manage orders', 'manage bar', 'manage tables', 'manage table-arrangements'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#restaurant" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="restaurant">
                            <i class="mdi mdi-food-fork-drink"></i> <span>Restaurant</span>
                        </a>
                        <div class="collapse menu-dropdown" id="restaurant">
                            <ul class="nav nav-sm flex-column">
                                @can('manage orders')
                                    <li class="nav-item">
                                        <a href="{{ route('orders.index') }}" class="nav-link">Orders</a>
                                    </li>
                                @endcan
                                {{-- @can('manage kitchen')
                                    <li class="nav-item">
                                        <a href="{{ route('kitchen.index') }}" class="nav-link">Kitchen</a>
                                    </li>
                                @endcan --}}
                                @can('manage bar')
                                    <li class="nav-item">
                                        <a href="{{ route('bar.index') }}" class="nav-link">Bar</a>
                                    </li>
                                @endcan
                                @can('manage tables')
                                    <li class="nav-item">
                                        <a href="{{ route('tables.index') }}" class="nav-link">Tables</a>
                                    </li>
                                @endcan
                                {{-- @can('manage table arrangements')
                                    <li class="nav-item">
                                        <a href="{{ route('table-arrangements.index') }}" class="nav-link">Table
                                            Arrangements</a>
                                    </li>
                                @endcan --}}
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['manage bookings', 'manage rooms'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#hotel" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="hotel">
                            <i class="mdi mdi-office-building"></i> <span>Hotel Reservations</span>
                        </a>
                        <div class="collapse menu-dropdown" id="hotel">
                            <ul class="nav nav-sm flex-column">
                                @can('manage bookings')
                                    <li class="nav-item">
                                        <a href="{{ route('check-availability') }}" class="nav-link">Check Availability</a>
                                    </li>
                                @endcan
                                @can('manage bookings')
                                    <li class="nav-item">
                                        <a href="{{ route('bookings.index') }}" class="nav-link">Bookings</a>
                                    </li>
                                @endcan

                                @can('manage bookings')
                                    <li class="nav-item">
                                        <a href="{{ route('checkin.index') }}" class="nav-link">Check In</a>
                                    </li>
                                @endcan

                                @can('manage bookings')
                                    <li class="nav-item">
                                        <a href="{{ route('checkout.index') }}" class="nav-link">Check Out</a>
                                    </li>
                                @endcan

                                @can('manage bookings')
                                    <li class="nav-item">
                                        <a href="{{ route('status') }}" class="nav-link">Room Status</a>
                                    </li>
                                @endcan
                                {{-- @can('manage rooms')
                                    <li class="nav-item">
                                        <a href="{{ route('room-types.index') }}" class="nav-link">Room Types</a>
                                    </li>
                                @endcan
                                @can('manage rooms')
                                    <li class="nav-item">
                                        <a href="{{ route('rooms.index') }}" class="nav-link">Rooms</a>
                                    </li>
                                @endcan --}}

                            </ul>
                        </div>
                    </li>
                @endcanany



                @can('manage rooms')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#roomFacilities" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="roomFacilities">
                            <i class="mdi mdi-office-building"></i> <span>Room Facilities</span>
                        </a>
                        <div class="collapse menu-dropdown" id="roomFacilities">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('room-facility.index') }}" class="nav-link">Facility List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- @can('manage bording')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#bordingtype" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="bordingtype">
                            <i class="mdi mdi-office-building"></i> <span>Boarding</span>
                        </a>
                        <div class="collapse menu-dropdown" id="bordingtype">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('bording-type.index') }}" class="nav-link">Boarding Type</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan --}}


                @canany(['manage rooms'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#room-settings" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="room-settings">
                            <i class="ri-settings-2-line"></i> <span>Room Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="room-settings">
                            <!-- Unique ID for Room Settings section -->
                            <ul class="nav nav-sm flex-column">
                                @can('manage rooms')
                                    <li class="nav-item">
                                        <a href="{{ route('rooms.index') }}" class="nav-link">Rooms</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('room-types.index') }}" class="nav-link">Room Types</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('room-size.index') }}" class="nav-link">Room Size</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bording-type.index') }}" class="nav-link">Boarding Type</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['manage report'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#report" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="purchase">
                            <i class="ri-book-3-line"></i> <span>Reports</span>
                        </a>
                        <div class="collapse menu-dropdown" id="report">
                            <ul class="nav nav-sm flex-column">
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('users.ReportsIndex') }}" class="nav-link">User Report</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('customers.ReportsIndex') }}" class="nav-link">Guest Reports</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('employees.ReportsIndex') }}" class="nav-link">Employees
                                            Reports</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('supplier.ReportsIndex') }}" class="nav-link">Supplier Reports</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('purchase.ReportsIndex') }}" class="nav-link">Purchase Reports</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('product.ReportsIndex') }}" class="nav-link">Product Reports</a>
                                    </li>
                                @endcan
                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('booking.ReportsIndex') }}" class="nav-link">Booking Reports</a>
                                    </li>
                                @endcan

                                @can('manage report')
                                    <li class="nav-item">
                                        <a href="{{ route('order.ReportsIndex') }}" class="nav-link">Order Reports</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endcanany

                @can('manage settings')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('settings.index') }}">
                            <i class="ri-settings-2-line"></i> <span>Settings</span>
                        </a>
                    </li>
                @endcan

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="">
                        <i class="ri-book-2-line"></i> <span>User Manual</span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
