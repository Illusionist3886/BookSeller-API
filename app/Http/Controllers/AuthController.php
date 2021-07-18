<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

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
            'phone' => $request->phone?:NULL,
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
            $token = Str::random(30);
            DB::table('users')->where(['email'=>$request->email])->update(['api_token'=>$token]);
            return response()->json([
                'status' => 'ok',
                'api_token' => $token
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'Invalid Credentials'
            ]);
        }

    }

    public function forgot(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $data = DB::table('users')->select(['id','email'])->where(['email'=>$request->email])->first();
        
        if(!empty($data->email))
        {
            $token = rand(123456,99999999);

            DB::table('users')->where(['email'=>$request->email])->update(['reset_token'=>$token]);
            Mail::to($request->email)
            ->send(new ForgotPassword($token));
            
            return response()->json([
                'status' => 'ok',
                'message' => 'Reset E-mail sent.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'Invalid Credentials'
            ]);
        }

    }

    public function verify_token(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);
        $data = DB::table('users')->select(['id','email'])->where(['reset_token'=>$request->code])->first();

        if(!empty($data->email))
        {
            return response()->json([
                'status' => 'ok'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'Invalid Reset Code!'
            ]);
        }
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
            'code' => 'required'
        ]);

        $token = Str::random(32);
        $data = [
            'password' => Crypt::encrypt($request->password),
            'api_token' => $token,
            'reset_token'   => NULL
        ];
        DB::table("users")->where(['reset_token'=>$request->code])->update($data);
        return response()->json([
            'status' => 'ok',
            'api_token' => $token
        ]);
    }

    public function test(Request $request)
    {
        return $request->user()->role;
    }


}
