<?php

namespace App\Http\Controllers\blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Auth;
use App\User;

class CommentController extends Controller
{
    public function __construct () {
        $this->middleware('auth:api')->except(['show']);
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'blog_id' => 'required|numeric|exists:blogs,id',
            'comment' => 'string',
            'reply' => 'numeric'
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['user_name'] = Auth::user()->name;
        $comment = Comment::create($data);

        return response()->json(['status' => true,
                                 'message' => 'comment submitted',
                                 'comment' => $comment], 200);
    }


    public function show($id)
    {
        $comments = Comment::where('blog_id', $id)->orderBy('created_at', 'DESC')->with(['user', 'blog'])->get();

        return response()->json(['status' => true,
                                 'comments' => $comments], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id)->delete();

        if ((Auth::user()->id !== $comment->user_id) && !Admin::find(Auth::user()->id)) {
            return response()->json(['status'=> false,
                                    ], 401);
        }

        return response()->json(['status' => true,
                                 'message' => 'comment deleted'], 200);
    }
}
