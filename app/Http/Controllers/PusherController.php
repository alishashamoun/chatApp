<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Log;

class PusherController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $chats = Chat::where('person_one_id', $user->id)
            ->orWhere('person_two_id', $user->id)
            ->with(['userOne', 'userTwo'])->get();
        if (Auth::user()->hasRole(['admin', 'manager', 'user'])) {
            $users = User::role('user')->get();
        } else {
            $users = User::role('manager')->get();
        }

         $chat = Chat::where('person_one_id', auth()->id())
                ->orWhere('person_two_id', auth()->id())
                ->first();

        return view('dashboard', compact('users', 'chats', 'chat'));
    }

    public function broadcast(Request $request)
    {
        // chat ko find karo
        $chat = Chat::find($request->chat_id);

        // agar chat na mile to error
        if (!$chat) {
            return response()->json(['error' => 'Chat not found'], 404);
        }

        // receiver ko set karo agar frontend se nahi aaya to
        $receiver_id = $request->receiver_id ?? (
            $chat->person_one_id == auth()->id()
            ? $chat->person_two_id
            : $chat->person_one_id
        );

        // message create karo
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver_id,
            'chat_id' => $chat->id,
            'message' => $request->message,
        ]);


        broadcast(new PusherBroadcast($request->get('message')))->toOthers();
        return view('broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request)
    {
        return view('receive', ['message' => $request->get('message')]);
    }

    public function chatWithUser($userId)
    {
        $user = User::findOrFail($userId);

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->get();

        return response()->json([
            'messages' => $messages,
            'user' => $user
        ]);
    }

}
