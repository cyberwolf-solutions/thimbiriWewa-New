<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\MealsProducts;
use App\Models\Product;
use App\Models\ProductsIngredients;
use App\Models\Settings;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Products';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Product::all();

        return view('products.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Products';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('products.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;
        $ingredients = Ingredient::all();
        $categories = Category::all();

        return view('products.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'categories', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products,name',
            'category' => 'required',
            'unit_price' => 'required',
            'type' => 'required',
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
                'type' => $request->type,
                'description' => $request->description,
                'image_url' => $image_url,
                'created_by' => Auth::user()->id,
            ];

            $Product = Product::create($data);

            if($Product != null){
                if(isset($request['ing_id']) && count($request['ing_id']) > 0){
                    foreach($request['ing_id'] as $k=>$v){
                        ProductsIngredients::create([
                            'product_id' => $Product->id,
                            'ingredient_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }

                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/products/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/products'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Product created', 'url' => route('products.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $data = Product::find($id);

        $settings = Settings::latest()->first();

        if($data->image_url != null){
            $image = 'uploads/products/'.$data->image_url;
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
        $html .= '<tr>';
        $html .= '<td>Type :</td>';
        $html .= '<td>' . $data->type . '</td>';
        $html .= '</tr>';
        if(isset($data->ingredients)){
            $html .= '<tr>';
            $html .= '<td>Ingredients :</td>';
            $html .= '<td><a href="javascript:void();" data-url="' . route('get-product-ingredients') . '" data-id="' . $data->id . '"
                class="show-ingredients">Show Ingredients</a></td>';
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
        $title = 'Products';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('products.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $data = Product::find($id);
        $ing_array = [];
        $ing_ids = [];
        $total = 0;

        if(isset($data->ingredients)){
            foreach($data->ingredients as $k=>$ingd){
                $consumed = ProductsIngredients::where('product_id',$id)->where('ingredient_id',$ingd->id)->first();
                $settings = Settings::latest()->first();
                $unit_price = str_replace(',', '', $ingd->unit_price);
                $unit_price = (float)$unit_price;
                // $unit_price = number_format($ingd->unit_price, 2);
                
                $cost = number_format(($unit_price*($consumed->quantity)), 2);
                

                $ing_array[$k] = ['id' => $ingd->id, 'name' => $ingd->name, 'avl_qty' => $ingd->quantity, 'unit' => $ingd->unit->name,
                    'currency' => $settings->currency, 'unit_price' => $unit_price, 'cost' => $cost, 'cons_qty' => $consumed->quantity];
                $total += ($unit_price*($consumed->quantity));
                array_push($ing_ids,$ingd->id);
            }
        }

        $is_edit = true;
        $ingredients = Ingredient::whereNotIn('id',$ing_ids)->get();
        $categories = Category::all();
        $total = number_format($total, 2);

        return view('products.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'ingredients',
            'categories', 'ing_array', 'total'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products,name,' . $id,
            'category' => 'required',
            'unit_price' => 'required',
            'type' => 'required',
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
                'type' => $request->type,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ];

            if($request->file('image') != null) {
                $data['image_url'] = $image_url;
            }

            $product = Product::find($id)->update($data);
            $Product = Product::find($id);

            if($Product != null){
                if(isset($request['ing_id']) && count($request['ing_id']) > 0){
                    $deleted = ProductsIngredients::where('product_id',$Product->id)->get();
                    $deleted->each->delete();
                    foreach($request['ing_id'] as $k=>$v){
                        ProductsIngredients::create([
                            'product_id' => $Product->id,
                            'ingredient_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }

                if($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/products/' . $preferred_name . '.' . $request['image']->extension();
                    if(file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/products'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Product updated', 'url' => route('products.index')]);
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
            $Product = Product::find($id);
            $Product->update(['deleted_by' => Auth::user()->id]);
            $Product->delete();
            $ProductIngredients = ProductsIngredients::where('product_id',$id)->get();
            $ProductIngredients->each->delete();

            return json_encode(['success' => true, 'message' => 'Product deleted', 'url' => route('products.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Get Product Details
     */
    public function getProducts(Request $request){
        $prodId = $request['prod_id'];

        $product = Product::where('id',$prodId)->first();
        $settings = Settings::latest()->first();

        $col = '<tr>';
        $col .= '<td><span class="iname">' . $product->name . '</span></td>';
        $col .= '<td><input type="number" step="any" class="form-control bg-transparent" id="quantity" name="quantity[]"></td>';
        $col .= '<td>' . $settings->currency . ' <span class="uprice">' . number_format($product->unit_price, 2) . '</span></td>';
        $col .= '<td>' . $settings->currency . ' <span class="cost"></span></td>';
        $col .= '<td><button class="btn bg-transparent border-0 text-danger remove_row"><i class="ri-delete-bin-2-line"></i></button></td>';
        $col .= '<td><input type="hidden" step="any" class="form-control bg-transparent prod_id" value="' . $prodId . '" name="prod_id[]"></td>';
        $col .= '</tr>';

        return response()->json(['col' => $col]);
    }

    /**
     * Get Meal Product Details
     */
    public function getMealProducts(Request $request){
        $id = $request['id'];
        $meal = Meal::find($id);
        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<th>Product Name</th>';
        $html .= '<th>Consumed Quantity</th>';
        $html .= '</tr>';

        foreach($meal->products as $prod){
            $consumed = MealsProducts::where('meal_id',$id)->where('product_id',$prod->id)->first();

            $html .= '<tr>';
            $html .= '<td>' . $prod->name . '</td>';
            $html .= '<td>' . $consumed->quantity . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response()->json([$html]);
    }
    public function productReport(){
        $title = 'Prodcut Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Product::with('category')->get();
        return view ('reports.productReports' , compact('data'));
    }
}
