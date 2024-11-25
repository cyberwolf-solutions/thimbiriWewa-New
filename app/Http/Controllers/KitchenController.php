<?php

namespace App\Http\Controllers;

use App\Models\Kot;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

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

    public function print(string $id)
    {
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the KOT by its ID
            $kot = Order::findOrFail($id);
    
            // Update the status to 'canceled'
            $kot->status = 'Cancel';
            $kot->save();
    
            // Update the linked order_payment status
            OrderPayment::where('order_id', $kot->order_id)
                ->update(['payment_status' => 'Cancel']);
    
            // Redirect with a success message
            return redirect()->route('kitchen.index')->with('success', 'KOT status updated to canceled.');
        } catch (\Throwable $e) {
            // Log the error for debugging
            Log::error('Error updating KOT status: ' . $e->getMessage());
    
            // Print the error to the page for immediate debugging (optional in development)
            return redirect()->route('kitchen.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
}
