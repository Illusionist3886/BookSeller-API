<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
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
            $path = 'uploads/books';
            $request->file('image')->move($path, $featured);
        }

        $data = [
            'name' => $request->name,
            'image' => $featured,
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'description' => $request->description,
            'unit_price' => $request->unit_price,
            'offer_price' => $request->offer_price,
            'discount' => $request->discount,
            'promotion_validity' => $request->promotion_validity,
            'created_at'    => Carbon::now()
        ];

        $lastid = DB::table("books")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'book_id' => $lastid,
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
        $books = DB::table('books')->paginate(10);
        return response()->json([
            'status' => 'ok',
            'books' => $books,
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
        DB::table('books')->where(['id'=>$request->book_id])->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Book Deleted!',
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
        $book = DB::table('books')->where(['id'=>$request->book_id])->first();
        return response()->json([
            'status' => 'ok',
            'details' => $book,
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
            $path = 'uploads/books';
            $request->file('image')->move($path, $featured);
        } else {
            $book = DB::table('books')->select(['image'])->where(['id'=>$request->book_id])->first();
            $featured = $book->image;
        }

        $data = [
            'name' => $request->name,
            'image' => $featured,
            'writer' => $request->writer,
            'publisher' => $request->publisher,
            'description' => $request->description,
            'unit_price' => $request->unit_price,
            'offer_price' => $request->offer_price,
            'discount' => $request->discount,
            'promotion_validity' => $request->promotion_validity,
            'updated_at'    => Carbon::now()
        ];

        DB::table("books")->where(['id'=>$request->book_id])->update($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'Book Updated Successfully!',
        ]);

    }
}