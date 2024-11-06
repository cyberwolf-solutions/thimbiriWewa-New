<?php

namespace App\Http\Controllers;

use App\Models\BordingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BordingTypeCOntroller extends Controller
{
    public function index()
    {

        $title = 'Boarding Type';




        $data = BordingType::all();
        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];


        return view('bording.index', compact('data', 'title', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        //dd($request->all());

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

            $existingFacility = BordingType::where('name', $request->name)->first();

            if ($existingFacility) {
                return json_encode(['success' => false, 'message' => 'This Boarding type exists']);
            }


            $data = [
                'name' => $request->name,
                'price' => $request->list,
                'created_by' => Auth::user()->id,
            ];

            $Roomfacility = BordingType::create($data);

            return json_encode(['success' => true, 'message' => 'Boarding Type created', 'url' => route('bording-type.index')]);
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
                'price' => $request->list,
                'updated_by' => Auth::user()->id,
            ];

            $RoomType = BordingType::find($id)->update($data);

            return json_encode(['success' => true, 'message' => 'Boarding Type updated', 'url' => route('bording-type.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    public function destroy(string $id)
    {
        try {

            $RoomType = BordingType::find($id);
            $RoomType->update(['deleted_by' => Auth::user()->id]);
            $RoomType->delete();

            return json_encode(['success' => true, 'message' => 'Boarding Type deleted', 'url' => route('bording-type.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

}
