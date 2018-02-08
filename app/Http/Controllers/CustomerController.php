<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Auth;
use App\Models\Customer;
use App\User;
use App\Models\Admin;

class CustomerController extends AuthController
{
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('admin')->only(['index']);
        $this->middleware('customer')->except(['store', 'index']);
    }

    public function index()
    {
        $customer = Customer::orderBy('created_at', 'desc')->with(['user', 'category', 'file', 'order', 'discount'])->get();

        return response()->json(['status' => true,
                                 'customers' => $customer], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'phone' => 'required|phone',
            'address' => 'required',
            'zip_code' => 'required|zip',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);

        }

        $user = Auth::user();

        $customer = new Customer();

        $customer->user_id = $user->id;
        $customer->phone = $data['phone'];
        $customer->address = $data['address'];
        $customer->zip_code = $data['zip_code'];

        if ($customer->save()) {
            
            return response()->json(['status' => true,
                                     'message' => 'your account has been completed',
                                     'user' => $user,
                                     'customer' => $customer], 200);
        }

    }


    public function show($slug)
    {
        $customer = Customer::where(['slug' => $slug])->with(['user'])->first();

        return response()->json(['status' => true,
                                 'message' => 'your profile',
                                 'customer' => $customer], 200);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $data = $request->all();

        $validator = $this->validate($request, [
            'category' => 'nullable|numeric|exists:categories,id',
            'phone' => 'nullable|phone',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|zip',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);

        }

        $customer = Customer::where(['id' => $id])->with(['user'])->first();

        $user = Auth::user();
        
        if ($user->customer->id !== $customer->id && !Admin::find($user->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        if (array_key_exists('phone', $data)) {
            if ($data['phone']) {
                $customer->phone = $data['phone'];
            }
        }

        if (array_key_exists('address', $data)) {
            if ($data['address']) {
                $customer->address = $data['address'];
            }
        }

        if (array_key_exists('zip_code', $data)) {
            if ($data['zip_code']) {
                $customer->zip_code = $data['zip_code'];
            }
        }

        if ($customer->update()) {
            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'customer' => $customer], 200);
        }
    }


    public function destroy($slug)
    {
        $customer = Customer::where(['slug' => $slug])->with(['file'])->first();

        $user = Auth::user();
        
        if ($user->customer->id !== $customer->id && !Admin::find($user->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        $file = $customer->file;
        Storage::delete($file->path);
        $file->delete();

        DB::delete('delete category_customer where customer_id = ?', [$customer->id]);

        $customer->delete();

        $success = 'customer has been deleted';

        return response()->json(['status'=> true, 
                                 'message' => $success,
                                ]);

    }
}
