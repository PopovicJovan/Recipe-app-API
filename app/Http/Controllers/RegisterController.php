<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function store(Request $request, RegistrationRequest $registrationRequest)
    {
        $registrationRequest->validated();

        $user = User::where('email', $request->input('email'))->first();

        if ($user) return response()->json([
            "message" => "You already have account!"
        ], 400);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->photo = $request->file('photo')->getClientOriginalName();
        $user->password = Hash::make($request->input('password'));
        $request->file('photo')->storeAs('public/images/', $user->photo);


        if ($user->save()) return response([
            "message" => "Created!"
        ], 200);

        return response([
            "message" => "error"
        ], 400);

    }
}
