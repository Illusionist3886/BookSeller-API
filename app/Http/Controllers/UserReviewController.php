<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserReviewController extends Controller
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


        $data = [
            'book' => $request->book_id,
            'user' => $request->user()->id,
            'review' => $request->review,
            'stars' => $request->stars,
            'created_at'    => Carbon::now()
        ];

        $lastid = DB::table("reviews")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'review_id' => $lastid,
        ]);

    }

    public function list(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='user')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        $reviews = DB::table('reviews')->paginate(10);
        return response()->json([
            'status' => 'ok',
            'reviews' => $reviews,
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
        DB::table('reviews')->where(['id'=>$request->review_id,'user'=>$request->user()->id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Review Deleted!',
        ]);
    }

    public function details(Request $request)
    {
        $role = $request->user()->role;
        if($role!=='user')
        {
            return response()->json([
                'status' => 'error',
                'message'   => 'You cannot perform this action!'
            ], 403);
        }
        $review = DB::table('reviews')->where(['id'=>$request->review_id])->first();
        return response()->json([
            'status' => 'ok',
            'details' => $review,
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
            'review' => $request->review,
            'stars' => $request->stars,
            'updated_at'    => Carbon::now()
        ];

        DB::table("reviews")->where(['id'=>$request->review_id,'user'=>$request->user()->id])->update($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'Review Updated Successfully!',
        ]);

    }
}