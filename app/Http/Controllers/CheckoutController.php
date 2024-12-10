<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Room;
use App\Models\Order;
use App\Models\checkout;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\checkincheckout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // $data = CheckinCheckout::select('booking_id', DB::raw('MAX(id) as id'), 'status') // Select relevant fields
        //     ->where('status', 'CheckedInANDCheckedOut') // Filter by status
        //     ->groupBy('booking_id', 'status') // Group by booking_id and status
        //     ->get();

        // dd($data);
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

        // $data = checkincheckout::where('status', 'CheckedIn')->get();
        $ids = CheckinCheckout::select(DB::raw('MIN(id) as id'))
            ->where('status', 'CheckedIn')
            ->groupBy('booking_id')
            ->pluck('id');
        $data = CheckinCheckout::whereIn('id', $ids)->get();


        // dd($data);
        $customers = Customer::all();

        return view('bookings.addcheckout', compact('title', 'breadcrumbs', 'is_edit', 'data', 'customers'));
    }
    public function getBookingPaymentDetails($bookingId, $roomId)
    {
        // Retrieve paid and due amounts for the selected booking
        // $bookingPaymentDetails = CheckinCheckout::where('booking_id', $bookingId)->first();
        $bookingPaymentDetails = CheckinCheckout::where('booking_id', $bookingId)
            ->where('room_no', $roomId)
            ->first();


        // Log::info('Booking Payment Details:', [
        //     'booking_id' => $bookingId,
        //     'paid_amount' => $bookingPaymentDetails ? $bookingPaymentDetails->paid_amount : 'N/A',
        //     'due_amount' => $bookingPaymentDetails ? $bookingPaymentDetails->due_amount : 'N/A',
        //     'total' => $bookingPaymentDetails ? $bookingPaymentDetails->total_amount : 'N/A',
        // ]);

        // Log::info('Booking Payment Details:', [
        //     'booking_id' => $bookingId,
        //     'paid_amount' => $bookingPaymentDetails ? $bookingPaymentDetails->paid_amount : 'N/A',
        //     'due_amount' => $bookingPaymentDetails ? $bookingPaymentDetails->due_amount : 'N/A',
        //      'total'=>$bookingPaymentDetails ? $bookingPaymentDetails->total_amount : 'N/A',
        // ]);

        return response()->json([
            'payed' => $bookingPaymentDetails->paid_amount,
            'total' => $bookingPaymentDetails->total_amount
        ]);
    }

    // public function getCustomerOrders($customerId, $roomId)
    // {
    //     $room = DB::table('rooms')->where('room_no', $roomId)->first();


    //     $roomIdFromTable = $room->id;

    //     dd($roomIdFromTable);

    //     $orders = Order::where('customer_id', $customerId)
    //     ->where('room_id', $roomId) // Filter by room ID
    //     ->where('type', 'RoomDelivery') // Filter by order type
    //     ->get();

    //     // $orders = Order::where('customer_id', $customerId)
    //     //     ->where('room_id', $roomId) // Filter by room ID
    //     //     ->where('type', 'RoomDelivery') // Filter by order type
    //     //     ->get();



    //     Log::info('Booking Payment Details:', [
    //         'booking_i ' => $roomId,
    //         'room Id' => $roomId,

    //     ]);


    //     $orderIds = $orders->pluck('id');

    //     $unpaidOrders = OrderPayment::whereIn('order_id', $orderIds)
    //     ->whereIn('payment_status', ['Unpaid'])
    //     ->get();
    
    //     return response()->json([
    //         'orders' => $orders,
    //         'orderIds' => $orderIds,
    //         'unpaidOrders' => $unpaidOrders,
    //     ]);
    // }


    public function getCustomerOrders($customerId , $roomId)
    {
        // Fetch orders for the specified customer ID where type = RoomDelivery
        // $orders = Order::where('customer_id', $customerId)
        //     ->where('type', 'RoomDelivery')
        //     ->get();

        $roomIdFromTable = Room::where('room_no', $roomId)->value('id');


        $orders = Order::where('customer_id', $customerId)
        ->where('room_id', $roomIdFromTable) // Filter by roomId as well
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

    // public function store(Request $request)
    // {



    //     $validator = Validator::make($request->all(), [
    //         'additional' => 'required',
    //         'note' => 'required',
    //         'tot' => 'required',
    //         'stot' => 'required',
    //         'payment_method' => 'required',
    //         'rooms_data' => 'required|json',
    //     ]);


    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()->all()
    //         ]);
    //     }

    //     $rid = $request->booking_room_id;

    //     $id = $request->checkincheckout_id;
    //     $checkout = checkincheckout::find($id);
    //     $room = Room::find($rid);
    //     $full_payed_amount = $request->payed + $request->fd;

    //     try {

    //         if ($checkout && $room) {



    //             $checkout->update([
    //                 'additional_payment' => $request->input('additional'),
    //                 'note' => $request->input('note'),
    //                 'full_payment' => $request->input('tot'),
    //                 'status' => 'CheckedInANDCheckedOut',
    //                 'sub_total' => $request->stot,
    //                 'discount' => $request->has('dis') && !is_null($request->dis) ? $request->dis : 0,

    //                 // 'due_amount'=>0,
    //                 // 'paid_amount'=>$request->total,
    //                 'full_payed_amount' => $full_payed_amount,
    //                 'payment_method' => $request->payment_method,
    //             ]);
    //             $room->update([
    //                 'status' => 'Available'
    //             ]);
    //             // $this->generatePDF($request);

    //             // Fetch unpaid orders dynamically based on the updated paid state
    //             $customerId = $checkout->customer_id;
    //             $orders = Order::where('customer_id', $customerId)
    //                 ->where('type', 'RoomDelivery')
    //                 ->get();
    //             $orderIds = $orders->pluck('id');
    //             OrderPayment::whereIn('order_id', $orderIds)
    //                 ->where('payment_status', 'Unpaid')
    //                 ->update(['payment_status' => 'Paid']);



    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Checkout data has been saved successfully.',
    //                 // 'redirect' => route('checkout.index')
    //                 'redirect' => route('checkout.invoice',  [$request->checkincheckout_id])
    //             ]);
    //         }
    //     } catch (\Throwable $th) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong! ' . $th->getMessage()
    //         ]);
    //     }
    // }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'additional' => 'required',
            'note' => 'required',
            // 'tot' => 'required',
            'payment_method' => 'required',
            'rooms_data' => 'required|json',
            'checkincheckout_id' => 'required',
            'booking_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        $roomsData = json_decode($request->rooms_data, true);
        $bookingId = $request->booking_id;

        try {
            foreach ($roomsData as $roomData) {
                $roomNo = $roomData['roomNo'];
                $subtotal = $roomData['subtotal'];
                $dueAmount = $roomData['dueAmount'];
                $additionalPayments = $roomData['additionalPayments'];
                $paidAmount = $roomData['paidAmount'];

                // Find the checkincheckout record based on booking_id and roomNo
                $checkinCheckout = CheckinCheckout::where('booking_id', $bookingId)
                    ->where('room_no', $roomNo)
                    ->first();

                if ($checkinCheckout) {
                    $checkinCheckout->update([
                        'additional_payment' => $additionalPayments,
                        'note' => $request->note,
                        'full_payment' => $subtotal,
                        'status' => 'CheckedInANDCheckedOut',
                        'sub_total' => $subtotal,
                        'discount' => $request->has('dis') && !is_null($request->dis) ? $request->dis : 0,
                        'due_amount' => $dueAmount,
                        'paid_amount' => $paidAmount,
                        'full_payed_amount' => $subtotal,
                        // 'payment_method' => $request->payment_method,
                    ]);

                    // Update room status to 'Available'
                    $room = Room::where('room_no', $roomNo)->first();
                    if ($room) {
                        $room->update(['status' => 'Available']);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Checkout data has been saved successfully.',
                'redirect' => route('checkout.invoice', [$request->booking_id])
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $th->getMessage()
            ]);
        }
    }






    // public function invoice(string $checkincheckout_id)
    // {

    //     // $data = checkincheckout::find($checkincheckout_id);
    //     $data = checkincheckout::where('booking_id', $checkincheckout_id)->get();
    //     return view('bookings.invoice', compact('data'));
    // }

    public function invoice(string $checkincheckout_id)
    {
        // Retrieve all rows with the given booking_id
        $data = checkincheckout::where('booking_id', $checkincheckout_id)->get();
    
        $data1 = checkincheckout::where('booking_id', $checkincheckout_id)->first();

        // Pass the data to the view
        return view('bookings.invoice', compact('data','data1'));
    }
    public function invoicee(string $checkincheckout_id)
    {
        // Retrieve all rows with the given booking_id
        // $data = checkincheckout::where('booking_id', $checkincheckout_id)->get();
        // $data = checkincheckout::where('id', $checkincheckout_id)->get();
        // $data1 = checkincheckout::where('id', $checkincheckout_id)->get();

        $data = checkincheckout::find($checkincheckout_id); 
        // $data1 = checkincheckout::find($checkincheckout_id); 
        // Pass the data to the view
        return view('bookings.invoice2', compact('data'));
    }
    public function additionalInvoice($customerId, $checkoutDate , $room_no)
    {

        $room_id = Room::where('room_no', $room_no)->value('id'); 
        
        $cid = $customerId;
        // dd($room_id);

        // $checkinCheckout = CheckinCheckout::where('customer_id', $customerId)
        //     ->whereDate('checkout', $checkoutDate)
        //     ->first();
        $checkinCheckout = CheckinCheckout::where('customer_id', $customerId)
        ->whereDate('checkout', $checkoutDate)
        ->where('room_no', $room_no)
        ->first();

        

        $updatedAt = $checkinCheckout ? $checkinCheckout->updated_at->format('Y-m-d') : null;

        if ($checkinCheckout) {

            $updatedAt = $checkinCheckout->updated_at->format('Y-m-d');

            $customerId = $checkinCheckout->customer_id;

            $orders = Order::where('customer_id', $customerId)
                ->where('type', 'RoomDelivery')
                ->where('room_id', $room_id)
                ->get();

                // dd($room_id , $room_no);

            $orderIds = $orders->pluck('id');

            $orderItems = OrderItem::whereIn('order_id', $orderIds)->get();


            $data = OrderPayment::whereDate('updated_at', $updatedAt)->get();
        }
        return view('bookings.additional', compact('data', 'checkinCheckout', 'orders', 'orderItems', 'cid'));
    }
}
