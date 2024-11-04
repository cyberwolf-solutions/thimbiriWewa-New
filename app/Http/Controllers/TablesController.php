<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use DateTime;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TablesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Tables';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Table::all();
        return view('tables.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Tables';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('tables.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('tables.create-edit', compact('title', 'breadcrumbs', 'is_edit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tables,name',
            'capacity' => 'required',
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
                'capacity' => $request->capacity,
                'availability' => 'Available',
                'created_by' => Auth::user()->id,
            ];

            $Table = Table::create($data);

            return json_encode(['success' => true, 'message' => 'Table created', 'url' => route('tables.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $data = Table::find($id);

        $settings = Settings::latest()->first();

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td>Name :</td>';
        $html .= '<td>' . $data->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Capacity :</td>';
        $html .= '<td>' . $data->capacity . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Availability :</td>';
        $html .= '<td>' . $data->availability . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created By :</td>';
        $html .= '<td>' . $data->createdBy->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created Date :</td>';
        $html .= '<td>' . date_format(new DateTime('@' . strtotime($data->created_at)), $settings->date_format) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return response()->json([$html]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Tables';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('tables.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $data = Table::find($id);

        return view('tables.create-edit', compact('title', 'breadcrumbs', 'data', 'is_edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15|unique:tables,name,' . $id,
            'capacity' => 'required',
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
                'capacity' => $request->capacity,
                'updated_by' => Auth::user()->id,
            ];

            $Table = Table::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Table updated', 'url' => route('tables.index')]);
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

            $Table = Table::find($id);
            $Table->update(['deleted_by' => Auth::user()->id]);
            $Table->delete();

            return json_encode(['success' => true, 'message' => 'Table deleted', 'url' => route('tables.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
