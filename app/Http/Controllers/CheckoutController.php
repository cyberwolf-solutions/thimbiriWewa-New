<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Room;
use App\Models\Order;
use App\Models\checkout;
use App\Models\Customer;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\checkincheckout;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{
    public function index()
    {

        $title = 'Checkout';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = checkincheckout::where('status', 'CheckedInANDCheckedOut')->get();

        return view('bookings.checkout', compact('title', 'breadcrumbs', 'data'));
    }

    public function create()
    {

        $title = 'Checkout';
        $is_edit = true;

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = checkincheckout::where('status', 'CheckedIn')->get();
        $customers = Customer::all();

        return view('bookings.addcheckout', compact('title', 'breadcrumbs', 'is_edit', 'data', 'customers'));
    }
    public function getBookingPaymentDetails($bookingId)
    {
        // Retrieve paid and due amounts for the selected booking
        $bookingPaymentDetails = CheckinCheckout::where('booking_id', $bookingId)->first();

        return response()->json([
            'payed' => $bookingPaymentDetails->paid_amount,
            // 'due' => $bookingPaymentDetails->due_amount
        ]);
    }

    public function getCustomerOrders($customerId)
    {
        // Fetch orders for the specified customer ID where type = RoomDelivery
        $orders = Order::where('customer_id', $customerId)
            ->where('type', 'RoomDelivery')
            ->get();


        $orderIds = $orders->pluck('id');


        $unpaidOrders = OrderPayment::whereIn('order_id', $orderIds)
            ->where('payment_status', 'Unpaid')
            ->get();




        return response()->json([
            'orders' => $orders,
            'orderIds' => $orderIds,
            'unpaidOrders' => $unpaidOrders,
        ]);
    }

    public function getCheckinCheckoutId(Request $request)
    {

        $customerId = $request->input('customer_id');
        $bookingId = $request->input('booking_id');


        $checkinCheckoutId = CheckinCheckout::where('customer_id', $customerId)
            ->where('booking_id', $bookingId)
            ->value('id');


        return response()->json(['checkincheckout_id' => $checkinCheckoutId]);
    }

    public function store(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'additional' => 'required',
            'note' => 'required',
            'tot' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        $rid = $request->booking_room_id;

        $id = $request->checkincheckout_id;
        $checkout = checkincheckout::find($id);
        $room = Room::find($rid);
        $full_payed_amount= $request->payed + $request->fd;

        try {

            if ($checkout && $room) {



                $checkout->update([
                    'additional_payment' => $request->input('additional'),
                    'note' => $request->input('note'),
                    'full_payment' => $request->input('tot'),
                    'status' => 'CheckedInANDCheckedOut',
                    // 'due_amount'=>0,
                    // 'paid_amount'=>$request->total,
                    'full_payed_amount'=>$full_payed_amount
                ]);
                $room->update([
                    'status' => 'Available'
                ]);
                // $this->generatePDF($request);

                // Fetch unpaid orders dynamically based on the updated paid state
                $customerId = $checkout->customer_id;
                $orders = Order::where('customer_id', $customerId)
                    ->where('type', 'RoomDelivery')
                    ->get();
                $orderIds = $orders->pluck('id');
                OrderPayment::whereIn('order_id', $orderIds)
                    ->where('payment_status', 'Unpaid')
                    ->update(['payment_status' => 'Paid']);



                return response()->json([
                    'success' => true,
                    'message' => 'Checkout data has been saved successfully.',
                    // 'redirect' => route('checkout.index')
                    'redirect' => route('checkout.invoice',  [$request->checkincheckout_id])
                ]);
            }
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }


    public function invoice(string $checkincheckout_id)
    {

        $data = checkincheckout::find($checkincheckout_id);
        return view('bookings.invoice', compact('data'));
    }


    public function additionalInvoice($customerId , $checkoutDate)
    {

        $cid = $customerId;
        
        $checkinCheckout = CheckinCheckout::where('customer_id', $customerId)
        ->whereDate('checkout', $checkoutDate)
        ->first();

        $updatedAt = $checkinCheckout ? $checkinCheckout->updated_at->format('Y-m-d') : null;

        if ($checkinCheckout) {

            $updatedAt = $checkinCheckout->updated_at->format('Y-m-d');

            $customerId = $checkinCheckout->customer_id;


            $orders = Order::where('customer_id', $customerId)
            ->where('type', 'RoomDelivery')
            ->get();

            $orderIds = $orders->pluck('id');

            $orderItems = OrderItem::whereIn('order_id', $orderIds)->get();


            $data = OrderPayment::whereDate('updated_at', $updatedAt)->get();

            

        }





        

        return view ('bookings.additional' , compact('data' ,'checkinCheckout' , 'orders' , 'orderItems' , 'cid'));
        
    }
}
