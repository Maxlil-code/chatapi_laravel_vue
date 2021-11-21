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
    public function create(Request $request)
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
            return response()->json([
                'data'=> $user
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

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'username' => ['required', 'unique:users,username'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:6']
        ]);

        if ($validation->fails()){
            return response()->json(['error' => $validation->errors()->toArray()]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)){
            return $credentials;
        }

        return 'Login Login details are not valid';
    }

    public function show_message($sender, $receiver)
    {
        $messages  = Chat::where([['sender_id', '=', $sender], ['receiver_id', '=', $receiver]])->orWhere([['sender_id', '=', $receiver], ['receiver_id', '=', $sender]])->limit(10)->orderBy('id', 'desc')-> get();

        return response()->json($messages, 200);
    }
}
