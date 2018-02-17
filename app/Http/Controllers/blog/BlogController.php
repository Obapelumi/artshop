<?php

namespace App\Http\Controllers\blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController as Filer;
use App\User;
use App\Models\Blog;
use App\Models\Admin;
use App\Models\BlogTag as Tag;
use DB;
use Auth;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')
            ->except(['index', 'show', 'blogTags', 'search']);

        $this->middleware('blogger')
            ->except(['index', 'show', 'blogTags', 'search']);
    }

    public function index()
    {
        $posts = Blog::with(['user', 'file', 'comment'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json(['status' => true,  
                                 'posts' => $posts], 200);
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
            'seo_key_words' => 'nullable|string',
            'published'=> 'nullable|boolean',
            'tags' => 'nullable|string',
        ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);

        }

        $data = $request->all();
        $user = Auth::user();

        $slug = str_replace(' ', '-', strtolower($data['title']));
        $slug = str_replace('?', '', $slug);

        if ($data['published'] === true) {
            $data['published'] = Carbon::now();
        }
        
        if (Blog::where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        $data['slug'] = $slug;

        $data['user_id'] = $user->id;

        $tags = [];

        foreach (explode(',', $data['tags']) as $tag) {
            if ($tag > '') {
                if (!Tag::find(['name' => $tag])) {
                    $tags[] = $tag;
                    BlogTag::create($tag);
                }
            }
        }

        $blogPost = Blog::create($data);

        $blogPost->blogTag()->sync($tags, false);

        return response()->json(['status' => true,
                                 'message' => 'post created',
                                 'post' => $blogPost], 200);

    }


    public function show($slug)
    {
        $blogPost = Blog::where('slug' , $slug)
                            ->with(['user', 'blogger', 'blogTag', 'file', 'comment'])
                            ->first();

        return response()->json(['status' => true,
                                 'post' => $blogPost], 200);
    }

    public function blogTags () {
        $blogTags = Tag::orderBy('created_at', 'DESC')->get();

        return response()->json(['status' => true,
                                 'tags' => $blogTags,
                                ], 200);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $search = Blog::where('title', 'like', '%' . $query . '%')
                            ->with(['user', 'blogger', 'blogTag', 'file', 'comment'])
                            ->orderBy('created_at', 'desc')->get();

        return response()->json(['result'=> $search], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validate($request, [
            'title' => 'nullable|string',
            'body' => 'nullable|string',
            'seo_key_words' => 'nullable|string',
            'tags' => 'nullable|string',
            ]);

        if ($validator) {
            $errors = $validator->errors();

            return response()->json(['status'=> false,
                                     'message' => $errors, 
                                    ], 422);
        }

        $blogPost = Blog::where('id', $id)->first();

        $user = Auth::user();
        $admin = Admin::find(['user_id' => $user->id]);
        if (($user->id !== $blogPost->user_id) && !$admin) {
            return response()->json(['status'=> false,
                                    ], 401);
        }


        if (array_key_exists('title', $data)) {
            if ($data['title'] != null) {
                $blogPost->title = $data['title'];
            }

            $slug = str_replace(' ', '-', strtolower($data['title']));

            if (Blog::where('slug', $slug)->first()) {
                $slug = $slug . '-' . time();
            }

            $blogPost->slug = $slug;
        }

        if (array_key_exists('body', $data)) {
            if ($data['body'] != null){
                $blogPost->body = $data['body'];
            }
        }

        if (array_key_exists('published', $data)) {
            if ($data['published'] != false){
                $blogPost->published = Carbon::now();
            }
        }

        if ($blogPost->update()) {
            return response()->json(['status' => true,
                                     'message' => 'your post has been updated',
                                     'post' => $blogPost], 200);
        }
    }


    public function destroy($id)
    {
        $blogPost = Blog::where(['id' => $id,])
            ->with(['file', 'comment'])
            ->first();  

        $file = $blogPost->file;

        if ($file) {
            Filer::destroy($file->id);
        }

        // $blogPost->comment->delete();

        // DB::delete('delete blog_tag where blog_id = ?', [$blogPost->id]);
        DB::table('blog_tag')->where('blog_id', $blogPost->id)->delete();

        $blogPost->delete();

        return response()->json(['status'=> true, 
                                 'message' => 'post deleted',
                                ], 200);
    }

    public function destroyBlogTag($id)
    {
        $blogTag = Blog::find($id);  

        DB::delete('delete blog_tag where blog_tag_id = ?', [$blogTag->id]);

        $blogTag->delete();

        return response()->json(['status'=> true, 
                                 'message' => 'blog tag deleted',
                                ], 200);
    }
}
