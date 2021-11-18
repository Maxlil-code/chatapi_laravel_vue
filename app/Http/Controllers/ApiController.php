<?php

namespace App\Http\Controllers;

use App\Models\Chatter;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function create(Request $request)
    {
        $chatters = new Chatter();

        $chatters->username = $request->input('username');
        $chatters->email = $request->input('email');
        $chatters->password = $request->input('password');

        //$duplicate = Chatter::where('email', $chatters->email);

        try {
            $chatters->save();
            return response()->json([
                'data'=> $chatters
            ]);
        }catch (\Exception $ex){
            return response()->json([
                'data' => $ex
            ]);
        }
    }
}
