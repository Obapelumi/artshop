<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use DB;

class DiscountController extends Controller
{
    public function __construct () {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->with(['product', 'category'])->get();

        return response()->json(['status' => true,
                                 'discounts' => $discounts,
                                ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'product_id' => 'nullable|numeric|exists:products,id',
                'category_id' => 'nullable|numeric|exists:categories,id',
                'discount' => 'required|numeric|max:1',
                'period' => 'required|date',
                'coupon' => 'nullable|boolean',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $discount = new Discount();

        $discount->name = $data['discount'] * 100  . '% discount';
        $discount->discount = $data['discount'];
        $discount->period = $data['period'];

        if (array_key_exists('coupon', $data)) {
            $discount->coupon = $data['coupon'];
        }

        if ($discount->save()) {
            if (array_key_exists('product_id', $data)) {
                if ($data['product_id']) {
                    $products = [];
                    $products[] = $data['product_id'];
                    $discount->product()->sync($products, false);
                }
            }
            
            if (array_key_exists('category_id', $data)) {
                if ($data['category_id']) {
                    $categories = [];
                    $categories[] = $data['category_id'];
                    $discount->category()->sync($categories, false);
                }
            }

            if (array_key_exists('customers', $data)) {
                if ($data['customers']) {
                    $discount->customer()->sync($data['customers'], false);
                }
            }

            return response()->json(['status'=> true,
                                     'message' => 'discount created',
                                     'discount' => $discount, 
                                    ], 200);
        }
        else {
            return 'true';
        }

    }


    public function show($id)
    {
        $discount = Discount::find($id);

        return response()->json(['status' => true,
                                 'discount'=>$discount], 200); 
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'discount' => 'numeric|max:1',
                'coupon' => 'nullable|boolean',
                'period' => 'nullable|date',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $discount = Discount::find($id);

        if (array_key_exists('name', $data)) {
            if ($data['name']) {
                $discount->name = $data['name'];
            }    
        }

        if (array_key_exists('discount', $data)) {
            if ($data['discount']) {
                $discount->discount = $data['discount'];
            }
        }
        

        if (array_key_exists('coupon', $data)) {
            if ($data['coupon']) {
                $discount->coupon = $data['coupon'];
            }
        }

        if (array_key_exists('coupon', $data)) {
            if ($data['period']) {
                $discount->period = $data['period'];
            }
        }

        if ($discount->update()) {

            if (array_key_exists('products', $data)) {
                if ($data['products']) {
                    $discount->product()->sync($data['products'], false);
                }
            }

            if (array_key_exists('categories', $data)) {
                if ($data['categories']) {
                    $discount->category()->sync($data['categories'], false);
                }
            }

            if (array_key_exists('customers', $data)) {
                if ($data['customers']) {
                    $discount->customer()->sync($data['customers'], false);
                }
            }

            return response()->json(['status'=> true,
                                     'message' => 'discount updated',
                                     'discount' => $discount, 
                                    ], 200);
        }
    }

    public function removeProduct ($id) {
        DB::table('discount_product')->where('product_id', $id)->delete();

        return response()->json(['status'=> true,
                                 'message' => 'product removed from this discount',
                                ], 200);
    }

    public function destroy($id)
    {
        $discount = Discount::find($id);
        DB::table('discount_product')->where('discount_id', $id)->delete();
        DB::table('category_discount')->where('discount_id', $id)->delete();

        $discount->delete();

        return response()->json(['status'=> true,
                                 'message' => 'discount deleted',
                                ], 200);
    }

}
