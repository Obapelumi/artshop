<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserMeta;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
use App\Http\Controllers\MailController as Mailer;
// use App\Jobs\SendWelcomeEmail;
use App\Http\Controllers\NewsLetterController as Subscribe;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function signin(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if (DB::table('oauth_access_tokens')->where('user_id', $user->id)->get()) {
                DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            }

            $result = $this->issueAccessToken($user);

            if ($result === false) {
                return response()->json(['error'=>'Unauthorised'], 405);
            }

            return response()->json(['token' => $result['token'], 'user' => $result['user']], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function directSignup(Request $request)
    {
        $user = $this->signup($request);

        return response()->json(['user'=>$user], 200);
    }

    public function signup($request)
    {
        $user = User::where('email', $request['email'])->with(['meta', 'vendor', 'customer', 'admin', 'blogger', 'order', 'review', 'file', 'cart', 'blog'])->first();
        if ($user) {
            return $user;
        }

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // return $user;

        $userMeta = new UserMeta();
        $userMeta->user_id = $user->id;
        $userMeta->verification_code = substr(md5(microtime()),rand(0,26),6);
        $userMeta->save();

        // $result = $this->issueAccessToken($user);

        // Mail::to($result['user']->email)->send(new WelcomeToArtshop($result['user']));

        return $user;
    }

    public function verifyEmail (Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users',
            'verification_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user = User::where('email', $data['email'])->with(['meta'])->first();

        if (!$user) {
            return response()->json(['error'=>'user not found'], 422); 
        }

        if ($user->meta->verification_code === $data['verification_code']) {
            $userMeta = UserMeta::where(['user_id' => $user->id])->first();
            $userMeta->verified = true;
            $userMeta->save(); 

            $newsSubscription = [];
            $newsSubscription['email'] = $user->email;
            $newsSubscription['is_user'] = true;
            $subscribe = new Subscribe();
            $mailList = $subscribe->subscriber($newsSubscription);

            $result = $this->issueAccessToken($user);  

            return response()->json(['token' => $result['token'], 
                                     'user' => $result['user']], 200);
        }
        else {
            return response()->json(['error'=>'incorrect verification code'], 422); 
        }
    }

    public function issueAccessToken ($user) {
        $user = User::where('id', $user->id)->with(['meta', 'vendor', 'customer', 'admin', 'blogger', 'order', 'review', 'file', 'cart', 'blog'])->first();

        if ($user->meta->verified != true) {
            return false;
        }

        $token =  $user->createToken($user->name);
        $expiresAt = Carbon::now()->addDays(7);

        $token->token->expires_at = $expiresAt;

        $result['user'] = $user;

        $result['token'] = $token;
        
        return $result;
    }

    public function signout()
    {
        $success = 'logged out';

        DB::table('oauth_access_tokens')->where('user_id', Auth::user()->id)->delete();

        return response()->json(['success'=>$success], 200);
    }

    public function updateUser (Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'nullable|string',
            'phone' => 'nullable|string|phone',
            'address' => 'nullable|string',
            'password' => 'nullable|string',
            'password_confirmation' => 'nullable|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user = Auth::user();

        if (array_key_exists('name', $data)) {
            if ($data['name']) {
                $user->name = $data['name'];
            }
        }

        if (array_key_exists('password', $data)) {
            if ($data['password']) {
                $user->password = bcrypt($data['password']);
            }
        }

        $user->update();

        $user = User::where('id', $user->id)->with(['meta', 'vendor', 'customer', 'admin', 'blogger', 'order', 'review', 'file', 'cart', 'blog'])->first();

        return response()->json(['success'=>'Customer Updated',
                                 'user' => $user], 200);
    }

    public function resetPassword (Request $request) {
        $data = $request->all();
        $validator = $this->validate($request, [
            // 'email' => 'required|email|exists:users',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }
        $user = User::find(['email' => $data['email']]);

        if (!$user) {
            return response()->json(['error'=>'User not found'], 422);
        }

        function getRandomBytes($nbBytes = 32)
        {
            $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
            if (false !== $bytes && true === $strong) {
                return $bytes;
            }
            else {
                throw new \Exception("Unable to generate secure token from OpenSSL.");
            }
        }
        function generatePassword($length){
            return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length+1))),0,$length);
        }

        $password = generatePassword(8);
        $user->password = bcrypt($password);
        $user->update();

        $user->password = $password;

        if (Mailer::resetPassword($user)) {
            return response()->json(['success'=> 'password reset done'], 200);
        }
    }

    public function getDetails($id)
    {
        $user = User::where('id', $id)
            ->with(['customer', 'vendor', 'admin'])
            ->first();
        return response()->json(['user' => $user], $this->successStatus);
    }
}