<?php

namespace App\Http\Controllers;

use App\Models\BordingType;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\checkincheckout;
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
            $query->where('status', 'OnGoing');
        })->with('bookings.rooms')->get();

        $is_edit = false;
        $data = Booking::where('status', 'OnGoing')->get();
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
            'booking_room_id' => 'required',
            'booking_id' => 'required',
            'room_facility' => 'required',
            'room_no' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
            'total' => 'required',
            'payed' => 'required',
            'due' => 'required',
            'bordingtype' => 'required',
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
            $customer = checkincheckout::create([
                'booking_id' => $request->booking_id,
                'customer_id' => $request->customer_id,
                'room_type' => $request->booking_room_id,
                'room_facility_type' => $request->room_facility,
                'room_no' => $request->room_no,
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'total_amount' => $request->total,
                'paid_amount' => $request->payed,
                'due_amount' => $request->due,
                'boardingtype' => $request->bordingtype,
                'created_by' => Auth::user()->id,
            ]);



            return json_encode(['success' => true, 'message' => 'Customer Checked In', 'url' => route('checkin.index')]);
        } catch (\Throwable $th) {
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
