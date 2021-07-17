<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $token = Str::random(32);
        $data = [
            'role'  => 'user',
            'email' => $request->email,
            'password' => Crypt::encrypt($request->password),
            'name'  => $request->name,
            'api_token' => $token
        ];
        $lastid = DB::table("users")->insertGetId($data);
        return response()->json([
            'status' => 'ok',
            'api_token' => $token,
            'userid'    => $lastid
        ]);

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $data = DB::table('users')->select(['email','password'])->where(['email'=>$request->email])->first();
        if(Crypt::decrypt($data->password) == $request->password)
        {
            return response()->json([
                'status' => 'ok',
                'api_token' => Str::random(30)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'Invalid Credentials'
            ]);
        }

        

    }
}
