<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Modifier;
use App\Models\ModifiersIngredients;
use App\Models\Product;
use App\Models\ProductsIngredients;
use App\Models\Settings;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Ingredients';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Ingredient::all();

        return view('ingredients.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Ingredients';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('ingredients.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;
        $units = Unit::all();

        return view('ingredients.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:ingredients,name',
            'unit' => 'required',
            'unit_price' => 'required',
            'quantity' => 'required',
            'min_quantity' => 'required',
            'description' => 'nullable'
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
                'unit_id' => $request->unit,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'min_quantity' => $request->min_quantity,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
            ];

            $ingredient = Ingredient::create($data);

            return json_encode(['success' => true, 'message' => 'Ingredient created', 'url' => route('ingredients.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Ingredient::find($id);

        $settings = Settings::latest()->first();

        // dd($data->name);
        $html = '<table class="table">';
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
        $html .= '<td>Unit :</td>';
        $html .= '<td>' . @$data->unit->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Quantity :</td>';
        $html .= '<td>' . $data->quantity . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Minimum Quantity :</td>';
        $html .= '<td>' . $data->min_quantity . '</td>';
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
    public function edit(string $id)
    {
        $title = 'Ingredients';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('ingredients.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;

        $units = Unit::all();
        $data = Ingredient::find($id);

        return view('ingredients.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:ingredients,name,' . $id,
            'unit' => 'required',
            'unit_price' => 'required',
            'quantity' => 'required',
            'min_quantity' => 'required',
            'description' => 'nullable'
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
                'unit_id' => $request->unit,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'min_quantity' => $request->min_quantity,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ];

            $ingredient = Ingredient::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Ingredient updated', 'url' => route('ingredients.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $Ingredient = Ingredient::find($id);
            $Ingredient->update(['deleted_by' => Auth::user()->id]);
            $Ingredient->delete();

            return json_encode(['success' => true, 'message' => 'Ingredient deleted', 'url' => route('ingredients.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Get Ingredient Details
     */
    public function getIngredients(Request $request){
        $ingId = $request['ing_id'];

        $ingredient = Ingredient::where('id',$ingId)->first();
        $settings = Settings::latest()->first();

        $col = '<tr>';
        $col .= '<td><span class="iname">' . $ingredient->name . '</span></td>';
        $col .= '<td>' . $ingredient->quantity . ' ' . $ingredient->unit->name . '</td>';
        $col .= '<td><input type="number" step="any" class="form-control bg-transparent" id="quantity" name="quantity[]"></td>';
        $col .= '<td>' . $ingredient->unit->name . '</td>';
        $col .= '<td>' . $settings->currency . ' <span class="uprice">' . number_format($ingredient->unit_price, 2) . '</span></td>';
        $col .= '<td>' . $settings->currency . ' <span class="cost"></span></td>';
        $col .= '<td><button class="btn bg-transparent border-0 text-danger remove_row"><i class="ri-delete-bin-2-line"></i></button></td>';
        $col .= '<td><input type="hidden" step="any" class="form-control bg-transparent ing_id" value="' . $ingId . '" name="ing_id[]"></td>';
        $col .= '</tr>';

        return response()->json(['col' => $col]);
    }

    /**
     * Get Product Ingredient Details
     */
    public function getProductIngredients(Request $request){
        $id = $request['id'];
        $product = Product::find($id);
        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<th>Ingredient Name</th>';
        $html .= '<th>Consumed Quantity</th>';
        $html .= '</tr>';

        foreach($product->ingredients as $ingd){
            $consumed = ProductsIngredients::where('product_id',$id)->where('ingredient_id',$ingd->id)->first();

            $html .= '<tr>';
            $html .= '<td>' . $ingd->name . '</td>';
            $html .= '<td>' . $consumed->quantity . ' ' . $ingd->unit->name . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response()->json([$html]);
    }

    /**
     * Get Modifier Ingredient Details
     */
    public function getModifierIngredients(Request $request){
        $id = $request['id'];
        $modifier = Modifier::find($id);
        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<th>Ingredient Name</th>';
        $html .= '<th>Consumed Quantity</th>';
        $html .= '</tr>';

        foreach($modifier->ingredients as $ingd){
            $consumed = ModifiersIngredients::where('modifier_id',$id)->where('ingredient_id',$ingd->id)->first();

            $html .= '<tr>';
            $html .= '<td>' . $ingd->name . '</td>';
            $html .= '<td>' . $consumed->quantity . ' ' . $ingd->unit->name . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response()->json([$html]);
    }
}
