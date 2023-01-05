<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    //* Login
    public function login(Request $request){

        // Validate the incoming request
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        // Get the user credentials
        $credentials = $request->only('email', 'password');

        // Attempt to login the user
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Incorrect email or password'
            ], 401);
        }

        $user = User::where("email", $request->email)->first();

        // return successful with user information as a json object
        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user(),
            'access_token' => $user->createToken('authToken')->plainTextToken,
        ]);

    }
    //* register
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }


    //* logout 
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }



    //* delete user
    public function delete(Request $request)
    {
        $request->user()->delete();
        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}
