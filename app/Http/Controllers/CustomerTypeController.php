<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Guests Type';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = CustomerType::all();
        return view('customers.type-index', compact('title', 'breadcrumbs', 'data'));
    }

    public function create() {
        $title = 'Create Guests Type ';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('customer.type'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        return view('customers.type', compact('title', 'breadcrumbs', 'is_edit'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:customer_types,type',
            'description' => 'required|string',
        
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
                'type' => $request->type,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
            ];

            $customer = CustomerType::create($data);

            return json_encode(['success' => true, 'message' => 'Guests Type created', 'modal' => true,]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerType $customerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerType $customerType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerType $customerType)
    {
        //
    }

    public function destroy(string $id) {
        try {
            $customer = CustomerType::find($id);
            $customer->update(['deleted_by' => Auth::user()->id]);
            $customer->delete();

            return json_encode(['success' => true, 'message' => 'Guests Type deleted', 'url' => route('customers.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
