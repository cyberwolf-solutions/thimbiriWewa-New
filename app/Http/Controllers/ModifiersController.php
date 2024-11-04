<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Modifier;
use App\Models\ModifiersCategories;
use App\Models\ModifiersIngredients;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Validator;

class ModifiersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Modifier';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Modifier::all();
        return view('modifiers.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Modifier';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('modifiers.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;
        $ingredients = Ingredient::all();
        $categories = Category::all();

        return view('modifiers.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'ingredients', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:modifiers,name',
            'unit_price' => 'required',
            'description' => 'nullable',
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
                'unit_price' => $request->unit_price,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
            ];

            $Modifier = Modifier::create($data);

            if ($Modifier != null) {
                if (isset($request['ing_id']) && count($request['ing_id']) > 0) {
                    foreach ($request['ing_id'] as $k => $v) {
                        ModifiersIngredients::create([
                            'modifier_id' => $Modifier->id,
                            'ingredient_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }

                if (isset($request['categories']) && count($request['categories']) > 0) {
                    foreach ($request['categories'] as $k => $v) {
                        ModifiersCategories::create([
                            'modifier_id' => $Modifier->id,
                            'category_id' => $v,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            return json_encode(['success' => true, 'message' => 'Modifier created', 'url' => route('modifiers.index')]);
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
        $data = Modifier::find($id);

        $settings = Settings::latest()->first();

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
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
        if (isset($data->categories)) {
            $html .= '<tr>';
            $html .= '<td>Categories :</td>';
            $html .= '<td><a href="javascript:void();" data-url="' . route('get-modifier-categories') . '" data-id="' . $data->id . '"
                class="show-cat-ingd">Show Categories</a></td>';
            $html .= '</tr>';
        }
        if (isset($data->ingredients)) {
            $html .= '<tr>';
            $html .= '<td>Ingredients :</td>';
            $html .= '<td><a href="javascript:void();" data-url="' . route('get-modifier-ingredients') . '" data-id="' . $data->id . '"
                class="show-cat-ingd">Show Ingredients</a></td>';
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
    public function edit(string $id)
    {
        $title = 'Modifier';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('modifiers.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $data = Modifier::find($id);
        $ing_array = [];
        $cat_array = [];
        $ing_ids = [];
        $total = 0;

        if (isset($data->ingredients)) {
            foreach ($data->ingredients as $k => $ingd) {
                $consumed = ModifiersIngredients::where('modifier_id', $id)->where('ingredient_id', $ingd->id)->first();
                $settings = Settings::latest()->first();
                $unit_price = str_replace(',', '', $ingd->unit_price);

                // Ensure unit_price is converted to float
                $unit_price = (float)$unit_price;
                // $unit_price = number_format($ingd->unit_price, 2);
                $cost = number_format(($unit_price * ($consumed->quantity)), 2);
                $ing_array[$k] = [
                    'id' => $ingd->id, 'name' => $ingd->name, 'avl_qty' => $ingd->quantity, 'unit' => $ingd->unit->name,
                    'currency' => $settings->currency, 'unit_price' => $unit_price, 'cost' => $cost, 'cons_qty' => $consumed->quantity
                ];
                $total += ($unit_price * ($consumed->quantity));
                array_push($ing_ids, $ingd->id);
            }
        }

        if (isset($data->categories)) {
            foreach ($data->categories as $cat) {
                array_push($cat_array, $cat->id);
            }
        }

        $is_edit = true;
        $ingredients = Ingredient::whereNotIn('id', $ing_ids)->get();
        $categories = Category::all();
        $total = number_format($total, 2);

        return view('modifiers.create-edit', compact(
            'title',
            'breadcrumbs',
            'is_edit',
            'data',
            'ingredients',
            'categories',
            'ing_array',
            'total',
            'cat_array'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:modifiers,name,' . $id,
            'unit_price' => 'required',
            'description' => 'nullable',
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
                'unit_price' => $request->unit_price,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ];

            $modifier = Modifier::find($id)->update($data);
            $Modifier = Modifier::find($id);

            if ($Modifier != null) {
                if (isset($request['ing_id']) && count($request['ing_id']) > 0) {
                    $deleted = ModifiersIngredients::where('modifier_id', $Modifier->id)->get();
                    $deleted->each->delete();
                    foreach ($request['ing_id'] as $k => $v) {
                        ModifiersIngredients::create([
                            'modifier_id' => $Modifier->id,
                            'ingredient_id' => $v,
                            'quantity'  => $request['quantity'][$k],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }

                if (isset($request['categories']) && count($request['categories']) > 0) {
                    $deleted = ModifiersCategories::where('modifier_id', $Modifier->id)->get();
                    $deleted->each->delete();
                    foreach ($request['categories'] as $k => $v) {
                        ModifiersCategories::create([
                            'modifier_id' => $Modifier->id,
                            'category_id' => $v,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            return json_encode(['success' => true, 'message' => 'Modifier updated', 'url' => route('modifiers.index')]);
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
            $Modifier = Modifier::find($id);
            $Modifier->update(['deleted_by' => Auth::user()->id]);
            $Modifier->delete();
            $ModifiersIngredients = ModifiersIngredients::where('modifier_id', $id)->get();
            $ModifiersIngredients->each->delete();
            $ModifiersCategories = ModifiersCategories::where('modifier_id', $id)->get();
            $ModifiersCategories->each->delete();

            return json_encode(['success' => true, 'message' => 'Modifier deleted', 'url' => route('modifiers.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
