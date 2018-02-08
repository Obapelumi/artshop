<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Storage;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'product', 'show']);
        $this->middleware('admin')->except(['index', 'product', 'show']);
    }
    
    public function index()
    {
        $category = Category::with(['tag', 'handling', 'vendor','customer', 'file', 'product'])
            ->orderBy('created_at', 'desc')->get();

        return response()->json(['status' => true,  
                                 'category' => $category], 200);
    }


    public function product(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'id' => 'numeric|exists:categories,id',
            'take'=>'numeric',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $product = Product::where('category_id', $data['id'])->with([
                                                           'tag',
                                                           'file',
                                                           'category',
                                                           // 'handling',
                                                           'vendor',
                                                           // 'order',
                                                           // 'review',
                                                          ])->get()
                                                            ->take($data['take']);

         return response()->json(['product' => $product,
                                    ], 200);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'name' => 'required|unique:categories,name',
            'shipping_factor' => 'nullable|numeric',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $slug = str_replace(' ', '-', strtolower($data['name']));

        $category = new Category();

        $category->name = $data['name'];
        $category->slug = $slug;

        if (array_key_exists('shipping_factor', $data)) {
            $category->shipping_factor = $data['shipping_factor'];
        }

        $category->save();

        return response()->json(['status' => true,
                                 'message' => 'category created',
                                 'category' => $category], 200);
    }


    public function show($slug)
    {
        $category = Category::where(['slug'=> $slug])->with(['tag', 
                                                             'handling', 
                                                             'vendor',
                                                             'customer', 
                                                             'file', 
                                                             'product'])->first();

        return response()->json(['status' => true,
                                 'category'=>$category], 200); 
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'name' => 'nullable|unique:categories,name',
            'image' => 'nullable|image|max:1999.999',
            'handling' => 'nullable|numeric', // 'exists:handlings,id',
            'shipping_factor' => 'nullable|numeric',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $category = Category::find($id);

        if (array_key_exists('name', $data)) {
            if ($data['name'] != null) {
                $category->name = $data['name'];
            }

            $slug = str_replace(' ', '-', strtolower($data['name']));
            $category->slug = $slug;
        }

        if (array_key_exists('handling', $data)) {
            if ($data['handling'] != null){
                $category->handling_id = $data['handling'];
            }
        }

        if (array_key_exists('shipping_factor', $data)) {
            if ($data['shipping_factor'] != null){
                $category->shipping_factor = $data['shipping_factor'];
            }
        }

        if ($category->update()) {
  
                $image = 'image';

                if ($request->hasFile($image)) {
                    $extension = $request->file($image)->extension();

                    $fileNameToStore = $slug . '.' . $extension;

                    Storage::delete($fileNameToStore);

                    $path = $request->file($image)->storeAs('/', $fileNameToStore);

                    $file = File::find(['category_id' => $category->id]);
                    $file->path = $fileNameToStore;
                    $file->update();
                }

            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'category' => $category], 200);
        }
    }


    public function destroy($id)
    {
        $category = Category::where(['id' => $id,])
            ->with(['file'])
            ->first();  

        // $file = $category->file;

        // Storage::delete($file->path);
        // $file->delete();

        DB::delete('delete category_tag where category_id = ?', [$category->id]);

        $category->delete();

        return response()->json(['status'=> true, 
                                 'message' => 'category deleted',
                                ], 200);
    }
}
