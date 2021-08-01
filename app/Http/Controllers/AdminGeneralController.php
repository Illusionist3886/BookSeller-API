<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminGeneralController extends Controller
{
    
    public function delete_comment(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        DB::table('comments')->where(['id'=>$request->comment_id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Comment Deleted!',
        ]);
    }

    public function delete_review(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='admin')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        DB::table('reviews')->where(['id'=>$request->review_id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Review Deleted!',
        ]);
    }
}