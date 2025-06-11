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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $users = User::get();
        // dd($users);

        return view('dashboard', compact('users'));
    }


    // public function broadcast(Request $request)
    // {


    //     broadcast(new PusherBroadcast($request->message))->toOthers();

    //     return view('broadcast', ['message' => $request->get('message')]);

    // }

    public function broadcast(Request $request)
    {
        try {
            $request->validate([
                'sender_id' => 'required|exists:users,id',
                'receiver_id' => 'nullable|exists:users,id',
                'chat_id' => 'nullable',
                'message' => 'required|string',
            ]);

            // Save message in DB
            $message = Message::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'chat_id' => $request->chat_id,
                'message' => $request->message,
            ]);

            // Broadcast
            broadcast(new PusherBroadcast($message->message))->toOthers();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send message.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMessages()
    {
        $messages = Message::get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'messages' => $messages,
            ]
        ]);
    }


    public function receive(Request $request)
    {
        return view('receive', ['message' => $request->get('message')]);
    }

    public function chatWithUser(Request $request, $userId)
    {
        dd($request->chat_id, Chat::find($request->chat_id));
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
