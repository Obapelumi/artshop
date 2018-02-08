<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsLetter;
use App\Models\MailList;
use Auth;
use App\Http\Controllers\MailController as Mailer;

class NewsLetterController extends Controller
{
    public function __construct () {
        $this->middleware('admin')->except(['subscribe', 'subscriber']);
    }

    public function index()
    {
        $newsletters = NewsLetter::orderBy('created_at', 'desc')->with(['user'])->get();

        return response()->json(['status' => true,
                                 'newsletters' => $newsletters], 200);
    }

    public function subscribe (Request $request) {
        $data = $request->all();

        $validator = $this->validate($request, [
            'email' => 'required|string|unique:mail_lists',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $data['is_user'] = false;

        $mailList = $this->subscriber($data);
        
        return response()->json(['status' => true,
                                 'message' => 'subscriber created',
                                 'subscriber' => $mailList], 200);
    }

    public function subscriber ($request) {
        $mailList = MailList::find(['email' => $request['email']]);
        if (!$mailList) {
            $mailList = new MailList();

            $mailList->email = $request['email'];
            $mailList->is_user = $request['is_user'];
            $mailList->save();
        }

        return $mailList;
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 200);
        }

        $data['user_id'] = Auth::user()->id;
        $data['slug'] = str_replace(' ', '-', strtolower($data['title']));

        $newsletter = NewsLetter::create($data);

        return response()->json(['status' => true,
                                 'newsletter' => $newsletter], 200);
    }

    public function show($slug)
    {
        $newsletter = NewsLetter::where('slug', $slug)->with(['user'])->first();

        return response()->json(['status' => true,
                                 'newsletter' => $newsletter], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'title' => 'nullable|string',
            'body' => 'nullable|string',
            'sent' => 'nullable|boolean',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 200);
        }

        $newsletter = NewsLetter::find($id);

        if (array_key_exists('title', $data)) {
            if ($data['title']) {
                $newsletter->title = $data['title'];
            }
        }

        if (array_key_exists('body', $data)) {
            if ($data['body']) {
                $newsletter->body = $data['body'];
            }
        }

        if (array_key_exists('sent', $data)) {
            if ($data['sent']) {
                $newsletter->sent = $data['sent'];
            }
        }

        return response()->json(['status' => true,
                                 'newsletter' => $newsletter], 200);        
    }

    public function destroy($id)
    {
        $newsletter = NewsLetter::find($id);
        $newsletter->delete();

        return response()->json(['status' => true,
                                 'message' => 'news letter deleted'], 200);
    }

    public function send() {
        $newsletter = NewsLetter::where('sent', false)->with(['user'])->first();
        $newsSent = Mailer::sendNewsLetters($newsletter);

        return response()->json(['status' => true,
                                 'message' => $newsSent], 200);
    }
}
