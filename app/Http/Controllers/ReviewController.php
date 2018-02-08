<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\User;
use DB;

class ReviewController extends Controller
{
    public function __construct () {
        $this->middleware('admin')->only(['destroy']);
    }

    public function index()
    {
        $reviews = Review::orderBy('created_at', 'desc')
                            ->with(['product', 'user'])->get();

        return response()->json(['status' => true,
                                 'reviews' => $reviews], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'email' => 'nullable|email',
                'name' => 'nullable|string',
                'product_id' => 'numeric|exists:products,id',
                'review' => 'string',
                'vote' => 'nullable|numeric|max:5',
                'reply' => 'nullable|numeric|exists:reviews,id',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $user = User::where('email', $data['email'])->first();

        $review = new Review();

        if ($user) {
            $review->product_id = $data['product_id'];
            $review->user_id = $user->id;
            $review->name = $user->name;
            $review->review = $data['review'];

            if (array_key_exists('vote', $data)) {
                $review->vote = $data['vote'];
            }

            if (array_key_exists('vote', $data)) {
                $review->reply = $data['vote'];
            }

            $review->save();

            return response()->json(['status'=> true,
                                     'message' => 'Review Submitted',
                                     'review' => $review, 
                                    ], 200);

        } 
        // elseif (array_key_exists('vote', $data)) {
        //     $review->vote = $data['vote'];

        //     $review->save();

        //     $message = "Your vote was submitted but you need to be registered to post a review";

        //     return response()->json(['status'=> true,
        //                              'message' => $message, 
        //                             ], 200);
        // } 
        else {
            $message = 'You need to be a registered user to post a review';

            return response()->json(['status'=> false,
                                     'message' => $message, 
                                    ], 405);
        }
    }


    public function show($id)
    {
        $review = Review::where('product_id', $id)->with('user')->get();

        return response()->json(['status' => true,
                                 'reviews' => $review, 
                                ], 200);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'product_id' => 'numeric|exists:products,id',
                'review' => 'string',
                'vote' => 'nullable|numeric|max:5',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }


        $review = Review::where('id', $id)
                        ->with(['user'])
                        ->first();

        $user = Auth::user();

        if ($user->id !== $review->user->id) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        if (array_key_exists('review', $data)) {
            if ($data['review']) {
                $order->review = $data['review'];
            }
        }

        if (array_key_exists('vote', $data)) {
            if ($data['vote']) {
                $order->vote = $data['vote'];
            }
        }

        $review->update();

        return response()->json(['status'=> true,
                                 'review' => $review, 
                                ], 200);

    }


    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['status'=> false,
                                     'message' => 'review not found', 
                                    ], 422);
        }

        $vendorId = Vendor::find($request->input('vendor_id'));

        $user = Auth::user();

        if ($user->id !== $review->user->id) {
            if (($vendorId !== $review->product->vendor_id) && !Admin::find($id)) {
                return response()->json(['status'=> false,
                                        ], 401);
            }
        }

        $review->delete();
            
        return response()->json(['status'=> true,
                                 'message' => 'review deleted', 
                                ], 200);

    }
}
