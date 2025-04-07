<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerBoardMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerBoardMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'customer' => 'required|integer|exists:customers,id', // Ensure customer exists
            'cart' => 'required|string', // Cart is passed as a string, so validate it
        ]);
    
        if ($validator->fails()) {
            $all_errors = '';
    
            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }
    
            return response()->json([
                'success' => false,
                'message' => $all_errors
            ], 422); // 422 for validation error
        }
    
        // Decode the cart JSON string into an array
        $cart = json_decode($request->cart, true);
    
        // Validate the cart items if necessary
        foreach ($cart as $item) {
            // You can add extra validation if necessary, like checking if product ID and quantity are valid.
            if (!isset($item['id']) || !isset($item['quantity'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid cart item data.'
                ], 422);
            }
        }
    
        // Retrieve the booking details for the given customer
        $today = now()->format('Y-m-d'); // Get today's date
    
        // Retrieve the active booking for the customer
        $booking = Booking::where('customer_id', $request->customer)
            ->whereDate('checkin', '<=', $today)
            ->whereDate('checkout', '>=', $today)
            ->first();
    
        // If no booking is found, return an error message
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'No active booking found for this customer.',
            ], 422); // 422 - validation error
        }
    
        // Process and store each item from the cart into the booking_meal table
        try {
            foreach ($cart as $item) {
                // Prepare data for the booking_meal table
                $bookingMealData = [
                    'booking' => $booking->id, // Booking ID
                    'customer' => $booking->customer_id, // Customer ID
                    'boarding' => $booking->bording_type, // Boarding type from booking
                    'mealtype' => $item['id'], // Product ID (meal type)
                    'quantity' => $item['quantity'], // Quantity sold
                    'date' => now(), // Add the current date
                ];
    
                // Insert each product in the cart into the booking_meal table
                CustomerBoardMeal::create($bookingMealData);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully.',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return an error message
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500); // 500 for server errors
        }
    }
    
    
    
    


    /**
     * Display the specified resource.
     */
    public function show(CustomerBoardMeal $customerBoardMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerBoardMeal $customerBoardMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerBoardMeal $customerBoardMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerBoardMeal $customerBoardMeal)
    {
        //
    }
}
