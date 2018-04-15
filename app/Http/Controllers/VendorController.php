<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Vendor;
use App\Models\Admin;
use App\User;
use App\Services\Payout;
use App\Http\Controllers\MailController as Mailer;

class VendorController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except(['index', 'show', 'search']);
        $this->middleware('vendor')->only(['update', 'destroy']);
    }


    public function index()
    {
        $vendor = Vendor::orderBy('created_at', 'desc')
                            ->with(['category', 'product', 'user', 'file'])->get();

        return response()->json(['status' => true,
                                 'vendors' => $vendor], 200);
    }


    public function store(Request $request)
    {
        // $vendor = Vendor::where('id', 4)->with(['category', 'product', 'user', 'file'])->first();

        // return response()->json(['status' => true,
        //                          'message' => 'vendor retrieved',
        //                          'vendor' => $vendor], 200);
        $data = $request->all();

        $validator = $this->validate($request, [
            'brand_name' => 'required|unique:vendors,brand_name',
            'image' => 'nullable|image|max:1999.999',
            'category_id' => 'numeric|exists:categories,id',
            'phone' => 'required|phone',
            'address' => 'required',
            'zip_code' => 'required|zip',
            'acc_no' => 'required|digits:10',
            'bank_code' => 'required|numeric',
        ]);

        $user = Auth::user();

        $slug = str_replace(' ', '-', strtolower($data['brand_name']));

        $vendor = new Vendor();

        $vendor = $this->createTransferRecipient($vendor, $data);

        $vendor->user_id = $user->id;
        $vendor->slug = $slug;
        $vendor->brand_name = $data['brand_name'];
        $vendor->category_id = $data['category_id'];
        $vendor->phone = $data['phone'];
        $vendor->address = $data['address'];
        $vendor->zip_code = $data['zip_code'];
        $vendor->bio = $data['bio'];
        $vendor->acc_no = $data['acc_no'];
        $vendor->bank_code = $data['bank_code'];

        if ($vendor->save()) {
            return response()->json(['status' => true,
                                     'message' => 'your account has been created',
                                     'vendor' => $vendor,
                                     'user' => $user,
                                    ], 200);
        }

    }


    public function createTransferRecipient ($vendor, $data) {

        $payout = new Payout();

        $paystackVendor = $payout->createTransferRecipient($data);

        if ($paystackVendor->status != true || 
            $paystackVendor->message == 'Recipient already exists') {
            return response()->json(['status'=> false,
                                     'message' => $paystackVendor->message, 
                                    ], 200);
        }

        $vendor->recipient_code = $paystackVendor->data->recipient_code;

        return $vendor;
    }


    public function show($slug)
    {
        $vendor = Vendor::where('slug', $slug)->with(['category', 'product', 'user', 'file'])->first();

        return response()->json(['status' => true,
                                 'message' => 'vendor retrieved',
                                 'vendor' => $vendor], 200);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $search = Vendor::where('brand_name', 'like', '%' . $query . '%')
                            ->with(['category', 'product', 'user', 'file'])
                            ->orderBy('created_at', 'desc')->get();

        return response()->json(['result'=> $search], 200);
    }


    public function update(Request $request, $slug)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'image' => 'nullable|image|max:1999.999',
            'phone' => 'nullable|phone',
            'address' => 'nullable',
            // 'zip_code' => 'nullable|zip',
            'acc_no' => 'nullable|digits:10',
            'bank_code' => 'nullable|numeric',
            'display' => 'nullable|string',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $vendor = Vendor::where('slug', $slug)->with(['file'])->first();

        if (!$vendor) {
            return response()->json(['status'=> false,
                                     'message' => 'vendor not found', 
                                    ], 422);
        }

        $user = Auth::user(); 
        $admin = Admin::find(['user_id' => $user->id]);

        if (($user->vendor->id !== $vendor->id) && !$admin) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        if (array_key_exists('brand_name', $data)) {
            if ($data['brand_name']) {
                $slug = str_replace(' ', '-', strtolower($data['brand_name']));
                if (!Vendor::where('slug', $slug)) {
                    $vendor->slug = $slug;   
                }

                $vendor->brand_name = $data['brand_name'];
            }
        }

        if (array_key_exists('phone', $data)) {
            if ($data['phone']) {
                $vendor->phone = $data['phone'];
            }
        }

        if (array_key_exists('address', $data)) {
            if ($data['address']) {
                $vendor->address = $data['address'];
            }
        }

        if (array_key_exists('zip_code', $data)) {
            if ($data['zip_code']) {
                $vendor->zip_code = $data['zip_code'];
            }
        }

        if (array_key_exists('bio', $data)) {
            if ($data['bio']) {
                $vendor->bio = $data['bio'];
            }
        }

        if (array_key_exists('display', $data)) {
            if ($data['display']) {
                if (!$admin) {
                    return response()->json(['status'=> false,
                                    ], 401);
                }
                else {
                    $vendor->display = $data['display'];
                }
            }
        }

        if (array_key_exists('acc_no', $data) && array_key_exists('bank_code', $data)) {
            if ($data['acc_no'] !== $vendor->acc_no) {
                $vendor->acc_no = $data['acc_no'];
                $vendor->bank_code = $data['bank_code'];
                $vendor = $this->createTransferRecipient($vendor);
            }
        }

        if ($vendor->update()) {
            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'vendor' => $vendor], 200);
        }


    }

    public function sendVendorReports () {
        $vendors = Vendor::orderBy('created_at', 'desc')->with(['category', 'product', 'user', 'file'])->get();

        Mailer::sendVendorReports($vendors);

    }

    public function destroy($slug)
    {
        $vendor = Vendor::where(['slug' => $slug])
            ->with(['category', 'product', 'user', 'file'])
            ->first();

        $user = Auth::user(); 

        if (($user->vendor->id !== $vendor->id) && !Admin::find($user->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }
    
        $file = $vendor->file;
        Storage::delete($file->path);
        $file->delete();

        $vendor->delete();

        $success = 'vendor has been deleted';

        return response()->json(['status'=> true, 
                                 'message' => $success,
                                ], 200);
    }
}
