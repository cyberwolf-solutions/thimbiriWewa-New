<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Order;
use Illuminate\Http\Request;

class BarController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        $type = $request->type;

        $title = 'Beverage Order Tickets';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Order::where('status', 'Pending')->whereHas('items', function ($query) {
            $query->whereHas('meal', function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('type', 'BOT');
                });
            });
        });

        if ($type) {
            $data = $data->where('type', $type);
        }

        $data = $data->get();

        return view('bot.index', compact('title', 'breadcrumbs', 'data', 'type'));
    }
    public function print(string $id) {
        $data = Order::where('id', $id)->whereHas('items', function ($query) {
            $query->whereHas('meal', function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('type', 'BOT');
                });
            });
        })->first();
        return view('bot.print', compact('data'));
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
