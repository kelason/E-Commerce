<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    //
    public function index() {
        $user = User::select('id', 'name', 'email', 'phone_number', 'user_type')
        ->where('user_type', '!=', 'Admin')
        ->get();

        return response()->json($user);
    }

    public function changeUser(Request $request)
    {
        $post = User::findOrFail($request->id);

        $post->user_type = ($request->user_type == "Seller") ? "Buyer" : "Seller";

        $post->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'User type change.'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|unique:users',
            'user_type' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status_code'=>400, 
                'message'=>'Validation Fail!! Email or Phone number already exists'
            ]);
        }

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->user_type = $request->user_type;
        $user->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'User created successfully!'
        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status_code' => 400, 
                'message' => 'Bad Request'
            ]);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'status_code' => 500,
                'message' => 'Wrong credentials!'
            ]);
        }

        // $user = User::where('email', $request->email)->first();

        // $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status_code' => 200
        ]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return response()->json([
            'status_code' => 200,
            'message' => 'Token Deleted'
        ]);
        
    } 
}
