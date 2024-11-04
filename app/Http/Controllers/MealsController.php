<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Meal;
use App\Models\MealsProducts;
use App\Models\Product;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DateTime;

class MealsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Meal';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Meal::all();
        return view('meals.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Meal';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('meals.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;
        $products = Product::all();
        $categories = Category::all();

        return view('meals.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:meals,name',
            'category' => 'required',
            'unit_price' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000',
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
                'category_id' => $request->category,
                'unit_price' => $request->unit_price,
                'description' => $request->description,
                'image_url' => $image_url,
                'created_by' => Auth::user()->id,
            ];

            $Meal = Meal::create($data);

            if($Meal != null){
                if(isset($request['prod_id']) && count($request['prod_id']) > 0){
                    foreach($request['prod_id'] as $k=>$v){
                        MealsProducts::create([
                            'meal_id' => $Meal->id,
                            'product_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }

                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/meals/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/meals'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Meal created', 'url' => route('meals.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $data = Meal::find($id);

        $settings = Settings::latest()->first();

        if($data->image_url != null){
            $image = 'uploads/meals/'.$data->image_url;
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
        $html .= '<td>Description :</td>';
        $html .= '<td>' . $data->description . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Unit Price :</td>';
        $html .= '<td>' . $settings->currency . ' ' . number_format($data->unit_price, 2) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Category :</td>';
        $html .= '<td>' . @$data->category->name . '</td>';
        $html .= '</tr>';
        if(isset($data->products)){
            $html .= '<tr>';
            $html .= '<td>Products :</td>';
            $html .= '<td><a href="javascript:void();" data-url="' . route('get-meal-products') . '" data-id="' . $data->id . '"
                class="show-products">Show Products</a></td>';
            $html .= '</tr>';
        }
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
        $title = 'Meal';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('meals.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $data = Meal::find($id);
        $prod_array = [];
        $prod_ids = [];
        $total = 0;

        if(isset($data->products)){
            foreach($data->products as $k=>$prod){
                $consumed = MealsProducts::where('meal_id',$id)->where('product_id',$prod->id)->first();
                $settings = Settings::latest()->first();
                $unit_price = number_format($prod->unit_price, 2);

                // $cost = number_format(($unit_price*($consumed->quantity)), 2);
                $cost = number_format(($unit_price*($consumed->quantity)), 2);
                $prod_array[$k] = ['id' => $prod->id, 'name' => $prod->name, 'currency' => $settings->currency,
                    'unit_price' => $unit_price, 'cost' => $cost, 'cons_qty' => $consumed->quantity];
                $total += ($unit_price*($consumed->quantity));
                array_push($prod_ids,$prod->id);
            }
        }

        $is_edit = true;
        $products = Product::whereNotIn('id',$prod_ids)->get();
        $categories = Category::all();
        $total = number_format($total, 2);

        return view('meals.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'products',
            'categories', 'prod_array', 'total'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:meals,name,' . $id,
            'category' => 'required',
            'unit_price' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000',
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
                'category_id' => $request->category,
                'unit_price' => $request->unit_price,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ];

            if($request->file('image') != null) {
                $data['image_url'] = $image_url;
            }

            $meal = Meal::find($id)->update($data);
            $Meal = Meal::find($id);

            if($Meal != null){
                if(isset($request['prod_id']) && count($request['prod_id']) > 0){
                    $deleted = MealsProducts::where('meal_id',$Meal->id)->get();
                    $deleted->each->delete();
                    foreach($request['prod_id'] as $k=>$v){
                        MealsProducts::create([
                            'meal_id' => $Meal->id,
                            'product_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }

                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/meals/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/meals'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Meal updated', 'url' => route('meals.index')]);
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
            $Meal = Meal::find($id);
            $Meal->update(['deleted_by' => Auth::user()->id]);
            $Meal->delete();
            $MealsProducts = MealsProducts::where('meal_id',$id)->get();
            $MealsProducts->each->delete();

            return json_encode(['success' => true, 'message' => 'Meal deleted', 'url' => route('meals.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
