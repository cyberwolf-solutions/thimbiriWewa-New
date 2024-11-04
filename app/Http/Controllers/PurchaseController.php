<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\PurchasePayment;
use App\Models\Purchases;
use App\Models\Settings;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Purchase';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = Purchases::all();
        return view('purchases.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Purchase';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('purchases.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];

        $is_edit = false;

        $suppliers = Supplier::all();
        $products = Ingredient::all();

        $latest = Purchases::latest()->first();

        if ($latest) {
            $latest = $latest->id;
        } else {
            $latest = 0;
        }

        $latest++;

        return view('purchases.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'suppliers', 'products', 'latest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'supplier' => 'required',
            'note' => 'nullable',
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

        // Retrieve products from the request
        $products = json_decode($request->input('products'), true);

        if (empty($products)) {
            return response()->json(['success' => false, 'message' => 'Select at least one item']);
        }

        try {

            // Calculate subtotal
            $subtotal = 0;
            foreach ($products as $product) {
                $subtotal += $product['price'] * $product['quantity'];
            }

            // Retrieve other form data
            $date = $request->input('date');
            $supplierId = $request->input('supplier');
            $note = $request->input('note');
            $discount = $request->input('discount');
            $vatPercentage = $request->input('vat_percentage');

            // Calculate total after discount and VAT
            $subtotalAfterDiscount = $subtotal - $discount;
            $vatAmount = ($subtotalAfterDiscount * $vatPercentage) / 100;
            $total = $subtotalAfterDiscount + $vatAmount;

            $data = [
                'date' => $date,
                'supplier_id' => $supplierId,
                'note' => $note,
                'sub_total' => $subtotal,
                'discount' => $discount,
                'vat' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'total' => $total,
                'created_by' => Auth::user()->id,
            ];

            $purchase = Purchases::create($data);

            foreach ($products as $product) {
                $data = [
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'total' => $product['price'] * $product['quantity'],
                ];
                PurchaseItem::create($data);
            }

            Supplier::where('id', $supplierId)->increment('balance', $total);

            return json_encode(['success' => true, 'message' => 'Purchase created', 'url' => route('purchases.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $title = 'Purchase';

        $data = Purchases::find($id);
        $settings = Settings::latest()->first();

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('purchases.index'), 'active' => false],
            ['label' => $settings->purchase($data->id), 'url' => '', 'active' => true],
        ];


        return view('purchases.show', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Purchase';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('purchases.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $is_edit = true;

        $suppliers = Supplier::all();
        $products = Ingredient::all();

        $data = Purchases::find($id);

        return view('purchases.create-edit', compact('title', 'breadcrumbs', 'is_edit', 'data', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'supplier' => 'required',
            'note' => 'nullable',
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

        // Retrieve products from the request
        $products = json_decode($request->input('products'), true);

        if (empty($products)) {
            return response()->json(['success' => false, 'message' => 'Select at least one item']);
        }

        // Retrieve the purchase instance to update
        $purchase = Purchases::findOrFail($id);

        try {

            // Calculate subtotal, discount, VAT, and total
            $subtotal = 0;
            foreach ($products as $product) {
                $subtotal += $product['price'] * $product['quantity'];
            }
            $discount = $request->input('discount');
            $vatPercentage = $request->input('vat_percentage');
            $subtotalAfterDiscount = $subtotal - $discount;
            $vatAmount = ($subtotalAfterDiscount * $vatPercentage) / 100;
            $total = $subtotalAfterDiscount + $vatAmount;

            // Update purchase details
            $purchase->update([
                'date' => $request->input('date'),
                'supplier_id' => $request->input('supplier'),
                'note' => $request->input('note'),
                'sub_total' => $subtotal,
                'discount' => $discount,
                'vat' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'total' => $total,
                'updated_by' => Auth::user()->id,
            ]);

            // Delete items that are not in the new products array
            $existingProductIds = $purchase->items->pluck('product_id')->toArray();
            $newProductIds = collect($products)->pluck('id')->toArray();
            $productsToDelete = array_diff($existingProductIds, $newProductIds);
            PurchaseItem::whereIn('product_id', $productsToDelete)->delete();

            // Add new items to the purchase
            foreach ($products as $product) {
                $existingItem = $purchase->items()->where('product_id', $product['id'])->first();
                if ($existingItem) {
                    // Update existing item
                    $existingItem->update([
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'total' => $product['price'] * $product['quantity'],
                    ]);
                } else {
                    // Create new item
                    $purchase->items()->create([
                        'product_id' => $product['id'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'total' => $product['price'] * $product['quantity'],
                    ]);
                }
            }

            // Update supplier balance
            Supplier::where('id', $request->input('supplier'))->increment('balance', $total);

            return json_encode(['success' => true, 'message' => 'Purchase updated', 'url' => route('purchases.index')]);
        } catch (\Throwable $th) {
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        try {

            $purchase = Purchases::find($id);
            $purchase->update(['deleted_by' => Auth::user()->id]);

            $items = PurchaseItem::where('purchase_id', $purchase->id)->delete();

            $supplier = Supplier::find($purchase->supplier_id);
            $balance = $supplier->balance - $purchase->total;
            $supplier->update(['balance' => $balance]);

            $purchase->delete();

            return json_encode(['success' => true, 'message' => 'Purchase deleted', 'url' => route('purchases.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    public function viewAddPayment(string $id) {
        $data = Purchases::find(decrypt($id));

        $due = $data->total - $data->paymentSum();

        $is_edit = false;

        return view('purchases.payment', compact('data', 'is_edit', 'due'));
    }

    public function viewPayments() {
        $data = PurchasePayment::all();

        return view('purchases.payments-modal', compact('data'));
    }

    public function addPayment(Request $request) {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required',
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

            $id = decrypt($request->id);

            $purchaseOrder = Purchases::find($id);

            $total = $purchaseOrder->total;

            // Retrieve all payments for the purchase-order
            $payments = PurchasePayment::where('purchase_id', $purchaseOrder->id)->get();

            // Calculate the total amount paid, including the current payment
            $totalAmountPaid = $payments->sum('amount') + $request->amount;


            // Determine the payment status
            if ($totalAmountPaid >= $total) {
                $status = 'Paid';
            } elseif ($totalAmountPaid > 0) {
                $status = 'Partially Paid';
            } else {
                $status = 'Unpaid';
            }

            // Update the payment status in the database
            $purchaseOrder->update(['payment_status' => $status]);

            $data = [
                'purchase_id' => $purchaseOrder->id,
                'date' => $request->date,
                'amount' => $request->amount,
                'reference' => $request->reference,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
            ];

            // Handle file upload for receipt
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');

                // Generate a unique filename based on date and other details
                $fileName = sprintf(
                    'purchase_payment_%s_%s.%s', // Add placeholders for the purchase order ID and file extension
                    $purchaseOrder->id,
                    now()->format('YmdHis'), // Use the current date and time for uniqueness
                    $file->getClientOriginalExtension() // Get the original file extension
                );


                // Store the file with the custom name
                $filePath = $file->storeAs('receipts', $fileName, 'public');

                // Add the file path to the $data array
                $data['receipt'] = $filePath;
            }

            $payment = PurchasePayment::create($data);

            $supplier = Supplier::find($purchaseOrder->supplier_id);
            $balance = $supplier->balance - $request->amount;
            $supplier->update(['balance' => $balance]);

            return json_encode(['success' => true, 'message' => 'Payment Added', 'url' => route('purchases.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    public function purchaseReport(){
        $title = 'Purchase Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        // $data = Purchases::all();
        $data = Purchases::with(['supplier', 'items.product', 'payments'])->get();
        return view ('reports.purchaseReports' , compact('data'));
    }
}
