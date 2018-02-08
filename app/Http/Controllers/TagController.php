<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use DB;

class TagController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $tags = Tag::with(['category', 'product'])
            ->orderBy('created_at', 'desc')->get();

        return response()->json(['status' => true,  
                                 'tags' => $tags], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
                'name' => 'required|unique:tags,name',
                'category_id' => 'nullable|numeric',
            ]);

        $slug = str_replace(' ', '-', strtolower($data['name']));

        $tag = new Tag();

        $tag->name = $data['name'];
        $tag->slug = $slug;

        $tag->save();

        $tag->category()->sync($data['category_id'], false);

        return response()->json(['status'=> false,
                                 'tag' => $tag, 
                                ], 200);

    }


    public function show($slug)
    {
        $tag = Tag::where(['slug'=> $slug])->with(['category', 'product'])->get();

        return response()->json(['status' => true,
                                 'tag'=>$tag], 200); 
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $slug)
    {
        $data = $request->all();

        $this->validate($request, [
                'name' => 'nullable|unique:tags,name',
                'categories' => 'nullable|numeric', //|exists:categories,id
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $tag = Tag::find('slug', $slug);

        if (array_key_exists('name', $data)) {
            if ($data['name'] != null) {
                $tag->name = $data['name'];
            }

            $slug = str_replace(' ', '-', strtolower($data['name']));
            $tag->slug = $slug;
        }

        if ($tag->update()) {

            return response()->json(['status' => true,
                                     'message' => 'your profile has been updated',
                                     'tag' => $tag], 200);
        }
    }


    public function destroy($id)
    {
        $tag = Tag::where('id', $id)->first(); 
        
        DB::table('category_tag')->where('tag_id', $tag->id)->delete();

        $tag->delete();

        return response()->json(['status'=> true, 
                                 'message' => 'tag deleted',
                                ], 200);
    }
}
