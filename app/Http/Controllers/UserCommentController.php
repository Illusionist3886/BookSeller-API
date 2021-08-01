<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserCommentController extends Controller
{
    public function add(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='user')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }

        $blog = DB::table('blogs')->where(['id'=>$request->blog_id])->first();

        if(empty($blog))
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'No Blog Post Found!'
            ], 403);
        }
        $data = [
            'user' => $request->user()->id,
            'blog' => $request->blog_id,
            'comment' => $request->comment,
            'created_at'    => Carbon::now()
        ];



        $lastid = DB::table("comments")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'comment_id' => $lastid,
        ]);

    }


    public function delete(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='user')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        DB::table('comments')->where(['id'=>$request->comment_id,'user'=>$request->user()->id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Comment Deleted!',
        ]);
    }

    public function update(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='user')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
 

        $data = [
            'comment' => $request->comment,
            'updated_at'    => Carbon::now()
        ];

        DB::table("comments")->where(['id'=>$request->comment_id,'user'=>$request->user()->id])->update($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'Comment Updated Successfully!',
        ]);

    }
}