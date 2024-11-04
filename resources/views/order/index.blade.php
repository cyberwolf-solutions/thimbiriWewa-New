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
                    <form action="" id="form">
                        <div class="row">
                            <div class="col">
                                <select class="form-select" name="status" id="" onchange="$('#form').submit()">
                                    <option value="" {{ $status == '' ? 'selected' : '' }}>All</option>
                                    <option value="Pending"{{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="InProgress"{{ $status == 'InProgress' ? 'selected' : '' }}>In Progress
                                    </option>
                                    <option value="Complete"{{ $status == 'Complete' ? 'selected' : '' }}>Completed
                                    </option>
                                </select>
                            </div>

                            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                                title="Refresh">
                                <i class="ri-restart-line"></i>
                            </a>
                        </div>
                    </form>
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
                            <th>Guest</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Table Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td>#{{ $settings->invoice($item->id) }}</td>
                                    @if ($item->customer_id == 0)
                                        <td>Walking Customer</td>
                                    @else
                                        <td>{{ $item->customer->name }}</td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($item->order_date)->format($settings->date_format) }}</td>
                                    <td>{{ $settings->currency }}
                                        {{ number_format($item->payment ? $item->payment->total : 0, 2) }}
                                    </td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->table_id != 0 ? $item->table->availability : 'No Table' }}</td>
                                    <td>
                                        @can('view orders')
                                            <a href="javascript:void(0)" data-url="{{ route('orders.show', [$item->id]) }}"
                                                data-title="View Order" data-size="xl" data-location="centered"
                                                data-ajax-popup="true" data-bs-toggle="tooltip" title="View Order"
                                                class="btn btn-sm btn-light"><i class="mdi mdi-eye"></i>
                                            </a>
                                        @endcan
                                        <a href="{{ route('order.print', [$item->id]) }}" target="__blank"
                                            class="btn btn-sm btn-soft-warning ms-1" data-bs-toggle="tooltip"
                                            title="Print">
                                            <i class="mdi mdi-printer"></i>
                                        </a>
                                        @if ($item->table_id != 0)
                                            <a href="javascript:void(0)" data-url="{{ route('order.complete') }}"
                                                data-data='{"id":{{ $item->id }}}'
                                                class="btn btn-sm btn-soft-success ms-1 send-post-ajax"
                                                data-bs-toggle="tooltip" title="Complete">
                                                <i class="mdi mdi-check"></i>
                                            </a>
                                        @endif
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
