<?php

namespace App\Http\Controllers;

use App\Models\Chatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function create(Request $request)
    {

       $username = $request->input('username');
       $email = $request->input('email');
       $usernameCheck  = User::where('username', '=', $username)->first();
       $emailCheck  = User::where('email', '=', $email)->first();

       /* $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));*/

        //$duplicate = Chatter::where('email', $chatters->email);
        if ($usernameCheck === null && $emailCheck === null){
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
        }else{
            return response()->json([
               'error'=> 'Email or Username already exist'
            ]);
        }

    }

    public function listUsers()
    {
        return User::all();
    }
}
