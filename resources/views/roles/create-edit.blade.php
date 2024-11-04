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
                <form method="POST" class="ajax-form"
                    action="{{ $is_edit ? route('roles.update', $data->id) : route('roles.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-12 mb-3 required">
                            <label for="" class="form-label">Role Name</label>
                            <input type="text" name="name" id="" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter role name" />
                        </div>
                    </div>
                    <div class="row">
                        <p>Assign Permissions to Role</p>
                        <div class="form-group col-12">
                            <div class="table-responsive">
                                <table class="table dt-responsive nowrap align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="25%">Module</th>
                                            <th>Permission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $main = [
                                                'users',
                                                'roles',
                                                'employees',
                                                'customers',
                                                'suppliers',
                                                'products',
                                                'categories',
                                                'units',
                                                'purchases',
                                                'pos',
                                                'kitchen',
                                                'bar',
                                                'tables',
                                                // 'table arrangements',
                                                'meals',
                                                'ingredients',
                                                'modifiers',
                                                'rooms',
                                                'bookings',
                                                'orders',
                                                'report',
                                                'settings',
                                            ];
                                        @endphp
                                        @foreach ($main as $moduleName)
                                            <tr>
                                                <td scope="row">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input me-2 all" type="checkbox"
                                                            id="{{ $moduleName }}" data-id="{{ $moduleName }}">
                                                        <label class="form-check-label" for="{{ $moduleName }}">
                                                            {{ ucfirst($moduleName) }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    @foreach ($permissions as $permission)
                                                        @php
                                                            $words = explode(' ', $permission->name);
                                                            $action = array_shift($words); // Get the action word
                                                            $permissionModuleName = implode(' ', $words); // Get the module name
                                                        @endphp
                                                        @if ($permissionModuleName === $moduleName)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input me-2" type="checkbox"
                                                                    id="{{ $action . $moduleName }}" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    data-id="{{ $moduleName }}"
                                                                    {{ $is_edit && in_array($permission->name, $data->permissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="{{ $action . $moduleName }}">
                                                                    @php
                                                                        $action = explode('_', $action);
                                                                        $action = implode(' ', $action);
                                                                    @endphp
                                                                    {{ ucfirst($action) }}
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('roles.index') }}'">Cancel</button>
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
        $(document).ready(function() {
            $('.all').change(function() {
                var id = $(this).data('id');
                var checkboxes = $('input[type="checkbox"][data-id="' + id + '"]');
                if ($(this).prop('checked')) {
                    checkboxes.each(function() {
                        $(this).prop('checked',
                            true); // Uncheck the checkbox if it's already checked
                    });
                } else {
                    checkboxes.each(function() {
                        $(this).prop('checked',
                            false); // Check the checkbox if it's not already checked
                    });

                }
            });
        });
    </script>
@endsection
