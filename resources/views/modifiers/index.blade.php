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
                    @can('create modifiers')
                        <a href="{{ route('modifiers.create') }}" class="btn btn-primary btn-icon" data-bs-toggle="tooltip"
                            title="Create">
                            <i class="ri-add-line"></i>
                        </a>
                    @endcan
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
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item->unit_price, 2) }}</td>
                                    <td>
                                        @if(isset($item->categories))
                                            @can('manage categories')
                                                <a data-url="{{ route('get-modifier-categories') }}" data-id="{{ $item->id }}"
                                                   class="btn btn-info btn-sm small btn-icon text-dark show-cat-ingd">
                                                    <i class="mdi mdi-food-fork-drink" data-bs-toggle="tooltip" title="Show Categories"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @if(isset($item->ingredients))
                                            @can('manage ingredients')
                                                <a data-url="{{ route('get-modifier-ingredients') }}" data-id="{{ $item->id }}"
                                                   class="btn btn-info btn-sm small btn-icon text-dark show-cat-ingd">
                                                    <i class="mdi mdi-food" data-bs-toggle="tooltip" title="Show Ingredients"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @can('view modifiers')
                                            <a data-url="{{ route('modifiers.show', [$item->id]) }}"
                                                class="btn btn-light btn-sm small btn-icon text-white show-more">
                                                <i class="bi bi-eye" data-bs-toggle="tooltip" title="View"></i>
                                            </a>
                                        @endcan
                                        @can('edit modifiers')
                                            <a href="{{ route('modifiers.edit', [$item->id]) }}"
                                                class="btn btn-secondary btn-sm small btn-icon">
                                                <i class="bi bi-pencil-square" data-bs-toggle="tooltip" title="Edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete modifiers')
                                            <a href="javascript:void(0)"
                                                data-url="{{ route('modifiers.destroy', [$item->id]) }}"
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
@include('modifiers.view-ingredients-categories-modal')

@section('script')
    <script>
        $(document).on('click', '.show-cat-ingd', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "id": id
                },
                success: function(result) {
                    $('.ingredientCategoryBody').html(result);
                }
            });
            $('#viewIngredientCategoryModal').modal('show');
        });
    </script>
@endsection
