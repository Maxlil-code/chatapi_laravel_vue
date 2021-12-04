<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    public function send(Request $request)
    {
        //return response()->json(Auth::id());

        $message = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('contact_id'),
            'message' => $request->input('text')
        ]);
        broadcast(new NewMessage($message));



        return httpResponse($message, 'succesfully sent', '201');
    }
}
