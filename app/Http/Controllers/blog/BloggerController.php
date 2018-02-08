<?php

namespace App\Http\Controllers\blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Models\Blogger;
use App\Models\UserMeta;
use App\User;
use App\Models\File;
use Auth;
use App\Http\Controllers\MailController as Mailer;

class BloggerController extends AuthController
{
    public function __construct() {
        // $this->middleware('auth')->except(['index', 'show']);
        // $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $bloggers = Blogger::orderBy('created_at', 'desc')
                         ->with(['blog', 'user', 'file'])
                         ->get();

        return response()->json(['status' => true,
                                 'bloggers' => $bloggers], 200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone' => 'required|phone|unique:bloggers',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $user['user'] = $this->signup($request);

        $slug = str_replace(' ', '-', strtolower($user['user']->name));

        if (Blogger::where('slug', $slug)) {
            $slug = $slug . '-' . time();
        }

        $blogger = new Blogger();

        $blogger->user_id = $user['user']->id;
        $blogger->name = $data['name'];
        $blogger->phone = $data['phone'];
        $blogger->slug = $slug;

        $blogger->save();

        $user['password'] = $data['password'];
        $user['role'] = 'Blogger';

        Mailer::inviteAdmin($user);

        $userMeta = UserMeta::where('user_id', $user['user']->id)->first();
        if ($userMeta) {
            $userMeta->verified = true;
            $userMeta->update();
        }

        return response()->json(['status' => true,
                                 'message' => 'blogger account created',
                                 'blogger' => $blogger], 200);

    }


    public function show($slug)
    {
        $blogger = Blogger::where('slug', $slug)
                     ->with(['blog', 'user', 'file'])
                     ->first();

        $blogger->social_media = unserialize($blogger->social_media);

        return response()->json(['status' => true,
                                 'blogger' => $blogger], 200);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        // $user = Auth::user();

        $this->validate($request, [
            'name' => 'nullable|unique:vendors,brand_name',
            'image' => 'nullable|image|max:1999.999',
            'phone' => 'nullable|phone',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $blogger = Blogger::find($id);

        if (!$blogger) {
            return response()->json(['status'=> false,
                                     'message' => 'blogger not found', 
                                    ], 422);
        }

        $user = Auth::user(); 
        //$user->vendor->id

        if (($user->blogger->id !== $blogger->id) && !Admin::find($user->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        if (array_key_exists('name', $data)) {
            if ($data['name']) {
                $blogger->name = $data['name'];
            }
        }

        if (array_key_exists('phone', $data)) {
            if ($data['phone']) {
                $blogger->phone = $data['phone'];
            }
        }

        if (array_key_exists('social_media', $data)) {
            if ($data['social_media']) {
                $blogger->social_media = serialize($data['social_media']);
            }
        }

        if ($blogger->update()) {
  
                $image = 'image';

                if ($request->hasFile($image)) {
                    $extension = $request->file($image)->extension();

                    $fileNameToStore = $slug . '.' . $extension;

                    Storage::delete($fileNameToStore);

                    $path = $request->file($image)->storeAs('/', $fileNameToStore);

                    $file = File::find('blogger_id', $blogger->id);
                    // $file = $->file;
                    $file->path = $fileNameToStore;
                    $file->update();
                }

            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'blogger' => $blogger], 200);
        }
    }


    public function destroy($id)
    {
        $blogger = Blogger::where('slug', $slug)
                     ->with(['blog', 'user', 'file'])
                     ->first();

        $user = Auth::user(); 
        //$user->vendor->id

        if (($user->blogger->id !== $blogger->id) && !Admin::find($user->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }      

        $file = $blogger->file;
        Storage::delete($file->path);
        $file->delete();

        $blogger->delete();

        $success = 'blogger has been deleted';

        return response()->json(['status'=> true, 
                                 'message' => $success,
                                ], 200);
    }
}
