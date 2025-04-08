<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingsRooms;
use App\Models\BordingType;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Room;
use App\Models\RoomPricing;
use App\Models\RoomType;
use App\Models\Settings;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Bookings';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Booking::all();
        return view('bookings.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checkin' => 'required',
            'checkout' => 'required',
            'no_of_adults' => 'required',
            'room' => 'required',
            'name' => 'required'
        ]);

        // Validate request data
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }

        DB::beginTransaction(); // Start a database transaction for better error handling

        try {
            // Create customer data
            $cust_data = [
                'name' => $request->name,
                'contact' => $request->contact,
                'email' => $request->email,
                'address' => $request->address,
                'type' => $request->custype,
                'created_by' => Auth::user()->id,
            ];

            $Customer = Customer::create($cust_data);

            if (!$Customer) {
                throw new \Exception("Failed to create customer.");
            }

            // Determine booking status based on check-in date
            $status = (($request->checkin > date("Y-m-d")) ? 'Pending' : 'OnGoing');

            // Booking data
            $booking_data = [
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'no_of_adults' => $request->no_of_adults,
                'no_of_children' => $request->no_of_children,
                'customer_id' => $Customer->id,
                'customer_type' => $request->custype,
                'currency' => $request->currency,
                'bording_type' => $request->bording,
                'status' => $status,
                'created_by' => Auth::user()->id,
            ];

            // Create the booking record
            $Booking = Booking::create($booking_data);

            if (!$Booking) {
                throw new \Exception("Failed to create booking.");
            }

            // Loop through each room selected by the user
            foreach ($request->room as $room) {
                // Get the room price from the 'rooms' table
                $roomData = Room::find($room);
                if (!$roomData) {
                    throw new \Exception("Room not found.");
                }

                $roomPrice = $roomData->price; // Room price from rooms table

                // Find the customer_type_id based on the customer type name
                $customerType = CustomerType::where('type', $request->custype)->first();

                // Check if customer type is found
                if (!$customerType) {
                    return json_encode(['success' => false, 'message' => 'Customer type not found.']);
                }

                // Now, you can use the customer_type_id in your room pricing query
                $roomPricing = RoomPricing::where('room_id', $room)
                    ->where('boarding_type_id', $request->bording)
                    ->where('customer_type_id', $customerType->id)  // Use the ID here
                    ->where('currency', $request->currency)
                    ->first();

                // Check if pricing is found
                if (!$roomPricing) {
                    // \Log::error("Room pricing not found for room_id: $room, boarding_type_id: " . $request->bording . ", customer_type_id: " . $customerType->id . ", currency: " . $request->currency);
                    return json_encode(['success' => false, 'message' => 'Room pricing not found for the selected options.']);
                }


                // Calculate the number of days between check-in and check-out
                $checkinDate = new \DateTime($request->checkin);
                $checkoutDate = new \DateTime($request->checkout);
                $days = $checkoutDate->diff($checkinDate)->days;


                $cost = ($roomPrice + ($roomPrice * ($roomPricing->rate / 100))) * $days;

                // Add room data to the 'bookings_rooms' table
                $broom_data = [
                    'booking_id' => $Booking->id,
                    'room_id' => $room,
                    'days' => $days,
                    'rate' => $roomPricing->rate,
                    'cost' => $cost,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                // Create the bookings_rooms record
                $BookingRoom = BookingsRooms::create($broom_data);

                if (!$BookingRoom) {
                    throw new \Exception("Failed to assign room to booking.");
                }

                // If the status is 'OnGoing', update the room status
                if ($status == 'OnGoing') {
                    $room_data = [
                        'status' => 'Reserved',
                        'updated_by' => Auth::user()->id,
                    ];

                    // Ensure the room status is updated
                    $roomUpdate = Room::find($room)->update($room_data);

                    if (!$roomUpdate) {
                        throw new \Exception("Failed to update room status.");
                    }
                }
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return json_encode(['success' => true, 'message' => 'Booking created successfully', 'url' => route('bookings.index')]);
        } catch (\Exception $e) {
            // Rollback any changes if something fails
            DB::rollBack();

            // Return the error message
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Booking::find($id);

        $settings = Settings::latest()->first();
        $noOfDays = (Carbon::parse($data->checkin))->diffInDays(Carbon::parse($data->checkout));
        $total = 0;
        foreach ($data->rooms as $room) {
            $total += $noOfDays * ($room->price);
        }

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td>Guest :</td>';
        $html .= '<td><a href="javascript:void(0)" data-url="' . route('get-booking-customers') . '"
                    data-id="' . $data->customers->id . '" class="show-modal">' . $data->customers->name . '</a></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Check In :</td>';
        $html .= '<td>' . date_format(new DateTime('@' . strtotime($data->checkin)), $settings->date_format) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Check Out :</td>';
        $html .= '<td>' . date_format(new DateTime('@' . strtotime($data->checkout)), $settings->date_format) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>No of Adults/Children :</td>';
        $html .= '<td>' . $data->no_of_adults . '/' . $data->no_of_children . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Status :</td>';
        $html .= '<td>' . $data->status . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Rooms :</td>';
        $html .= '<td><a href="javascript:void();" data-url="' . route('get-booking-rooms') . '"
                    data-id="' . $data->id . '" class="show-modal">View Rooms</a></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Total(Room Charges Only) :</td>';
        $html .= '<td>' . $settings->currency . ' ' . number_format($total, 2) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created By :</td>';
        $html .= '<td>' . $data->createdBy->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created Date :</td>';
        $html .= '<td>' . date_format(new DateTime('@' . strtotime($data->created_at)), $settings->date_format) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return response()->json([$html]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Bookings';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Booking::find($id);

        //Get Available Rooms
        $selectedRooms = null;

        $settings = Settings::latest()->first();
        $selectedArray = [];
        $selectedR = $data['rooms']->chunk(3);
        foreach ($selectedR as $row) {
            $selectedRooms = '<div class="row">';
            foreach ($row as $item) {
                array_push($selectedArray, $item['id']);
                if ($item->image_url != null) {
                    $image = 'uploads/rooms/' . $item->image_url;
                } else {
                    $image = 'https://placehold.co/50';
                }

                $selectedRooms .= '<div class="col-md-4">';
                $selectedRooms .= '<div class="form-check form-check-inline">';
                $selectedRooms .= '<input class="form-check-input form-control" type="checkbox" name="room[]" id=""
                                                value="' . $item['id'] . '" checked>';
                $selectedRooms .= '<div class="card border">';
                $selectedRooms .= '<img src=" ' . $image . ' " alt="" class="card-img-top">';
                $selectedRooms .= '<div class="card-body text-center">';
                $selectedRooms .= '<p class="card-text small"> Name: ' . $item['name'] . ' </p>';
                $selectedRooms .= '<p class="card-text small"> Room No: ' . $item['room_no'] . ' </p>';
                $selectedRooms .= '<p class="card-text small"> Capacity: ' . $item['capacity'] . ' </p>';
                $selectedRooms .= '<p class="card-text small"> Room Type: ' . $item['types']['name'] . ' </p>';
                $selectedRooms .= '<p class="card-text small"> Price: ' . $settings->currency . ' ' . number_format($item['price'], 2) . ' </p>';
                $selectedRooms .= '</div>';
                $selectedRooms .= '</div>';
                $selectedRooms .= '</div>';
                $selectedRooms .= '</div>';
            }
            $selectedRooms .= '</div>';
        }

        $selected = implode(', ', $selectedArray);

        return view('bookings.edit', compact('title', 'breadcrumbs', 'data', 'selectedRooms', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'checkin' => 'required',
            'checkout' => 'required',
            'no_of_adults' => 'required',
            'room' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }

        try {
            $booking_data = [
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'no_of_adults' => $request->no_of_adults,
                'no_of_children' => $request->no_of_children,
                'updated_by' => Auth::user()->id,
            ];

            $booking = Booking::find($id)->update($booking_data);
            $Booking = Booking::find($id);

            $cust_data = [
                'name' => $request->name,
                'contact' => $request->contact,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
            ];

            $customer = Customer::find($Booking->customers->id)->update($cust_data);

            $selected = explode(',', $request->selected);

            foreach ($selected as $sel) {
                $room_data = [
                    'status' => 'Available',
                    'updated_by' => Auth::user()->id,
                ];

                $room = Room::find($sel)->update($room_data);
            }

            foreach ($request->room as $room) {
                $deleted = BookingsRooms::where('booking_id', $id)->get();
                $deleted->each->delete();
                $broom_data = [
                    'booking_id' => $Booking->id,
                    'room_id' => $room,
                    'created_by' => Auth::user()->id,
                ];

                $BookingRoom = BookingsRooms::create($broom_data);

                if ($Booking->status == 'OnGoing') {
                    $room_data = [
                        'status' => 'Reserved',
                        'updated_by' => Auth::user()->id,
                    ];

                    $room = Room::find($room)->update($room_data);
                }
            }

            return json_encode(['success' => true, 'message' => 'Booking updated', 'url' => route('bookings.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $Booking = Booking::find($id);
            $Booking->update(['deleted_by' => Auth::user()->id]);
            $Booking->delete();
            $BookingRooms = BookingsRooms::where('booking_id', $id)->get();
            $BookingRooms->each->delete();

            foreach ($BookingRooms as $bookingRoom) {
                $room = Room::find($bookingRoom->room_id);


                $room->update(['status' => 'Available']);
            }



            return json_encode(['success' => true, 'message' => 'Booking deleted', 'url' => route('bookings.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Check Availability View.
     */
    public function checkAvailability()
    {
        $title = 'Check Availability';
        $cus = CustomerType::all();
        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = BordingType::all();
        return view('bookings.check-availability', compact('title', 'cus', 'data', 'breadcrumbs'));
    }


    /**
     * Get Available Rooms for Specific Booking
     */
    public function getAvailableRooms(Request $request)
    {
        $formData = $request->formData;
        $count = $formData['no_of_adults'] + $formData['no_of_children'];
        $boardingTypeId = $formData['bording']; // Get the selected boarding type

        try {
            $checkin = $formData['checkin'];
            $checkout = $formData['checkout'];

            // Fetch bookings with overlapping dates
            $availability = Booking::where(function ($query) use ($checkin, $checkout) {
                $query->where('checkin', '>=', $checkin)
                    ->where('checkin', '<', $checkout);
            })->orWhere(function ($query) use ($checkin, $checkout) {
                $query->where('checkout', '>', $checkin)
                    ->where('checkout', '<=', $checkout);
            })->orWhere(function ($query) use ($checkin, $checkout) {
                $query->where('checkin', '<', $checkin)
                    ->where('checkout', '>', $checkout);
            })->get();

            // Collect booked room IDs
            $bookedRooms = [];
            foreach ($availability as $aval) {
                foreach ($aval->rooms as $room) {
                    array_push($bookedRooms, $room->id);
                }
            }

            $availabilityIds = $availability->pluck('id')->toArray();

            // Modify the query to include the selected boarding type
            $availableRoomsQuery = Room::where('capacity', '>=', $count)
                ->where('status', 'Available')
                ->whereNotIn('id', $availabilityIds);


            // If a boarding type is selected, filter rooms by the selected boarding type from RoomPricing
            if ($boardingTypeId) {
                // Instead of limiting to a single match, ensure all rooms with that boarding type are included
                $availableRoomsQuery = $availableRoomsQuery->whereHas('pricings', function ($query) use ($boardingTypeId) {
                    $query->where('boarding_type_id', $boardingTypeId);
                });
            }


            // Fetch available rooms
            $availableRooms = $availableRoomsQuery->get();

            // Group by room types and format the details
            $roomTypeCount = [];
            foreach ($availableRooms as $rooms) {
                // Group by room types if needed
                $roomTypeCount[$rooms->types->name] = isset($roomTypeCount[$rooms->types->name]) ? $roomTypeCount[$rooms->types->name] + 1 : 1;
            }

            $roomTypeArr = [];
            $roomTypeDetails = $availableRoomDetails = null;

            // Get Available Room Types - Single, Double
            if ($roomTypeCount) {
                $roomTypeArr = array_chunk($roomTypeCount, 3, true);
                foreach ($roomTypeArr as $row) {
                    $roomTypeDetails = '<div class="row">';
                    foreach ($row as $k => $item) {
                        $roomTypeDetails .= '<div class="col-md-4">';
                        $roomTypeDetails .= '<h4><strong>' . $k . ' </strong>: ' . $item . ' </h4>';
                        $roomTypeDetails .= '</div>';
                    }
                    $roomTypeDetails .= '</div>';
                }
            }

            // Formatting the available room details
            $settings = Settings::latest()->first();
            if ($availableRooms->isNotEmpty()) {
                $availableRooms = $availableRooms->chunk(3);
                $availableRoomDetails = ''; // Initialize outside the loop

                foreach ($availableRooms as $row) {
                    $availableRoomDetails .= '<div class="row">'; // Use .= to append instead of overwrite
                    foreach ($row as $item) {
                        $image = $item->image_url ? 'uploads/rooms/' . $item->image_url : 'https://placehold.co/50';
                        Log::info('Available rooms result', ['rooms' => $item]);

                        $availableRoomDetails .= '<div class="col-md-4">';
                        $availableRoomDetails .= '<div class="form-check form-check-inline">';
                        $availableRoomDetails .= '<input class="form-check-input form-control" type="checkbox" name="room[]" value="' . $item->id . '">';
                        $availableRoomDetails .= '<div class="card border">';
                        $availableRoomDetails .= '<img src="' . $image . '" alt="" class="card-img-top">';
                        $availableRoomDetails .= '<div class="card-body text-center">';
                        $availableRoomDetails .= '<p class="card-text small"> Name: ' . $item->name . ' </p>';
                        $availableRoomDetails .= '<p class="card-text small"> Room Facility: ' . $item->roomFacility->name . ' </p>';
                        $availableRoomDetails .= '<p class="card-text small"> Room No: ' . $item->room_no . ' </p>';
                        $availableRoomDetails .= '<p class="card-text small"> Capacity: ' . $item->capacity . ' </p>';
                        $availableRoomDetails .= '<p class="card-text small"> Room Type: ' . $item->types->name . ' </p>';
                        $availableRoomDetails .= '<p class="card-text small"> Price (Per Night): ' . $settings->currency . ' ' . number_format($item->price, 2) . ' </p>';
                        $availableRoomDetails .= '</div>';
                        $availableRoomDetails .= '</div>';
                        $availableRoomDetails .= '</div>';
                        $availableRoomDetails .= '</div>';
                    }
                    $availableRoomDetails .= '</div>'; // Close the row after processing the chunk
                }
            }

            return response()->json([
                'roomTypeDetails' => $roomTypeDetails,
                'availableRoomDetails' => $availableRoomDetails
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }




    /**
     * Get Room Details
     */
    public function getBookingRooms(Request $request)
    {
        $id = $request['id'];
        $booking = Booking::find($id);
        $settings = Settings::latest()->first();

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<th>Room Name</th>';
        $html .= '<th>Room No</th>';
        $html .= '<th>Room Type</th>';
        $html .= '<th>Room Capacity</th>';
        $html .= '<th>Room Price</th>';
        $html .= '</tr>';

        foreach ($booking->rooms as $room) {
            $html .= '<tr>';
            $html .= '<td>' . $room->name . '</td>';
            $html .= '<td>' . $room->room_no . '</td>';
            $html .= '<td>' . $room->types->name . '</td>';
            $html .= '<td>' . $room->capacity . '</td>';
            $html .= '<td>' . $settings->currency . ' ' . number_format($room->price, 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response()->json([$html]);
    }

    /**
     * Get Customer Details
     */
    public function getBookingCustomers(Request $request)
    {
        $id = $request['id'];
        $customer = Customer::find($id);

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td>Name :</td>';
        $html .= '<td>' . $customer->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Contact No :</td>';
        $html .= '<td>' . $customer->contact . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Email :</td>';
        $html .= '<td>' . $customer->email . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Address :</td>';
        $html .= '<td>' . $customer->address . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return response()->json([$html]);
    }



    public function status()
    {

        $title = 'Room Status';

        $type = RoomType::all();


        $data = Room::all();
        $data1 = Booking::all();
        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        return view('bookings.roomstatus', compact('data', 'title', 'breadcrumbs', 'type', 'data1'));
    }

    public function bookingReport()
    {
        $title = 'Booking Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Booking::all();
        return view('reports.bookingReports', compact('data'));
    }
}
