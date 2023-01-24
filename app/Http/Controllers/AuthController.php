<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        //validated
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //attempt auth
        if (Auth::attempt($validated)) {
            //get user object
            $user = User::where('email', $validated['email'])->first();

            //create token
            $token = $user->createToken('api_token')->plainTextToken;

            //return token response
            $response = [
                'data' => [
                    'access_token' => $token
                ]
            ];

            return response()->json($response);
        } else {
            //return res unauth
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(null, 204);
    }

    public function resetPassword(Request $request)
    {
        //validation
        $validated = $request->validate([
            'password' => 'required|min:8'
        ]);

        //get user
        $user = $request->user();

        //change password
        $user->password = Hash::make($validated['password']);

        //update user
        $user->save();

        //delete all token
        $user->tokens()->delete();

        //return response
        $response = [
            'data' => [
                'message' => 'Password change successfully'
            ]
        ];

        return response()->json($response, 200);
    }
}
