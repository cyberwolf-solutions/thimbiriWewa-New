<?php

namespace App\Http\Controllers;

use App\Models\Buffet;
use App\Models\Meal;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BuffetController extends Controller
{
    public function index()
    {
        $title = 'Buffet';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Buffet::all();


        return view('buffet.index', compact('title', 'breadcrumbs', 'data'));
    }



    public function create()
    {
        $title = 'Buffet';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('buffet.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('buffet.create', compact('title', 'breadcrumbs', 'is_edit'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode('<br>', $validator->errors()->all())
            ]);
        }

        try {
            // Check if Buffet with the same name already exists
            $existingBuffet = Buffet::where('name', $request->name)->first();
            if ($existingBuffet) {
                return response()->json([
                    'success' => false,
                    'message' => 'A buffet with this name already exists. Please update the existing buffet or delete it before creating a new one.'
                ]);
            }

            // Insert new Buffet record
            $buffet = Buffet::create([
                'name' => $request->name,
                'price' => $request->price,
                'created_by' => Auth::id(),
            ]);

            // Update Meal price if a meal with the same name exists
            $meal = Meal::where('name', $request->name)->first();
            if ($meal) {
                $meal->update(['unit_price' => $request->price]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Buffet created successfully!',
                'url' => route('buffet.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15|unique:categories,name,' . $id,
            'type' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|max:5000'
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
                'price' => $request->price,

                'updated_by' => Auth::user()->id,
            ];

            if ($request->file('image') != null) {
                $data['image_url'] = $image_url;
            }

            $Category = Buffet::find($id)->update($data);

            // if($Category != null){
            //     if($request->file('image') != null) {
            //         $preferred_name = trim($request->name);
            //         $path = public_path() . '/uploads/categories/' . $preferred_name . '.' . $request['image']->extension();
            //         if(file_exists($path)) {
            //             unlink($path);
            //         }

            //         $request['image']->move(public_path('uploads/categories'), $image_url);
            //     }
            // }

            return json_encode(['success' => true, 'message' => 'Category updated', 'url' => route('categories.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    public function edit(string $id)
    {
        $title = 'Buffet';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('buffet.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;
        $data = Buffet::find($id);

        return view('buffet.index', compact('title', 'breadcrumbs', 'is_edit', 'data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the Buffet entry
            $buffet = Buffet::find($id);

            if (!$buffet) {
                return response()->json(['success' => false, 'message' => 'Buffet not found.']);
            }

            // Update Meal price to 0 where Meal name matches Buffet name
            Meal::where('name', $buffet->name)->update(['unit_price' => 0.00]);

            // Soft delete the Buffet
            $buffet->update(['deleted_by' => Auth::user()->id]);
            $buffet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Buffet deleted successfully!',
                'url' => route('buffet.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }



    public function consumption()
    {
        $title = 'Buffet consumption';
    
        $breadcrumbs = [
            ['label' => $title, 'url' => '', 'active' => true],
        ];
    
        $data = DB::table('order_items')
            ->selectRaw('DATE(created_at) as date, itemable_id, COUNT(*) as count')
            ->whereIn('itemable_id', [1, 2, 3])
            ->groupByRaw('DATE(created_at), itemable_id')
            ->get();
    
        $mealData = DB::table('customer_board_meals')
            ->selectRaw('DATE(created_at) as date, mealtype as itemable_id, COUNT(*) as count')
            ->whereIn('mealtype', [1, 2, 3])
            ->groupByRaw('DATE(created_at), mealtype')
            ->get();
    
        // Merge both datasets by date and item ID
        $results = [];
    
        foreach ($data as $row) {
            $results[$row->date][$row->itemable_id]['order_items'] = $row->count;
        }
    
        foreach ($mealData as $row) {
            $results[$row->date][$row->itemable_id]['customer_board_meals'] = $row->count;
        }
    
        return view('buffet.consumption', compact('title', 'breadcrumbs', 'results'));
    }
    
}
