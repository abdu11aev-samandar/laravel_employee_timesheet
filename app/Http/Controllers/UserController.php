<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        //input validation
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        //get user object
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        //update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        //return response
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response(null, 204);
    }
}
