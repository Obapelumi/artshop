<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Auth;
use App\Mail\VendorProductPurchase;
use App\Mail\OrderConfirmed;
use App\Mail\AdminInvite;
use App\Mail\ResetPassword;
use App\Mail\WelcomeToArtshop;
use App\Mail\NewsLetter;
use App\User;
use App\Models\Vendor;
use App\Models\Cart;
use App\Models\Order;
// use App\Models\NewsLetter as NewsLetters;
use App\Models\MailList;

class MailController extends Controller
{
    public function __construct () {
        $this->middleware('auth:api')->only(['orderConfirmation', 'vendorProductPurchased']);
    }

    public function orderConfirmation ($id) {
        $order = Order::where('id', $id)->with(['cart', 'user', 'product'])->first();

        $order->cart->cart = unserialize($order->cart->cart);

        Mail::to(Auth::user()->email)->send(new OrderConfirmed($order));

        return response()->json(['status' => true,
                                 'message' => 'email sent'], 200);
    }

    public function vendorProductPurchased (Request $request) {
        $data = $request->all();
        
        $vendorUserIds = array_keys($data);
        $users = User::find($vendorUserIds);

        foreach ($users as $user) {
            $product = $data[$user->id];
            Mail::to($user->email)->queue(new VendorProductPurchase($product));
        }

        return response()->json(['status' => true,
                                 'message' => 'emails sent'], 200);
    }

    public static function inviteAdmin($user) {
        Mail::to($user['user']->email)->send(new AdminInvite($user));
    }

    public static function resetPassword ($user) {
        Mail::to($user->email)->send(new ResetPassword($user));
    }

    public function sendVerificationCode ($email) {
        $user = User::where('email', $email)->with(['meta'])->first();

        Mail::to($user->email)->send(new WelcomeToArtshop($user));

        return response()->json(['status' => true,
                                 'message' => 'verification code sent'], 200);
    }

    public static function sendNewsLetters ($newsletter) {
        $mailList = MailList::all();
        foreach ($mailList as $mail) {
            Mail::to($mail->email)->send(new NewsLetter($newsletter));
        }
        return 'messages sent';
    }

    public static function sendVendorReports ($vendors) {
        foreach ($vendors as $vendor) {
            Mail::to($vendor->user->email)->send(new VendorReport($vendor));
        }
        return 'mails sent';
    }
}
