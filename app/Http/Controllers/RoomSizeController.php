<?php

namespace App\Http\Controllers;

use App\Models\RoomSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomSizeController extends Controller
{
    public function index()
    {

        $title = 'Room Size';


        $data = RoomSize::all();

        $breadcrumbs = [

            ['label' => $title, 'url' => '', 'active' => true],
        ];
        return view('room-size.index',  compact('title', 'breadcrumbs', 'data'));
    }

    public function store(Request $request)
    {


        
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'size' => 'required|numeric',
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
                'size' => $request->size,
                'created_by' => Auth::user()->id,
            ];

            $RoomSize = RoomSize::create($data);

            return json_encode(['success' => true, 'message' => 'Room Size created', 'url' => route('room-size.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }



    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|numeric',
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
                'size' => $request->size,
                'updated_by' => Auth::user()->id,
            ];

            $RoomType = RoomSize::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Room Size updated', 'url' => route('room-size.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    public function destroy(string $id)
    {
        try {

            $RoomType = RoomSize::find($id);
            $RoomType->update(['deleted_by' => Auth::user()->id]);
            $RoomType->delete();

            return json_encode(['success' => true, 'message' => 'Room Type deleted', 'url' => route('room-size.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
