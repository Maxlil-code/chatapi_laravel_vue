<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Chatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function create_user(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'username' => ['required', 'unique:users,username'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:6']
        ]);

        if ($validation->fails()){
            return response()->json(['error' => $validation->errors()->toArray()]);
        }

        try {
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user_created'=> $user
            ]);
        }catch (\Exception $ex){
            return response()->json([
                'data' => $ex
            ]);
        }
    }

    public function listUsers()
    {
        $user = User::all();
        return response()->json(
            $user
        );
        //return $user->toJson();
    }

    public function conversation(Request $request)
    {
        $message = new Chat();

        $message->sender_id = $request->input('sender_id');
        $message->receiver_id = $request->input('receiver_id');
        $message->message = $request->input('message');

        try {
            $message->save();
            return response()->json([
               $message
            ],200);
        }catch (\Exception $ex){
            return response()->json([
                $ex
            ],404);
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
            return response()->json(['error' => $validation->errors()->toArray()]);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->status = 1;

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout_user()
    {

    }

    public function show_message($sender, $receiver)
    {
        $messages  = Chat::where([['sender_id', '=', $sender], ['receiver_id', '=', $receiver]])->orWhere([['sender_id', '=', $receiver], ['receiver_id', '=', $sender]])->limit(10)->orderBy('id', 'desc')-> get();

        return response()->json($messages, 200);
    }

    public function auth_users(Request $request)
    {

        return response()->json([
            $request->user()
        ]);
    }
}
