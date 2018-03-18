<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Auth;
use App\Models\Admin;
use App\Models\UserMeta;
use App\Http\Controllers\MailController as Mailer;

class AdminController extends AuthController
{
    public function __construct() {
        // $this->middleware('auth:api');
        $this->middleware('admin');
    }

    public function index()
    {
        $admins = Admin::orderBy('created_at', 'DESC')->with(['user', 'file'])->get();

        return response()->json(['status' => true,  
                                 'admins' => $admins], 200);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone' => 'required|phone|unique:admins',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        // $data['password'] = substr(md5(rand(1111, 9999)), 0, 10);
        // $data['c-password'] = $data['password'];

        $user['user'] = $this->signup($data);

        $slug = str_replace(' ', '-', strtolower($user['user']->name));

        if (Admin::where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        $admin = new Admin();

        $admin->user_id = $user['user']->id;
        $admin->phone = $data['phone'];
        $admin->slug = $slug;

        $admin->save();

        $user['password'] = $data['password'];
        $user['role'] = 'Administrator';
        
        $userMeta = UserMeta::where('user_id', $user['user']->id)->first();
        if ($userMeta) {
            $userMeta->verified = true;
            $userMeta->update();
        }

        Mailer::inviteAdmin($user);

        return response()->json(['status' => true,
                                 'message' => 'admin account created',
                                 'admin' => $admin], 200);

    }


    public function show($slug)
    {
        Admin::where(['slug' => $slug,])->with(['user', 'file'])->first();

        return response()->json(['status' => true,  
                                 'admin' => $admin], 200);
    }


    public function update(Request $request, $slug)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'image' => 'nullable|image|max:1999.999',
            'phone' => 'required|phone',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $admin = Admin::find(['slug' => $slug]);

        $user = Auth::user();

        if ($user->admin->id !== $admin->id) {
            return response()->json(['status'=> false,
                                    ], 401);
        }
        if (array_key_exists('phone', $data)) {
            if ($data['phone']) {
                $admin->phone = $data['phone'];   
            }
        }

        if ($admin->update()) {

            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'admin' => $admin], 200);
        }
    }

    public function destroy($slug)
    {
        $admin = Admin::where(['slug' => $slug])->with(['user', 'file'])->first();  

        $user = Auth::user();
        
        if ($user->admin->id !== $admin->id) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        $file = $user->admin->file;
        Storage::delete($file->path);
        $file->delete();

        $admin->delete();

        $success = 'customer has been deleted';

        return response()->json(['status'=> true, 
                                 'message' => $success,
                                ], 200);
    }
}
