<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\BordingType;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\checkincheckout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $customer = Customer::with('bookings.rooms')->findOrFail($customerId);

        return response()->json($customer->bookings);
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'booking_id' => 'required',
            'bordingtype' => 'required',
            'rooms_data' => 'required|json',
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
            // $customer = checkincheckout::create([
            //     'booking_id' => $request->booking_id,
            //     'customer_id' => $request->customer_id,
            //     'room_type' => $request->booking_room_id,
            //     'room_facility_type' => $request->room_facility,
            //     'room_no' => $request->room_no,
            //     'checkin' => $request->checkin,
            //     'checkout' => $request->checkout,
            //     'total_amount' => $request->total,
            //     'paid_amount' => $request->payed,
            //     'due_amount' => $request->due,
            //     'boardingtype' => $request->bordingtype,
            //     'created_by' => Auth::user()->id,
            // ]);
            $roomsData = json_decode($request->rooms_data, true);


            Log::info("Room Data" , $roomsData);

            // dd($roomsData);

            foreach ($roomsData as $roomData) {
                $customer = checkincheckout::create([
                    'booking_id' => $request->booking_id,
                    'customer_id' => $request->customer_id,
                    'boardingtype' => $request->bordingtype,
                    'created_by' => Auth::user()->id,
                    'room_type' => $roomData['roomId'],
                    'room_no' => $roomData['roomNo'],
                    'room_facility_type' => $roomData['facility'],
                    'checkin' => $roomData['checkin'],
                    'checkout' => $roomData['checkout'],
                    // 'price' => $roomData['price'],
                    // 'boarding_price' => $roomData['boardingPrice'],
                    'total_amount' => $roomData['totalCharge'],
                    'paid_amount' => $roomData['paidAmount'],
                    'due_amount' => $roomData['dueAmount'],
                    'sub_total'=>0,
                    'discount'=>0
                ]);
            }


            $booking =Booking::find($request->booking_id);
            $booking->status= 'Complete';
            $booking->save();

            return json_encode(['success' => true, 'message' => 'Customer Checked In', 'url' => route('checkin.index')]);
        } catch (\Throwable $th) {
            Log::error('Error in CheckinCheckoutController store method: ' . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
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
