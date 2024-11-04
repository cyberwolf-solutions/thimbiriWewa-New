<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Roles';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Role::all();

        return view('roles.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Roles';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('roles.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $data = Role::all();

        $permissions = Permission::all();

        $is_edit = false;

        return view('roles.create-edit', compact('title', 'breadcrumbs', 'data', 'is_edit', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {

            $role = Role::create(['name' => $request->input('name')]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->input('permissions'));
            }

            return json_encode(['success' => true, 'message' => 'Role created', 'url' => route('roles.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Roles';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('roles.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $data = Role::find($id);

        $permissions = Permission::all();

        $is_edit = true;

        return view('roles.create-edit', compact('title', 'breadcrumbs', 'data', 'is_edit', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {

            $role = Role::find($id);
            $role->update(['name' => $request->input('name')]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->input('permissions'));
            } else {
                // If no permissions are selected, you may want to sync an empty array.
                $role->syncPermissions([]);
            }

            return json_encode(['success' => true, 'message' => 'Role updated', 'url' => route('roles.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        try {

            $role = Role::find($id);

            if (!$role) {
                return redirect()->route('roles.index')->with('error', 'Role not found');
            }

            // Detach all permissions associated with the role
            $role->syncPermissions([]);

            // Delete the role
            $role->delete();

            return json_encode(['success' => true, 'message' => 'Role deleted', 'url' => route('roles.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
