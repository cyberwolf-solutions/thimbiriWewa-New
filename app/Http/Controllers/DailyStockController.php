<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\DailyStock;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DailyStockController extends Controller
{
    //
    public function index()
    {
        $title = 'Kitchen Products';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        //$data = Stock::all();
        $data = DailyStock::all();
        return view('daily-stock.index', compact('title', 'breadcrumbs', 'data'));
    }

    public function create()
    {
        $title = 'Consumption ';

        $is_edit = false;
        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        //$data = Stock::all();
        $data = Stock::all();
        return view('daily-stock.create-edit', compact('title', 'breadcrumbs', 'data', 'is_edit'));
    }

    

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'ingredient' => 'required|exists:ingredients,id', // Validate that the ingredient exists
            'quanity' => 'required|numeric|min:0', // Ensure that the quantity is numeric and non-negative
            'date' => 'required|date', // Ensure the date is valid
        ]);

        // If validation fails, return errors
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
            // Find the ingredient from the daily stock selection
            $ingredient = Ingredient::find($request->ingredient);

            // Check if the stock exists for the selected ingredient
            $stock = Stock::where('name', $ingredient->name)->first();

            if (!$stock) {
                return response()->json(['success' => false, 'message' => 'Stock not found for this ingredient']);
            }

            // Ensure the kitchen consumption quantity is available in the stock
            if ($stock->quantity < $request->quanity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available']);
            }

            // Subtract the consumption quantity from the stock quantity
            $stock->quantity -= $request->quanity;

            // Save the updated stock quantity in the stock table
            $stock->save();

            // Create the daily stock record
            $dailyStock = DailyStock::create([
                'name' => $ingredient->name,
                'quantity' => $request->quanity,
                'products' => $request->product, // assuming `product` is from the form
                'created_by' => Auth::user()->id,
                'date' => $request->date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Daily stock record created successfully',
                'url' => route('daily-stock.index')
            ]);
        } catch (\Throwable $th) {
            // Handle errors
            return response()->json(['success' => false, 'message' => 'Something went wrong! ' . $th->getMessage()]);
        }
    }
}
