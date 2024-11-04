<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomFacilityController extends Controller
{
    public function index()
    {

        $title = 'Room Facility';




        $data = RoomFacilities::all();
        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];


        return view('facilities.index', compact('data', 'title', 'breadcrumbs'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'list' => 'required',

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

            $existingFacility = RoomFacilities::where('name', $request->name)->first();

            if ($existingFacility) {
                return json_encode(['success' => false, 'message' => 'This facility already exists']);
            }


            $data = [
                'name' => $request->name,
                'List' => $request->list,
                'created_by' => Auth::user()->id,
            ];

            $Roomfacility = RoomFacilities::create($data);

            return json_encode(['success' => true, 'message' => 'Room Facility created', 'url' => route('room-facility.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }



    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'list' => 'required',
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
            $data = [
                'name' => $request->name,
                'List' => $request->list,
                'updated_by' => Auth::user()->id,
            ];

            $RoomType = RoomFacilities::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Room Facility updated', 'url' => route('room-facility.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    public function destroy(string $id)
    {
        try {

            $RoomType = RoomFacilities::find($id);
            $RoomType->update(['deleted_by' => Auth::user()->id]);
            $RoomType->delete();

            return json_encode(['success' => true, 'message' => 'Room Facility deleted', 'url' => route('room-facility.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }



}
