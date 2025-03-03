<?php

namespace App\Http\Controllers;

use App\Models\BordingType;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Room;
use App\Models\RoomFacilities;
use App\Models\RoomPricing;
use App\Models\RoomSize;
use App\Models\RoomType;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Rooms';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $customers = Customer::whereHas('bookings', function ($query) {
            $query->whereIn('status', ['OnGoing', 'Pending']);
        })->with('bookings.rooms')->get();
        

        $boarding = BordingType::all();

        $data = Room::all();
        $data1 = RoomFacilities::all();
        return view('rooms.index', compact('title', 'breadcrumbs','customers','boarding', 'data' , 'data1'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Rooms';
        $data1 = RoomFacilities::all();

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('rooms.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];
        $customers = Customer::whereHas('bookings', function ($query) {
            $query->whereIn('status', ['OnGoing', 'Pending']);
        })->with('bookings.rooms')->get();
        

        $boarding = BordingType::all();
        $cus = CustomerType::all();
        $is_edit = false;
        $types = RoomType::all();
        $sizes = RoomSize::all();

        return view('rooms.create-edit', compact('title', 'breadcrumbs','cus','customers','boarding', 'is_edit', 'types', 'sizes' ,  'data1'));
    }
 
    public function store(Request $request)
    {
        // dd($request->all());
        Log::info('Pricing Data:', $request->pricing);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name',
            'room_no' => 'required',
            'type' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|max:5000',
            'capacity' => 'required|integer',
            'size' => 'required',
            'facility' => 'required',
            'quantity' => 'required|integer|min:1',
            // 'pricing' => 'required|array',
            // 'pricing.*.boarding_type_id' => 'required|exists:bording_type,id',
            // 'pricing.*.customer_type_id' => 'required|exists:customer_types,id',
            // 'pricing.*.currency' => 'required|in:lkr,usd,euro',
            // 'pricing.*.rate' => 'required|numeric|min:0'
        ]);
    
        if ($validator->fails()) {
            $errors = collect($validator->errors()->all())->implode('<br>');
            return response()->json(['success' => false, 'message' => $errors]);
        }
    
        $image_url = null;
        if ($request->hasFile('image')) {
            $preferred_name = trim($request->name);
            $image_url = $preferred_name . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/rooms'), $image_url);
        }
    
        try {
            $roomIds = []; // Array to store created room IDs
    
            for ($i = 0; $i < $request->quantity; $i++) {
                $roomData = [
                    'name' => $request->name,
                    'room_no' => $request->room_no . ($i + 1), 
                    'type' => $request->type,
                    'price' => $request->price,
                    'status' => $request->status,
                    'description' => $request->description,
                    'image_url' => $image_url,
                    'capacity' => $request->capacity,
                    'size' => $request->size,
                    'created_by' => Auth::id(),
                    'RoomFacility_id' => $request->facility
                ];
    
                $room = Room::create($roomData);
                $roomIds[] = $room->id; // Store room ID
            }
    
            // Save pricing details for all created rooms
            foreach ($roomIds as $roomId) {
                foreach ($request->pricing as $pricing) {
                    RoomPricing::create([
                        'room_id' => $roomId,
                        'boarding_type_id' => $pricing['boarding_type_id'],
                        'customer_type_id' => $pricing['customer_type_id'],
                        'currency' => $pricing['currency'],
                        'rate' => $pricing['rate']
                    ]);
                }
            }
    
            return response()->json(['success' => true, 'message' => 'Room(s) created successfully!', 'url' => route('rooms.index')]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Something went wrong! ' . $th->getMessage()]);
        }
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Room::find($id);

        $settings = Settings::latest()->first();

        if ($data->image_url != null) {
            $image = 'uploads/rooms/' . $data->image_url;
        } else {
            $image = 'uploads/cutlery.png';
        }

        $html = '<table class="table" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td colspan="2"><img style="padding-left: 25%;" src="' . URL::asset($image) . '" alt="" height="100"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Name :</td>';
        $html .= '<td>' . $data->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Room No :</td>';
        $html .= '<td>' . $data->room_no . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Type :</td>';
        $html .= '<td>' . $data->types->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Facility :</td>';
        $html .= '<td>' . $data->roomFacility->List . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Price :</td>';
        $html .= '<td>' . $settings->currency . ' ' . number_format($data->price, 2) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Size (m2) :</td>';
        $html .= '<td>' . $data->size . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Capacity :</td>';
        $html .= '<td>' . $data->capacity . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Status :</td>';
        $html .= '<td>' . $data->status . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Description :</td>';
        $html .= '<td>' . $data->description . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created By: </td>';
        $html .= '<td>' . $data->createdBy->name . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Created Date: </td>';
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
        $title = 'Rooms';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('rooms.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $types = RoomType::all();
        $data = Room::find($id);
        $sizes = RoomSize::all();
        $data1 = RoomFacilities::all();


        return view('rooms.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'types', 'sizes','data1'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name,' . $id,
            'room_no' => 'required',
            'type' => 'required',
            'price' => 'required',
            'status' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000',
            'capacity' => 'required',
            'size' => 'required',
            'facility'=>'required'

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

        if ($request->file('image') != null) {
            $preferred_name = trim($request->name);
            $image_url = $preferred_name . '.' . $request['image']->extension();
        }

        try {
            $data = [
                'name' => $request->name,
                'room_no' => $request->room_no,
                'type' => $request->type,
                'price' => $request->price,
                'status' => $request->status,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'size' => $request->size,
                'updated_by' => Auth::user()->id,
                'RoomFacility_id' => $request->facility
            ];

            if ($request->file('image') != null) {
                $data['image_url'] = $image_url;
            }

            $room = Room::find($id)->update($data);
            $Room = Room::find($id);

            if ($Room != null) {
                if ($request->file('image') != null) {
                    $preferred_name = trim($request->name);
                    $path = public_path() . '/uploads/rooms/' . $preferred_name . '.' . $request['image']->extension();
                    if (file_exists($path)) {
                        unlink($path);
                    }

                    $request['image']->move(public_path('uploads/rooms'), $image_url);
                }
            }

            return json_encode(['success' => true, 'message' => 'Room updated', 'url' => route('rooms.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $Room = Room::find($id);
            $Room->update(['deleted_by' => Auth::user()->id]);
            $Room->delete();

            return json_encode(['success' => true, 'message' => 'Room deleted', 'url' => route('rooms.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
