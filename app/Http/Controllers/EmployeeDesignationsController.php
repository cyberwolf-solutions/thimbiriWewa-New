<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeDesignationsController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Employee Designations';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = EmployeeDesignation::all();
        return view('employee-designations.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:employee_designations,name',
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
            $data = [
                'name' => $request->name,
                'created_by' => Auth::user()->id,
            ];

            $EmployeeDesignation = EmployeeDesignation::create($data);

            return json_encode(['success' => true, 'message' => 'Employee Designation created', 'url' => route('employee-designations.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:employee_designations,name,' . $id,
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
            $data = [
                'name' => $request->name,
                'updated_by' => Auth::user()->id,
            ];

            $EmployeeDesignation = EmployeeDesignation::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Employee Designation updated', 'url' => route('employee-designations.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        try {

            $EmployeeDesignation = EmployeeDesignation::find($id);
            $EmployeeDesignation->update(['deleted_by' => Auth::user()->id]);
            $EmployeeDesignation->delete();

            return json_encode(['success' => true, 'message' => 'Employee Designation deleted', 'url' => route('employee-designations.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
