<?php

namespace App\Http\Controllers;

use App\Models\BoardConsumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BoardConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Board consumption';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = BoardConsumption::all();
        return view('board-consumption.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Board consumption';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('buffet.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('board-consumption.create', compact('title', 'breadcrumbs', 'is_edit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hb' => 'required|numeric',
            'fb' => 'required|numeric',
            'bb' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode('<br>', $validator->errors()->all())
            ]);
        }

        try {
            // Insert new Buffet record
            $buffet = BoardConsumption::create([
                'half_board' => $request->hb,
                'full_board' => $request->fb,
                'bb' => $request->bb,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consumption created successfully!',
                'url' => route('board_consumption')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BoardConsumption $boardConsumption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoardConsumption $id)
    {
        $title = 'Board consumption';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('rooms.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $data = BoardConsumption::find($id);


        return view('board-consumption.create', compact('title', 'breadcrumbs', 'is_edit', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoardConsumption $boardConsumption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardConsumption $boardConsumption)
    {
        //
    }
}
