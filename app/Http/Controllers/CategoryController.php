<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Modifier;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use DateTime;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Categories';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Category::all();
        return view('categories.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Categories';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('categories.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('categories.create-edit', compact('title', 'breadcrumbs', 'is_edit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'type' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000'
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

        if($request->file('image') != null) {
            $preferred_name = trim($request->name);
            $image_url = $preferred_name . '.' . $request['image']->extension();
        }else{
            $image_url = null;
        }

        try {
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'image_url' => $image_url,
                'created_by' => Auth::user()->id,
            ];

            $Category = Category::create($data);

            if($Category != null){
                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/categories/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/categories'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Category created', 'url' => route('categories.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $data = Category::find($id);

        $settings = Settings::latest()->first();

        if($data->image_url != null){
            $image = 'uploads/categories/'.$data->image_url;
        }else{
            $image = 'uploads/cutlery.png';
        }

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td colspan="2"><img style="padding-left: 25%;" src="' . URL::asset($image) . '" alt="" height="100"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Name :</td>';
        $html .= '<td>' . $data->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Type :</td>';
        $html .= '<td>' . $data->type . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Description :</td>';
        $html .= '<td>' . $data->description . '</td>';
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
        $title = 'Categories';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('categories.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $data = Category::find($id);

        return view('categories.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15|unique:categories,name,' . $id,
            'type' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000'
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

        if($request->file('image') != null) {
            $preferred_name = trim($request->name);
            $image_url = $preferred_name . '.' . $request['image']->extension();
        }

        try {
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ];

            if($request->file('image') != null) {
                $data['image_url'] = $image_url;
            }

            $Category = Category::find($id)->update($data);

            if($Category != null){
                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/categories/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/categories'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Category updated', 'url' => route('categories.index')]);
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

            $Category = Category::find($id);
            $Category->update(['deleted_by' => Auth::user()->id]);
            $Category->delete();

            return json_encode(['success' => true, 'message' => 'Category deleted', 'url' => route('categories.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Get Modifier Category Details
     */
    public function getModifierCategories(Request $request){
        $id = $request['id'];
        $modifier = Modifier::find($id);
        $html = '<table class="table" cellspacing="0" cellpadding="0">';

        foreach($modifier->categories as $cat){
            $html .= '<tr>';
            $html .= '<td>' . $cat->name . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response()->json([$html]);
    }
}
