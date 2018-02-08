<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'user_id' => 'required|numeric|exists:users,id',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $cart = $data['cart'];
        $cartItems = $cart ['items'];
        $cart['totalPrice'] = 0;

        foreach ($cartItems as $item) {
            $id = $item['item']['id'];

            $product = Product::where('id', $id)->with(['discount'])->first();

            if (!$product) {
                return response()->json(['status'=> false,
                                         'message' => 'product not found', 
                                        ], 422);
            }

            $item['item'] = $product;
            $discountedPrice = $product->price;

            if (count($product->discount) > 0) {
                $discountedPrice = $product->price * (1 - $product->discount[0]->discount);
            }
            
            $cart['totalPrice'] += $discountedPrice * $item['qty'];
        }

        Cart::where('user_id', $data['user_id'])
                ->where('purchased', false)->delete();

        $data['cart'] = serialize($data['cart']);

        $cart = Cart::create($data);

        $cart = Cart::find($cart->id);

        $cart->cart = unserialize($cart->cart);

        return $cart;
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }






















    public function addItem(Request $request, $id)
    {
    	$data = $request->all();
        $product = Product::find($id);

        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->id, $data['quantity']);

        Session::put('cart', $cart);

        return response()->json(['status' => true,
        						 'message' => 'item added to cart',
        						 'cart' => Session::get('cart'), 
    							]);
    }

    public function removeItem(Request $request, $id)
    {
        if(!Session::has('cart')){
            return response()->json(['status' => false,
            						 'message' => 'there are no items in your shopping cart',
        							], 400);
        }

    	$data = $request->all();
    	$product = Product::find($id);

    	$oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $cart->remove($product, $product->id, $data['quantity']);

        Session::put('cart', $cart);

        $message = $data['quantity'] . 'of' . $product->name . 'has been removed from your shopping cart';

        return response()->json(['status' => true,
        						 'message' => $message,
        						 'cart' => Session::get('cart'), 
    							], 200);
    }

    public function cart(Request $request)
    {
        if(!Session::has('cart')){
            return response()->json(['status' => false,
            						 'message' => 'there are no items in your shopping cart',
        							], 400);
        }

        $cart = Session::get('cart');
        $shippingCost = 1500;
        $totalPrice = $cart->totalPrice + $shippingCost;

        return response()->json(['status' => true,
        						 'cart' => $cart,
                                 'shippingCost' => $shippingCost,
                                 'totalPrice' => $totalPrice,
                                ], 200);
    }

}
