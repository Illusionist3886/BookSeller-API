<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller
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

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'created_at'    => Carbon::now()
        ];

        $lastid = DB::table("publications")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'publication_id' => $lastid,
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
        $publications = DB::table('publications')->paginate(10);
        return response()->json([
            'status' => 'ok',
            'publications' => $publications,
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
        DB::table('publications')->where(['id'=>$request->publication_id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Publication Deleted!',
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

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at'    => Carbon::now()
        ];

        DB::table("publications")->where(['id'=>$request->publication_id])->update($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'Publication Updated Successfully!',
        ]);

    }
}