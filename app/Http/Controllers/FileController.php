<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class FileController extends Controller
{
    public function __construct () {
        $this->middleware('auth:api')->except(['show']);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'image' => 'required|string',
                'slug' => 'required|string',
                'category_id' => 'nullable|numeric|exists:categories,id',
                'customer_id' => 'nullable|numeric|exists:customers,id',
                'vendor_id' => 'nullable|numeric|exists:vendors,id',
                'blogger_id' => 'nullable|numeric|exists:bloggers,id',
                'blog_id' => 'nullable|numeric|exists:blogs,id',
                'admin_id' => 'nullable|numeric|exists:admins,id',
                'product_id' => 'nullable|numeric|exists:products,id',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $explode = explode(',', $request->image);
        $ext = explode('/', $explode[0]);
        $ext = explode(';', $ext[1]);
        $ext = $ext[0];

        $fileName =  $request->slug.'.'.$ext;

        $image = Image::make($request->get('image'))->save(storage_path('app/public/images/'.$fileName));

        $data['path'] = $fileName;
        $user = Auth::user();
        $data['user_id'] = $user->id;

        $file = File::create($data);

        return response()->json(['status' => true, 
                                 'message' => 'file  created',
                                 'file' => $file], 200);
    }

    public function show($fileName)
    {
        return Image::make(storage_path('app/public/images/'.$fileName))->response();
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'image' => 'required|string',
                'category_id' => 'nullable|numeric|exists:categories,id',
                'customer_id' => 'nullable|numeric|exists:customers,id',
                'vendor_id' => 'nullable|numeric|exists:vendors,id',
                'blogger_id' => 'nullable|numeric|exists:bloggers,id',
                'blog_id' => 'nullable|numeric|exists:blogs,id',
                'admin_id' => 'nullable|numeric|exists:admins,id',
                'product_id' => 'nullable|numeric|exists:products,id',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        } 

        $file = File::where('id', $id)->with(['user'])->first();
        $user = Auth::user();
        // if ($user->id !== $file->user_id) {
        //     return response()->json(['status'=> false,
        //                              'message' => 'You are not allowed to update this file', 
        //                             ], 401);
        // }
        Image::make(storage_path('app/public/images/'.$file->path))->destroy();

        $explode = explode(',', $request->image);
        $ext = explode('/', $explode[0]);
        $ext = explode(';', $ext[1]);
        $ext = $ext[0];
        $fileName =  $request->slug.'.'.$ext;

        $image = Image::make($request->get('image'))->save(storage_path('app/public/images/'.$fileName));

        $file->path = $fileName;
        $file->update();

        return response()->json(['status' => true, 
                                 'message' => 'file  updated',
                                 'file' => $file], 200);
    }

    public function destroy($id)
    {
        File::find($id);
        Image::make(storage_path('app/public/images/'.$file->path))->destroy();
        $file->delete();
        return response()->json(['status' => true, 'message' => 'file  deleted'], 200);
    }
}
