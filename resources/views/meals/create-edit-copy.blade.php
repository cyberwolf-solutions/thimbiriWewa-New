@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('css')
    <!-- Filepond stylesheet -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <style>
        .filepond--panel-root {
            background-color: var(--vz-secondary-bg);
            !important;
        }

        .filepond--drop-label {
            color: var(--vz-body-color) !important;
        }
    </style>
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
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form"
                    action="{{ $is_edit ? route('modifiers.update', $data->id) : route('modifiers.store') }}">
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
                            <select name="category" id="category" class="form-control js-example-basic-single">
                                <option value="">Select...</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 required">
                            <label for="" class="form-label">Price</label>
                            <input type="number" name="name" step="any" id="" class="form-control"
                                value="{{ $is_edit ? $data->price : '' }}" placeholder="Enter Price" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Image</label>
                            <input type="file" accept="images/*" class="form-control filepond" name="filepond">
                        </div>
                    </div>
                    <div class="required mt-2">
                        <label for="">Ingredients</label>
                        <div class="row mb-4">
                            <select name="ingredient" id="ingredient" class="form-control js-example-basic-single">
                                <option value="">Select...</option>
                            </select>
                        </div>
                        <div class="row mb-4 px-3">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rate</th>
                                    <th width="10%">Quantity</th>
                                    <th>Total Cost</th>
                                    <th width="5%"></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td>Test Ingredient</td>
                                        <td>{{ $settings->currency }} 0.00</td>
                                        <td>
                                            <input type="number" min="1" value="1" step="any"
                                                class="form-control bg-transparent">
                                        </td>
                                        <td>{{ $settings->currency }} 0.00</td>
                                        <td>
                                            <button class="btn bg-transparent border-0 text-danger">
                                                <i class="ri-delete-bin-2-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3 required">
                            <label for="" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="" rows="3" required>{{ $is_edit ? $data->description : '' }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('modifiers.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Load FilePond library -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <!-- Turn all file input elements into ponds -->
    <script>
        FilePond.parse(document.body);
    </script>
@endsection
