<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if ($user) return response()->json([
            "message" => "You already have account!"
        ], 400);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));


        if ($user->save()) return response([
            "message" => "Created!"
        ], 200);

        return response([
            "message" => "error"
        ], 400);

    }
}
