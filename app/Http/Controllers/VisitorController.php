<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function books(Request $request)
    {
        
        $lastid = DB::select("SELECT books.name,books.image,books.writer,books.description,books.unit_price,books.offer_price,books.discount,books.promotion_validity,publications.name FROM books LEFT JOIN publications ON books.publisher=publications.id");
        return response()->json([
            'status' => 'ok',
            'books' => $lastid,
        ]);

    }

    public function book_details(Request $request)
    {
        $details = DB::select("SELECT books.name,books.image,books.writer,books.description,books.unit_price,books.offer_price,books.discount,books.promotion_validity,publications.name FROM books LEFT JOIN publications ON books.publisher=publications.id WHERE books.id=$request->book_id");
        $reviews = DB::select("SELECT reviews.review, users.name as username FROM reviews LEFT JOIN users ON reviews.user=users.id WHERE reviews.book=$request->book_id");
        return response()->json([
            'status' => 'ok',
            'details' => $details,
            'reviews' => $reviews
        ]);
    }

    public function blogs(Request $request)
    {
        
        $lastid = DB::select("SELECT blogs.id,blogs.title,blogs.image,blogs.description,blogs.created_at FROM blogs ORDER BY blogs.id desc");
        return response()->json([
            'status' => 'ok',
            'books' => $lastid,
        ]);

    }

    public function blog_details(Request $request)
    {
        
        $details = DB::select("SELECT blogs.id,blogs.title,blogs.image,blogs.description,blogs.created_at FROM blogs WHERE id = $request->blog_id");

        $comments = DB::select("SELECT comments.comment FROM comments LEFT JOIN users ON comments.user=users.id WHERE comments.blog=$request->blog_id");

        return response()->json([
            'status' => 'ok',
            'details' => $details,
            'comments' => $comments,
        ]);

    }
}