<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Models\Message;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('dashboard', compact('users'));
    }

    public function broadcast(Request $request)
    {
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
