<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\User;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Admin;
use App\Models\Cart;
use Auth;
use App\Services\Payout;

class OrderController extends Controller
{
    public function __construct() {
        $this->middleware('customer')->only(['store']);
        $this->middleware('admin')->only(['update', 'index']);
    }

    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')
            ->with(['product', 'user', 'customer', 'cart'])->get();

        foreach ($orders as $order) {
            $order->cart->cart = unserialize($order->cart->cart);
        }

        return response()->json(['status' => true,
                                 'orders' => $orders], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'cart_id' => 'required|exists:carts,id',
            'amount_charged' => 'required|numeric',
            'order_notes' => 'nullable|string',
            'trx_id' => 'required|alpha_num',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);

        }

        $cart = Cart::find($data['cart_id']);
        $cart->purchased = true;
        $cart->update();

        $productCart = unserialize($cart->cart);
        $items = $productCart['items'];
        $productIds = [];

        $data['status'] = 'paid';

        $order = Order::create($data);

        foreach ($items as $item) {
            array_push($productIds, $item['item']['id']);
            $product = Product::find($item['item']['id']);
            $product->quantity_sold += $item['qty'];
            $product->update();
        }

        $order->product()->sync($productIds, false);

        $order = (array)$order;
        $order['cart'] = $productCart;
        $order['user'] = Auth::user();
        $order = (object)$order;

        // Mail::to(Auth::user()->email)->send(new OrderConfirmed($order));

        return response()->json(['status' => true,
                                 'order' => $order], 200);
        
    }

    public function show($id)
    {
        $orders = Order::where('user_id', Auth::user()->id)
        ->with(['product', 'user', 'customer', 'cart'])->get();

        if (!$orders) {
            return response()->json(['status' => false,
                                     'message' => 'order does not exist'], 404);
        }

        foreach ($orders as $order) {
            $order->cart->cart = unserialize($order->cart->cart);
        }

        return response()->json(['status' => true,
                                 'order' => $orders], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'status' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $order = Order::find($id);

        if (array_key_exists('status', $data)) {
            if ($data['status'] && $order->status !== 'payout completed') {
                $order->status = $data['status'];
            }
        }

        if (array_key_exists('remark', $data)) {
            if ($data['remark']) {
                $order->remark = $data['remark'];
            }
        }

        $order->update();

        $order = Order::where('id', $id)->with(['product', 'cart'])->first();

        if ($order->status === 'confirmed') {
            return $this->payVendor($order);
        }
        else {
            return response()->json(['status' => true,
                                     'order' => $order], 200);
        } 
    }

    public function payVendor($order) {
        $completed = false;
        if ($order->status === 'payout in progress' || $order->status === 'payout completed') {
            return response()->json(['status' => false,
                                     'message' => 'transaction already sent'], 400);
        }
        
        $order->status = 'payout in progress';
        $order->update();

        foreach ($order->product as $product) {
            $vendor = Vendor::find($product->vendor_id);
            $data = [];
            $data['recipient_code'] = $vendor->recipient_code;
            $data['reason'] = $product->name . ' was purchased';
            $qty = unserialize($order->cart->cart)['items'][$product->id]['qty'];
            $data['amount'] = $product->price * $qty * 0.85;
            $payout = new Payout();
            $response = $payout->makePayment($data);
            $completed = $response->status;
            if ($completed !== true) {
                return response()->json(['status' => false,
                                         'message' => 'transaction failed'], 502);
            }
        }
        if ($completed === true) {
            $order->status = 'payout completed';
            $order->update();
            return response()->json(['status' => true,
                                     'order' => $order], 200);
        }
    }

    public function destroy($id)
    {
        //
    }
}
