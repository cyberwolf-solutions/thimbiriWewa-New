<?php

namespace App\Http\Controllers;

use App\Models\Kot;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        $type = $request->type;

        $title = 'Kitchen Order Tickets';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Order::where('status', 'Pending')->whereHas('items', function ($query) {
            $query->whereHas('meal', function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('type', 'KOT');
                });
            });
        });

        if ($type) {
            $data = $data->where('type', $type);
        }

        $data = $data->get();

        return view('kot.index', compact('title', 'breadcrumbs', 'data', 'type'));
    }

    public function print(string $id) {
        $data = Order::where('id', $id)->whereHas('items', function ($query) {
            $query->whereHas('meal', function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('type', 'KOT');
                });
            });
        })->first();
        return view('kot.print', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
