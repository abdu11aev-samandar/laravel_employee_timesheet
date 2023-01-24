<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //validation

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        //create user

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        //assign role

        $employee_role = Role::where('name', 'employee')->first();
        $employee_role->user()->save($user);

        //create token

        $token = $user->createToken('api_token')->plainTextToken;

        //return response

        $response = [
            'user' => $user,
            'access_token' => $token
        ];

        return $response()->json($response, 201);
    }
}