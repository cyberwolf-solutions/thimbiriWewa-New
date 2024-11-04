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
                    {{-- @can('create purchases')
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                            title="Create">
                            <i class="ri-add-line"></i>
                        </a>
                    @endcan --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 px-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <img src="{{ 'storage/' . $settings->logo_dark }}" class="card-logo card-logo-dark"
                                        alt="logo dark" height="67">
                                    <img src="{{ asset('storage/' . $settings->logo_light) }}"
                                        class="card-logo card-logo-light" alt="logo light" height="67">
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <p class="text-muted mb-1">
                                        <span>Email:</span>
                                        <a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a>
                                    </p>
                                    <p class="text-muted mb-0"><span>Contact No:</span> <a
                                            href="tel:{{ $settings->contact }}">{{ $settings->contact }}</a></p>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div>
                    <!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Purchase No</p>
                                    <h5 class="fs-15 mb-0">#<span
                                            id="invoice-no">{{ $settings->purchase($data->id) }}</span></h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-15 mb-0"><span
                                            id="invoice-date">{{ \Carbon\Carbon::parse($data->date)->format($settings->date_format) }}</span>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                    <?php $color = ''; ?>
                                    <?php $text = ''; ?>
                                    @if ($data->payment_status == 'Unpaid')
                                        <?php $color = 'bg-primary-subtle'; ?>
                                        <?php $text = 'text-primary'; ?>
                                    @elseif($data->payment_status == 'Partially Paid')
                                        <?php $color = 'bg-warning-subtle'; ?>
                                        <?php $text = 'text-warning'; ?>
                                    @elseif($data->payment_status == 'Paid')
                                        <?php $color = 'bg-success-subtle'; ?>
                                        <?php $text = 'text-success'; ?>
                                    @endif
                                    <span class="badge {{ $color }} {{ $text }} fs-11"
                                        id="payment-status">{{ $data->payment_status }}</span>
                                </div>
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Amount</p>
                                    <h5 class="fs-15 mb-0">{{ $settings->currency }} <span
                                            id="total-amount">{{ number_format($data->total, 2) }}</span></h5>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>

                    <!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;">#</th>
                                            <th scope="col" class="text-start">Product Details</th>
                                            <th scope="col">Rate</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-list">
                                        @foreach ($data->items as $key => $item)
                                            <tr>
                                                <th scope="row">{{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}</th>
                                                <td class="text-start">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td>{{ $settings->currency }} {{ $item->price }}</td>
                                                <td> {{ $item->quantity }}</td>
                                                <td class="text-end">{{ $settings->currency }} {{ $item->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>
                            <div class="border-top border-top-dashed mt-2">
                                <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                    style="width:250px">
                                    <tbody>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td class="text-end">{{ $settings->currency }}
                                                {{ number_format($data->sub_total, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Discount</td>
                                            <td class="text-end">{{ $settings->currency }}
                                                {{ number_format($data->discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>VAT ({{ $data->vat }}%)</td>
                                            <td class="text-end">{{ $settings->currency }}
                                                {{ number_format($data->vat_amount, 2) }}</td>
                                        </tr>
                                        <tr class="border-top border-top-dashed  fs-15">
                                            <th scope="row">Total Amount</th>
                                            <th class="text-end">{{ $settings->currency }}
                                                {{ number_format($data->total, 2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end table-->

                                <!--end table-->
                            </div>
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <a href="javascript:window.print()" class="btn btn-info"><i
                                        class="ri-printer-line align-bottom me-1"></i> Print</a>
                            </div>
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end col-->
                </div>
            </div>
        </div>

    </div>
@endsection
