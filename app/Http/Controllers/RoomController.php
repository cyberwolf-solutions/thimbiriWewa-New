<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomFacilities;
use App\Models\RoomSize;
use App\Models\RoomType;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use DateTime;

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
        $data = Room::all();
        $data1 = RoomFacilities::all();
        return view('rooms.index', compact('title', 'breadcrumbs', 'data' , 'data1'));
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

        $is_edit = false;
        $types = RoomType::all();
        $sizes = RoomSize::all();

        return view('rooms.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'types', 'sizes' ,  'data1'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    
{

    // dd($request->quantity);
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
        'quantity' => 'required|integer|min:1'
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
    
    $image_url = null;
    if ($request->file('image') != null) {
        $preferred_name = trim($request->name);
        $image_url = $preferred_name . '.' . $request['image']->extension();
    
        // Move the image upload code outside the loop
        $request['image']->move(public_path('uploads/rooms'), $image_url);
    }
    
    try {
        for ($i = 0; $i < $request->quantity; $i++) {
            $data = [
                'name' => $request->name,
                'room_no' => $request->room_no . ($i + 1), 
                'type' => $request->type,
                'price' => $request->price,
                'status' => $request->status,
                'description' => $request->description,
                'image_url' => $image_url, // Use the same image URL for each room
                'capacity' => $request->capacity,
                'size' => $request->size,
                'created_by' => Auth::user()->id,
                'RoomFacility_id' => $request->facility
            ];
    
            $Room = Room::create($data);
        }
    
        return json_encode(['success' => true, 'message' => 'Room(s) created', 'url' => route('rooms.index')]);
    } catch (\Throwable $th) {
        return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th->getMessage()]);
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
