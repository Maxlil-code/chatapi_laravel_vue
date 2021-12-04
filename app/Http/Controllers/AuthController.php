<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'username' => ['required', 'unique:users,username'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:6', Password::defaults()]
        ]);

        if ($validation->fails()){
            $data = $validation->errors()->toArray();
            $message = 'An error occured';
            $statusCode = 403;
            return httpResponse($data, $message, $statusCode);
        }

        try {
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            // Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            $message = 'User created successfully';
            $statusCode = 201;
            $data = [
                'token' => $token,
                'token_type' => 'Bearer',
                'user_created'=> $user
            ];
            return httpResponse($data, $message, $statusCode);
        }catch (\Exception $ex){
            return response()->json([
                'data' => $ex
            ]);
        }
    }


    public function login_user(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'username' => [],
            'email' => ['required','email'],
            'password' => ['required','min:6']
        ]);

        if ($validation->fails()){
            $data = $validation->errors()->toArray();
            $message = '';
            $statusCode = 401;
            return httpResponse($data, $message, $statusCode);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)){
            $data = $credentials;
            $message = 'Invalid credentials';
            $statusCode = 403;
            return httpResponse($data, $message, $statusCode);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->status = 1;

        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'authenticated user' => $user,
            'token' => $token
        ];
        $message = 'Logged In successfully';
        $statusCode = 200;

        return httpResponse($data, $message, $statusCode);
    }

    public function logout(Request $request)
    {
        // $user = $request->user();
        // $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->status = 0;
        auth()->user()->tokens()->delete();
        $data = [];
        return  httpResponse($data, 'Logout Successful', 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)){
            return $credentials;
        }

        return 'Login Login details are not valid';
    }
}
