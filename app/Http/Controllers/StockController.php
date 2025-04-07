<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //
    public function index()
    {
        $title = 'Stock';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        //$data = Stock::all();
        $data = Stock::with('ingredient.unit')->get();
        $ing = Ingredient::all();
        return view('stock.index', compact('title', 'breadcrumbs', 'data','ing'));
    }
}
