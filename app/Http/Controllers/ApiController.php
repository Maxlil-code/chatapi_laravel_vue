<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Chatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ApiController extends Controller
{
    /*public function create_user(Request $request)
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
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user_created'=> $user
            ];
            return httpResponse($data, $message, $statusCode);
        }catch (\Exception $ex){
            return response()->json([
                'data' => $ex
            ]);
        }
    }*/

    public function listUsers()
    {
        $user = User::all();
        return httpResponse($user,'List of all users',200);
        //return $user->toJson();
    }

    public function conversation(Request $request)
    {
        $chat = new Chat();

        $chat->sender_id = $request->input('sender_id');
        $chat->receiver_id = $request->input('receiver_id');
        $chat->message = $request->input('message');

        try {
            $chat->save();
            return httpResponse($chat,'Saved', 201);
        }catch (\Exception $ex){
            return response()->json([
                $ex
            ],404);
        }
    }



    public function show_message($sender, $receiver)
    {
        $messages  = Chat::where([['sender_id', '=', $sender], ['receiver_id', '=', $receiver]])->orWhere([['sender_id', '=', $receiver], ['receiver_id', '=', $sender]])->limit(10)->orderBy('id', 'desc')-> get();

        return httpResponse($messages, '', 200);
    }

    public function auth_users(Request $request)
    {
        $users = Auth::user();
        return response()->json([
           $users
        ]);
    }

    public function sent_received_messages($id)
    {
        $user = User::where('id', '!=', Auth::id())->get()->toArray();
        $messages = Chat::all();


        foreach ($user as $each){
            
        }
        $messages_exchanged  = Chat::where([['sender_id', '=', $id]])->orWhere([['receiver', '=', $id]])->limit(10)->orderBy('id', 'desc')->get();
    }

}
