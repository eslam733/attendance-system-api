<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function register(Request $req)
    {
        $req->validate([
            'name' => 'required|max:100,min:1',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $data = $req->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (empty($user)) {
            return $this->errorResponse(errors: null, code: 422 , message: 'user not created');
        }

        return $this->successResponse(data: $user, message: 'user created successfully');
    }

    function login(Request $req)
    {
        $req->validate([
            'identifier' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !is_numeric($value)) {
                        $fail('The ' . $attribute . ' must be a valid email or ID.');
                    }
                },
            ],
            'password' => 'required|min:6',
        ]);

        $data = $req->all();

        $user = User::where(function ($query) use ($data) {
            $query->where('email', $data['identifier'])->orWhere('id', $data['identifier']);
        })->first();

        if (empty($user) || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse(errors: null, code: 401 , message: 'Email or password incorrect');
        }

        $user->token = $user->createToken($user->email)->plainTextToken;

        return $this->successResponse(data: $user, message: 'login successfully');
    }
}
