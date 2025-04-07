<?php

namespace App\Http\Controllers;

use App\Models\Buffet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BuffetController extends Controller
{
    public function index()
    {
        $title = 'Buffet';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Buffet::all();
        

        return view('buffet.index', compact('title', 'breadcrumbs','data'));
    }


 
    public function create()
    {
        $title = 'Buffet';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('buffet.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('buffet.create', compact('title', 'breadcrumbs','is_edit'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode('<br>', $validator->errors()->all())
            ]);
        }



        try {
            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'created_by' => Auth::id(),
            ];

           // $product = Product::create($data);
           $expense = Buffet::create($data);

            

            return response()->json([
                'success' => true,
                'message' => 'Buffet created successfully!',
                'url' => route('buffet.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }  
}
