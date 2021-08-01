<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function add(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }

        if($request->hasFile('image'))
        {
            $featured = $request->file('image')->getClientOriginalName();
            $featured = Carbon::now()->format('Y-M-D h-i-s ').$featured;
            $path = 'uploads/blogs';
            $request->file('image')->move($path, $featured);
        }

        $data = [
            'user'  =>  $request->user()->id,
            'title' => $request->title,
            'image' => $featured,
            'description' => $request->description,
            'created_at'    => Carbon::now()
        ];

        $lastid = DB::table("blogs")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'blog_id' => $lastid,
        ]);

    }

    public function list(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        $blogs = DB::table('blogs')->paginate(10);
        return response()->json([
            'status' => 'ok',
            'blogs' => $blogs,
        ]);
    }

    public function delete(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        DB::table('blogs')->where(['id'=>$request->blog_id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Blog Post Deleted!',
        ]);
    }

    public function details(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        $blog = DB::table('blogs')->where(['id'=>$request->blog_id])->first();
        return response()->json([
            'status' => 'ok',
            'details' => $blog,
        ]);

    }

    public function update(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }

        if($request->hasFile('image'))
        {
            $featured = $request->file('image')->getClientOriginalName();
            $featured = Carbon::now()->format('Y-M-D h-i-s ').$featured;
            $path = 'uploads/blogs';
            $request->file('image')->move($path, $featured);
        } else {
            $blog = DB::table('blogs')->select(['image'])->where(['id'=>$request->blog_id])->first();
            $featured = $blog->image;
        }

        $data = [
            'user'  =>  $request->user()->id,
            'title' => $request->title,
            'image' => $featured,
            'description' => $request->description,
            'updated_at'    => Carbon::now()
        ];

        DB::table("blogs")->where(['id'=>$request->blog_id])->update($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'Post Updated Successfully!',
        ]);

    }
}