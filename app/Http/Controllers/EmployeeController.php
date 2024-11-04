<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDesignation;
use App\Models\Settings;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Employees';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Employee::all();
        return view('employees.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Employees';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('employees.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;
        $designations = EmployeeDesignation::all();

        return view('employees.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact_primary' => 'required|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'contact_secondary' => 'nullable|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'email' => 'nullable|email|max:255|unique:employees,email',
            'nic' => 'required|string|max:15|unique:employees,nic',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'emergency_name' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'designation' => 'required',
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
                'fname' => $request->fname,
                'lname' => $request->lname,
                'contact_primary' => $request->contact_primary,
                'contact_secondary' => $request->contact_secondary,
                'email' => $request->email,
                'nic' => $request->nic,
                'address' => $request->address,
                'city' => $request->city,
                'emergency_name' => $request->emergency_name,
                'emergency_contact' => $request->emergency_contact,
                'designation' => $request->designation,
                'created_by' => Auth::user()->id,
            ];

            $employee = Employee::create($data);

            return json_encode(['success' => true, 'message' => 'Employee created', 'url' => route('employees.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $data = Employee::find($id);

        $settings = Settings::latest()->first();

        // Check if the data is not found
        if (!$data) {
            return response()->json(['message' => 'Transfer not found.']);
        }

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td>Name: </td>';
        $html .= '<td>' . $data->fname . ' ' . $data->lname . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Contact Primary: </td>';
        $html .= '<td>' . $data->contact_primary . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Contact Secondary: </td>';
        $html .= '<td>' . $data->contact_secondary ?? '-' . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Designation: </td>';
        $html .= '<td>' . $data->designations->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Email: </td>';
        $html .= '<td>' . $data->email ?? '-' . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>NIC: </td>';
        $html .= '<td>' . $data->nic . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Address: </td>';
        $html .= '<td>' . $data->address . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>City: </td>';
        $html .= '<td>' . $data->city . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Emergency Name: </td>';
        $html .= '<td>' . $data->emergency_name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Emergency Contact: </td>';
        $html .= '<td>' . $data->emergency_contact . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created By: </td>';
        $html .= '<td>' . $data->createdBy->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created Date: </td>';
        $html .= '<td>' . date_format(new DateTime('@' . strtotime($data->created_at)), $settings->date_format) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return response()->json([$html]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Employees';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('employees.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $designations = EmployeeDesignation::all();
        $data = Employee::find($id);

        return view('employees.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact_primary' => 'required|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'contact_secondary' => 'nullable|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'email' => 'nullable|email|max:255|unique:employees,email,' . $id,
            'nic' => 'required|string|max:15|unique:employees,nic,' . $id,
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'emergency_name' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:15', // Assuming a maximum length of 15 characters for a phone number
            'designation' => 'required',
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
                'fname' => $request->fname,
                'lname' => $request->lname,
                'contact_primary' => $request->contact_primary,
                'contact_secondary' => $request->contact_secondary,
                'email' => $request->email,
                'nic' => $request->nic,
                'address' => $request->address,
                'city' => $request->city,
                'emergency_name' => $request->emergency_name,
                'emergency_contact' => $request->emergency_contact,
                'designation' => $request->designation,
                'updated_by' => Auth::user()->id,
            ];

            $employee = Employee::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Employee updated', 'url' => route('employees.index')]);
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

            $employee = Employee::find($id);
            $employee->update(['deleted_by' => Auth::user()->id]);
            $employee->delete();

            return json_encode(['success' => true, 'message' => 'Employee deleted', 'url' => route('employees.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
    public function employeesReport(){
        $title = 'Employee Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Employee::all();

        return view ('reports.employeesReport' , compact('data'));
    }
}
