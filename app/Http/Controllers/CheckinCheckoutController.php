<?php

namespace App\Http\Controllers;

use App\Models\BookingsRooms;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\BordingType;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\checkincheckout;
use App\Models\CustomerType;
use App\Models\RoomPricing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckinCheckoutController extends Controller
{
    public function index()
    {

        $title = 'Checkin';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = CheckinCheckout::with('customer', 'roomType')->get();
        return view('bookings.checkin', compact('title', 'breadcrumbs', 'data'));
    }

    public function create()
    {
        $title = 'Checkin';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        // $customers = Customer::with('bookings.rooms')->get();
        $customers = Customer::whereHas('bookings', function ($query) {
            $query->whereIn('status', ['OnGoing', 'Pending']);
        })->with('bookings.rooms')->get();


        $is_edit = false;
        $data = Booking::whereIn('status', ['OnGoing', 'Pending'])->get();

        $boarding = BordingType::all();
        $data1 = Room::all();
        return view('bookings.addcheckin', compact('title', 'breadcrumbs', 'data', 'is_edit', 'data1', 'customers', 'boarding'));
    }

    public function getBookingRooms($customerId)
{
    // Fetch the customer along with their bookings and rooms
    $customer = Customer::with('bookings.rooms')->findOrFail($customerId);

    // Get all booking rooms related to the bookings of this customer
    $bookingRooms = BookingsRooms::whereIn('booking_id', $customer->bookings->pluck('id'))
                               ->get(['booking_id', 'room_id', 'cost']); // Fetch necessary fields

    // Attach booking room data (cost) to each booking and room
    $customerBookings = $customer->bookings->map(function ($booking) use ($bookingRooms) {
        // Attach the relevant room data to each room in the booking
        $booking->rooms->each(function ($room) use ($booking, $bookingRooms) {
            // Find the corresponding room's booking room entry using booking_id and room_id
            $roomBooking = $bookingRooms->firstWhere('room_id', $room->id);

            if ($roomBooking) {
                // Add the 'cost' data from booking_rooms table to the room
                $room->cost = $roomBooking->cost;
            }
        });

        return $booking;
    });

    // Return the bookings data with attached room and cost information
    return response()->json($customerBookings);
}



    public function getRoomFacility($facilityId)
    {

        $facility = RoomFacilities::find($facilityId);

        if ($facility) {

            return response()->json(['name' => $facility->name]);
        } else {

            return response()->json(['error' => 'Facility not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'booking_id' => 'required',
            'bordingtype' => 'required',
            'rooms_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            $all_errors = '';
            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }
            return response()->json(['success' => false, 'message' => $all_errors]);
        }

        try {
            $roomsData = json_decode($request->rooms_data, true);
            $booking = Booking::findOrFail($request->booking_id);

            // Get customer type ID from name
            $customerType = CustomerType::where('type', $booking->customer_type)->first();

            if (!$customerType) {
                Log::warning('Customer type not found', ['name' => $booking->customer_type]);
                return response()->json([
                    'success' => false,
                    'message' => "Invalid customer type: {$booking->customer_type}"
                ]);
            }

            foreach ($roomsData as $roomData) {
                Log::info('Processing Room Data', $roomData);

                $checkin = Carbon::parse($roomData['checkin']);
                $checkout = Carbon::parse($roomData['checkout']);
                $days = $checkin->diffInDays($checkout);
                if ($days == 0) $days = 1;

                // Fetch room rate using ID, not name
                $roomRate = RoomPricing::where('room_id', $roomData['roomId'])
                    ->where('boarding_type_id', $booking->bording_type)
                    ->where('customer_type_id', $customerType->id)
                    ->where('currency', $booking->currency)
                    ->first();

                if (!$roomRate) {
                    Log::warning('Room pricing not found', [
                        'room_id' => $roomData['roomId'],
                        'boarding_type_id' => $booking->bording_type,
                        'customer_type_id' => $customerType->id,
                        'currency' => $booking->currency
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => "Room pricing not found for Room ID {$roomData['roomId']}."
                    ]);
                }

                $ratePerDay = $roomRate->rate; // Rate per day from RoomPricing
                $subTotal = ($ratePerDay * $roomData['price']) / 100; // Subtotal based on rate per day and number of days

                // Add the rate-per-day * number of days to the room price
                $totalRoomPrice = $roomData['price'] + $subTotal; // Add the calculated price to the room's base price
                $totalwithdays = $totalRoomPrice * $days;
                $paidAmount = $roomData['paidAmount'] ?? 0;
                $discount = $roomData['discount'] ?? 0;

                // Calculate the total amount and due amount
                $totalAmount = $totalwithdays - $discount;
                $dueAmount = $totalAmount - $paidAmount;

                // Create the checkincheckout record
                checkincheckout::create([
                    'booking_id' => $request->booking_id,
                    'customer_id' => $request->customer_id,
                    'boardingtype' => $request->bordingtype,
                    'created_by' => Auth::user()->id,
                    'room_type' => $roomData['roomId'],
                    'room_no' => $roomData['roomNo'],
                    'room_facility_type' => $roomData['facility'],
                    'checkin' => $roomData['checkin'],
                    'checkout' => $roomData['checkout'],
                    'total_amount' => $totalwithdays,
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'sub_total' => $totalAmount,
                    'discount' => $discount
                ]);
            }

            $booking->status = 'Complete';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer Checked In',
                'url' => route('checkin.index')
            ]);
        } catch (\Throwable $th) {
            Log::error('Error in CheckinCheckoutController store method: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json(['success' => false, 'message' => 'Something went wrong! ' . $th->getMessage()]);
        }
    }



    public function edit(string $id)
    {
        $title = 'Checkin';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        // $customers = Customer::with('bookings.rooms')->get();
        $customers = Customer::whereHas('bookings', function ($query) {
            $query->where('status', 'OnGoing');
        })->with('bookings.rooms')->get();

        $is_edit = true;
        $data = checkincheckout::find($id);

        $roomPrice = Room::where('room_no', $data->room_no)->value('price');

        $boarding = BordingType::all();
        $data1 = Room::all();
        return view('bookings.addcheckin', compact('roomPrice', 'title', 'breadcrumbs', 'data', 'is_edit', 'data1', 'customers', 'boarding'));
    }



    public function update(Request $request, string $id)
    {

        //dd($request->all());
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [

            //'room_no' => 'required',
            //'checkin' => 'required',
            'checkout' => 'required',
            'total' => 'required',

            'due' => 'required',

        ]);

        if ($validator->fails()) {
            // If validation fails, return error messages
            $all_errors = null;
            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }
            return response()->json(['success' => false, 'message' => $all_errors]);
        }

        try {
            // Create a new Checkin record
            // Find the existing record by ID
            $customer = checkincheckout::findOrFail($id);

            // Update the relevant fields
            $customer->checkout = $request->checkout;
            $customer->total_amount = $request->total;
            $customer->due_amount = $request->due;

            // Save the changes
            $customer->save();



            return json_encode(['success' => true, 'message' => 'Updated Successfully', 'url' => route('checkin.index')]);
        } catch (\Throwable $th) {
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }


    public function destroy($id)
    {

        try {
            $checkin = CheckinCheckout::findOrFail($id);


            $checkin->delete();


            return redirect()->route('checkin.index')->with('success', 'Checkin deleted successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
