<?php

namespace App\Http\Controllers;

use App\Models\BoardConsumption;
use App\Models\User;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Purchases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function user()
    {
        $title = 'User Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = User::with('roles')->get();


        return view('reports.Userindex', compact('title', 'breadcrumbs', 'data'));
    }

    public function customer()
    {
        $title = 'Guest Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Customer::all();


        return view('reports.customerindex', compact('title', 'breadcrumbs', 'data'));
    }
    public function employee()
    {
        $title = 'Employees Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Employee::all();


        return view('reports.employeeindex', compact('title', 'breadcrumbs', 'data'));
    }
    public function supplier()
    {
        $title = 'Supplier Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Supplier::all();


        return view('reports.supplierindex', compact('title', 'breadcrumbs', 'data'));
    }
    public function purchase()
    {
        $title = 'Purchase Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Purchases::with(['supplier', 'items.product', 'payments'])->get();


        return view('reports.purchaseindex', compact('title', 'breadcrumbs', 'data'));
    }
    public function product()
    {
        $title = 'Product Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Product::with('category')->get();


        return view('reports.productindex', compact('title', 'breadcrumbs', 'data'));
    }

    public function booking()
    {
        $title = 'Booking Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Booking::all();
        return view('reports.bookingindex', compact('title', 'breadcrumbs', 'data'));
    }
    public function order()
    {
        $title = 'Order Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Order::all();
        return view('reports.orderindex', compact('title', 'breadcrumbs', 'data'));
    }

    public function buffet()
    {
        $title = 'Buffet consumption Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
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

        return view('reports.buffetindex', compact('title', 'breadcrumbs', 'results'));
    }

    public function boardcobsumption()
    {
        $title = 'Board Consumption Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = BoardConsumption::all();
        return view('reports.boardcobsumptionindex', compact('title', 'breadcrumbs', 'data'));
    }


    public function searchByType(Request $request)
    {
        $title = 'Order Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $type = $request->type;
        if ($type === 'All') {
            $data = Order::all(); // Fetch all orders when "All" is selected
        } else {
            $data = Order::where('type', $type)->get(); // Filter by the selected type
        }
        // $data = Order::where('type', $type)->get();

        // return response()->json($data);
        return view('reports.orderindex', compact('title', 'breadcrumbs', 'data'));
    }
}
