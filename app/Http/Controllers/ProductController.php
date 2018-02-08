<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductMeta;
use App\User;
use App\Models\File;
use App\Models\Admin;
use Auth;
use Storage;
use Carbon\Carbon;
use DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
        $this->middleware('vendor')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $data = $request->all();

        $display['pending'] = 0;
        $display['rejected'] = 1;
        $display['archived'] = 2;
        $display['approved'] = 3;
        $display['featured'] = 4;
        $display['on-sale'] = 5;

        $products = Product::where('display', '>=', $display[$data['display']])
                    ->orderBy('created_at', 'desc')
                    ->with(['tag', 'discount', 'vendor', 'order', 'file', 'category', 'review', 'meta'])
                    // ->with(['discount', 'file', 'review',])
                    ->get();

        if (array_key_exists('take', $data)) {
            $products = $products->take($data['take']);
        }

        foreach ($products as $product) {
            if ($product->meta != null) {
                if ($product->meta->size_type == 'varying') {
                    $product->meta->sizes = unserialize($product->meta->sizes);
                }
            }
            // if (count($product->discount) > 0) {
            //     $product->discount = [$product->discount[0]];
            // }
            // if (count($product->file) > 0) {
            //     $product->file = [$product->file[0]];
            // }
        }

        return response()->json(['product'=>$products], 200);         
    }


    public function search(Request $request)
    {
        $query = $request->input('search');

        $search = Product::where('name', 'like', '%' . $query . '%')
                            ->where('display', '>=', 3)
                            ->with(['tag', 'discount', 'vendor', 'order', 'file', 'category', 'review', 'meta'])
                            ->orderBy('updated_at', 'desc')->get();

        return response()->json(['result'=> $search], 200);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            // 'user_id' => 'required|numeric|exists:vendors,user_id',
            'category_id' => 'required|numeric|exists:categories,id',
            // 'handling' => 'required|numeric', // |exists:handlings,id
            // 'tags' => 'nullable|numeric|exists:tags,id',
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric',
            'length' => 'nullable|numeric',
            'breadth' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);

        }

        $user = User::where('id', Auth::user()->id)
                        ->with(['vendor'])->first();

        $slug = str_replace(' ', '-', strtolower($data['name']));
        $slug = $slug . '-by-' . str_replace(' ', '-', strtolower($user->vendor->brand_name));
        
        if (Product::where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        $data['slug'] = $slug;
        $data['vendor_id'] = $user->vendor->id;


        if ($product = Product::create($data)) {
            $data['product_id'] = $product->id;
            if (array_key_exists('sizes', $data)) {
                $data['sizes'] = serialize($data['sizes']);
            }
            $productMeta = ProductMeta::create($data);

            if (array_key_exists('tags', $data)) {
                $product->tag()->sync($data['tags'], false);
            }

            return response()->json(['status' => true,
                                     'message' => 'product created',
                                     'product'=>$product, 
                                     'tags' => $product->tag,
                                     'vendor' => $product->vendor,
                                     'image' => $product->file, 
                                    ], 200);
        }

    }


    public function show($slug)
    {
        $product = Product::where(['slug'=> $slug])->with(['tag',
                                                           'file',
                                                           'category',
                                                           'handling',
                                                           'vendor',
                                                           'order',
                                                           'review',
                                                           'meta'
                                                          ])->first();

        if ($product->meta->size_type === 'varying') {
            $product->meta->sizes = unserialize($product->meta->sizes);
        }
        return response()->json(['status' => true,
                                 'product'=>$product], 200);    
    }


    public function update(Request $request, $slug)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'vendor_id' => 'nullable|numeric|exists:vendors,id',
            'user_id' => 'nullable|numeric|exists:users,id',
            'category_id' => 'nullable|numeric|exists:categories,id',
            // 'handling_id' => 'nullable|numeric|exists:handlings,id',
            'tags' => 'nullable|numeric|exists:tags,id',
            'price' => 'nullable|numeric:min=0',
            'stock' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'breadth' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'display' => 'nullable|numeric:max=6',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $product = Product::where(['slug'=> $slug])->with(['tag',
                                                           'meta',
                                                           'file',
                                                           'vendor',
                                                           'order',
                                                          ])->first();

        if (!$product) {
            return response()->json(['status'=> false,
                                     'message' => 'product not found', 
                                    ], 422);
        }

        $productMeta = ProductMeta::where(['product_id' => $product->id])->first();

        $user = Auth::user();
        $admin = Admin::find(['user_id' => $user->id]);

        if (($user->vendor->id !== $product->vendor_id) && !$admin) {
            return response()->json(['status'=> false,
                                     'message' => 'you are not allowed to edit this product',
                                    ], 401);
        }

        if (array_key_exists('brief_detail', $data)) {
            if ($data['brief_detail'] != null) {
                $product->brief_detail = $data['brief_detail'];
            }
        }

        if (array_key_exists('full_detail', $data)) {
            if ($data['full_detail'] != null) {
                $product->full_detail = $data['full_detail'];
            }
        }

        if (array_key_exists('stock', $data)) {
            if ($data['stock'] != null) {
                $product->stock = $data['stock'];
                $productMeta->stock_updated_at = Carbon::now();
            }
        }    

        if (array_key_exists('display', $data)) {
            if (!$admin) {
                return response()->json(['status'=> false,
                                         'message' => 'you are not allowed to change how this product is displayed',
                                        ], 401);
            }
            if ($data['display'] != null) {
                $product->display = $data['display'];
            }
        }  

        if ($product->display < 3) {

            if (array_key_exists('category_id', $data)) {
                if ($data['category_id'] != null) {
                    $product->category_id = $data['category_id'];
                }
            }

            if (array_key_exists('price', $data)) {
                if ($data['price'] != null) {
                    $product->price = $data['price'];
                }
            }

            if (array_key_exists('sizes', $data)) {
                if ($data['sizes'] != null) {
                    $productMeta->sizes = serialize($data['sizes']);
                }
            }

            if (array_key_exists('length', $data)) {
                $productMeta->length = $data['length'];
            }

            if (array_key_exists('breadth', $data)) {
                $productMeta->breadth = $data['breadth'];
            }

            if (array_key_exists('height', $data)) {
                $productMeta->height = $data['height'];
            }

            if (array_key_exists('weight', $data)) {
                $productMeta->weight = $data['weight'];
            }

            if (array_key_exists('handling_id', $data)) {
                if ($data['handling_id'] != null) {
                    $product->handling_id = $data['handling_id'];
                }
            }
        
        } 
        elseif (array_key_exists('length', $data)
            || array_key_exists('breadth', $data)
            || array_key_exists('height', $data)
            || array_key_exists('weight', $data)
            || array_key_exists('category_id', $data)
            || array_key_exists('handling_id', $data) 
            || array_key_exists('price', $data)
            || array_key_exists('sizes', $data)
            && !$admin)  
        {
            $message = 'you cannot change these attributes because this product is on sale';

            return response()->json(['status' => false,
                                     'message' => $message,
                                    ], 422);
        } 

        if ($product->meta) {
            $productMeta->save();
        }
 
        if ($product->update()) {
            return response()->json(['product'=>$product, 
                                     'tags' => $product->tag,
                                     'vendor' => $product->vendor,
                                     'image' => $product->file, 
                                    ], 200);
        }

    }


    public function destroy($slug)
    {
        $product = Product::where(['slug' => $slug])->with(['tag',
                                                 'vendor', 
                                                 'file'])->first();

        foreach ($product->file as $file) {
            Storage::delete($file->path);
            $file->delete();
        }

        DB::delete('delete product_tag where product_id = ?', [$product->id]);

        $product->delete();

        $success = 'This product has been deleted';

        return response()->json(['status'=> true, 
                                 'message' => $success,
                                ]);

    }
}
