<?php

namespace App\Http\Controllers;

use App\Events\notifyBot;
use App\Events\notifyKot;
use App\Models\BookingsRooms;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Meal;
use App\Models\Modifier;
use App\Models\ModifiersCategories;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\OrderNote;
use App\Models\OrderPayment;
use App\Models\Restaurant;
use App\Models\Room;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Modifiers;

class RestaurantController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'POS';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $categories = Category::all()->where('type', 'Restaurant');
        $meals = Meal::all();

        return view('restaurant.index', compact('title', 'breadcrumbs', 'categories', 'meals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    public function note() {
        return view('restaurant.notes');
    }
    public function process() {
        $inProgress = Order::where('status', 'Pending')->get();
        $ready = Order::where('status', 'InProgress')->get();
        return view('restaurant.in-process', compact('inProgress', 'ready'));
    }
    public function tables(Request $request) {
        $table = $request->table;
        $tables = Table::all()->where('availability', 'Available');
        return view('restaurant.tables-modal', compact('tables', 'table'));
    }
    public function rooms(Request $request) {
        $room = $request->room;
        $rooms = BookingsRooms::all();
        return view('restaurant.rooms-modal', compact('rooms', 'room'));
    }
    public function customer(Request $request) {
        $customer = $request->customer;
        $customers = Customer::all();

        return view('restaurant.customer-modal', compact('customers', 'customer'));
    }
    public function customerAdd() {
        return view('restaurant.customer-add-modal');
    }
    public function discount(Request $request) {
        $discount = $request->discount;
        $discount_method = $request->discount_method;
        return view('restaurant.discount-modal', compact('discount', 'discount_method'));
    }
    public function vat(Request $request) {
        $vat = $request->vat;
        $vat_method = $request->vat_method;
        return view('restaurant.vat-modal', compact('vat', 'vat_method'));
    }
    public function modifiers(Request $request) {
        $id = $request->id;
        $category = Meal::find($id)->category_id;
        $modifiers = ModifiersCategories::where('category_id', $category)->get();
        return view('restaurant.modifiers-modal', compact('modifiers', 'id'));
    }

    public function checkout(Request $request) {
        $validator = Validator::make($request->all(), [
            'customer' => 'required',
            'room' => 'required',
            'table' => 'required',
            'sub' => 'required',
            'discount' => 'required',
            'vat' => 'required',
            'total' => 'required',
            'kitchen_note' => 'required',
            'bar_note' => 'required',
            'staff_note' => 'required',
            'payment_note' => 'required',
            'type' => 'required',
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
                'customer_id' => $request->customer,
                'room_id' => $request->room,
                'table_id' => $request->table,
                'orderable_type' => 'App\Models\OrderItem',
                'orderable_id' => '0',
                'order_date' => date('d-m-Y'),
                'type' => $request->type,
                'created_by' => Auth::user()->id,
            ];
            //Create order
            $order = Order::create($data);

            //get the cart items
            $cart = json_decode($request->cart, true);

            $isKOT = false;
            $isBOT = false;

            foreach ($cart as $key => $value) {

                $meal = Meal::where('id', $value['id'])->whereHas('products', function ($query) {
                    $query->where('type', 'KOT');
                })->first();

                if ($meal) {
                    $isKOT = true;
                }
                $meal = Meal::where('id', $value['id'])->whereHas('products', function ($query) {
                    $query->where('type', 'BOT');
                })->first();

                if ($meal) {
                    $isBOT = true;
                }

                $data = [
                    'itemable_type' => 'App\Models\Meal',
                    'itemable_id' => $value['id'],
                    'order_id' => $order->id,
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                    'total' => $value['price'] * $value['quantity'],
                ];

                $item = OrderItem::create($data);

                if (isset($value['modifiers'])) {
                    foreach ($value['modifiers'] as $key => $modifier) {
                        $data = [
                            'item_id' => $item->id,
                            'modifier_id' => $modifier['id'],
                            'price' => $modifier['price'],
                            'quantity' => $value['quantity'],
                            'total' => $modifier['price'] * $value['quantity'],
                        ];

                        OrderItemModifier::create($data);
                    }
                }
            }

            $data = [
                'order_id' => $order->id,
                'kot' => $request->kitchen_note,
                'bot' => $request->bar_note,
                'staff' => $request->staff_note,
                'payment' => $request->payment_note,
            ];

            OrderNote::create($data);

            $data = [
                'order_id' => $order->id,
                'date' => date('d-m-Y'),
                'sub_total' => $request->sub,
                'vat' => $request->vat,
                'discount' => $request->discount,
                'total' => $request->total,
                // 'payment_type' => '',
                'created_by' => Auth::user()->id,
            ];

            if ($request->type == 'Dining') {
                $data['payment_status'] = 'Paid';
            }else if ($request->type == 'TakeAway') {
                $data['payment_status'] = 'Paid';
            }

            OrderPayment::create($data);

            //Reserve the table if selected
            if ($request->table != 0) {
                $table = Table::find($request->table);
                $table->availability = 'Order Taken';
                $table->save();
            }

            if ($isKOT) {
                event(new notifyKot('New KOT'));
            }
            if ($isBOT) {
                event(new notifyBot('New BOT'));
            }

            return response()->json(['success' => true, 'message' => 'Order Placed!', 'url' => route('order.print', [$order->id])]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }
    public function completeMeal(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
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
            $id = $request->id;
            $item = OrderItem::find($id);
            $item->updated_by = Auth::user()->id;
            $item->status = "Complete";
            $item->save();

            $order = Order::find($item->order_id);

            $totalItems = $order->items->count();
            $totalCompletedItems = $order->items->where('status',  'Complete')->count();

            $completedPercentage = $totalCompletedItems / $totalItems * 100;

            $order->progress = $completedPercentage;

            if ($totalItems == $totalCompletedItems) {
                $order->status = 'InProgress';

                //Order Complete the table if selected
                if ($order->table_id != 0) {
                    $table = Table::find($order->table_id);
                    $table->availability = 'Order Complete';
                    $table->save();
                }
            }

            $order->save();

            return response()->json(['success' => true, 'message' => 'Order completed!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
    public function completeOrder(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
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
            $id = $request->id;

            $order = Order::find($id);
            $order->status = 'Complete';
            $order->save();

            //Make the table available
            if ($order->table_id != 0) {
                $table = Table::find($order->table_id);
                $table->availability = 'Available';
                $table->save();
            }

            return response()->json(['success' => true, 'message' => 'Order completed!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
