<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if(!$user) return response()->json([
            "message" => "User does not exist!"
        ], 404);

        if (!Hash::check($request->input('password'), $user->password)){
            return response()->json([
                "message" => "Wrong credentials!"
            ], 400);
        }

        $credentals = request(['email', 'password']);
        $token = JWTAuth::attempt($credentals);

        return response([
            "token" => $token
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        JWTAuth::setToken($token)->invalidate();
        return response([
            "message" => "Logged out!"
        ]);

    }

}
