<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(){

        $credentials = request(['username', 'password']);

        if(! $token = auth()->attempt($credentials)){
            return response()->json([
               "success"=>false,
               "message" => "Wrong Credentials"
            ]);
        }

        return response()->json([
           "success"=> true,
            "message"=> "Logged in successfully",
            "token"=>$token,
            "user"=> auth()->user()
        ]);

    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success'=> true,
            'message' => 'Successfully logged out'
        ]);
    }
}
