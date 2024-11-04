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
                    @can('create purchases')
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                            title="Create">
                            <i class="ri-add-line"></i>
                        </a>
                    @endcan
                    {{-- <a href="{{route('purchase.Reports')}}" >
                        <button  class="btn btn-border btn-danger">Reports</button>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle" id="example">
                        <thead class="table-light">
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td>{{ $settings->purchase($item->id) }}</td>
                                    <td>{{ $item->supplier->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format($settings->date_format) }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item->total, 2) }}</td>
                                    <td>{{ $item->payment_status }}</td>
                                    <td>
                                        @can('view purchases')
                                            <a href="{{ route('purchases.show', [$item->id]) }}"
                                                class="btn btn-light btn-sm small btn-icon text-white">
                                                <i class="bi bi-eye" data-bs-toggle="tooltip" title="View"></i>
                                            </a>
                                        @endcan
                                        @can('edit purchases')
                                            <a href="{{ route('purchases.edit', [$item->id]) }}"
                                                class="btn btn-secondary btn-sm small btn-icon">
                                                <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                            </a>
                                        @endcan
                                        @can('add-payments purchases')
                                            <a href="javascript:void(0)"
                                                data-url="{{ route('purchases.payment', ['id' => encrypt($item->id)]) }}"
                                                data-title="Add Purchase Payment" data-location="centered"
                                                data-ajax-popup="true" data-bs-toggle="tooltip" title="Add Payment"
                                                class="btn btn-sm btn-info text-dark"><i class="bi bi-currency-dollar"></i>
                                            </a>
                                        @endcan
                                        @can('view-payments purchases')
                                            <a href="javascript:void(0)" data-url="{{ route('purchases.payments.view') }}"
                                                data-title="Purchase Payments" data-size="lg" data-location="centered"
                                                data-ajax-popup="true" data-bs-toggle="tooltip" title="View Payments"
                                                class="btn btn-sm btn-soft-warning"><i class="mdi mdi-cash-multiple"></i>
                                            </a>
                                        @endcan
                                        @can('delete purchases')
                                            <a href="javascript:void(0)"
                                                data-url="{{ route('purchases.destroy', [$item->id]) }}"
                                                class="btn btn-danger btn-sm small btn-icon delete_confirm">
                                                <i class="bi bi-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
