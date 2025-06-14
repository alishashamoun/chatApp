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
        $user = Auth::user();

        $chats = Chat::where('person_one_id', $user->id)
            ->orWhere('person_two_id', $user->id)
            ->with(['userOne', 'userTwo'])->get();
        if (Auth::user()->hasRole(['admin', 'manager'])) {
            $users = User::role('User')->get();
        } else {
            $users = User::role('manager')->get();
        }

        $users = User::get();
        // dd($users);
        $messages = Message::get();
        return view('dashboard2', compact('users', 'messages', 'chats', 'user'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (Auth::user()->hasRole(['admin', 'manager'])) {
            $users = User::role('User')->where('name', 'like', "%$search%")->get();
        } else {
            $users = User::role('manager')->where('name', 'like', "%$search%")->get();
        }
        $chats = Chat::with(['userOne', 'userTwo'])->get();
        $messages = Message::get();

        return view('chats.index', compact('users', 'chats', 'messages'));
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
                'current_user_id' => auth()->id(),
                'data' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->diffForHumans(),
                ],
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
        dd($request->chat_id, Message::find($request->chat_id));
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

    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        $user = Auth::user();

        if ($chat->user_one_id !== $user->id && $chat->user_two_id !== $user->id) {
            abort(403);
        }

        $messages = $chat->messages()->with('sender')->orderBy('created_at')->get();

        $chat->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $chat->user_one_id === $user->id ? $chat->userTwo : $chat->userOne;

        $chats = Chat::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with([
                'userOne',
                'userTwo',
                'messages'

            ])
            ->get();
        return view('chats.show', compact('chats', 'chat', 'otherUser', 'user', 'messages'));
    }

    public function initiateChat(Request $request, $userTwo)
    {
        $user = Auth::user();
        $Usertwomodel = User::findOrFail($userTwo);


        if ($userTwo === $user->id) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        $chat = Chat::where(function ($query) use ($user, $userTwo) {
            $query->where('user_one_id', $user->id)
                ->where('user_two_id', $userTwo);
        })->orWhere(function ($query) use ($user, $userTwo) {
            $query->where('user_one_id', $userTwo)
                ->where('user_two_id', $user->id);
        })->first();

        if (!$chat) {

            $chat = Chat::create([
                'user_one_id' => $user->id,
                'user_two_id' => $userTwo,
            ]);


            broadcast(new ChatInitiated($chat))->toOthers();

            $Usertwomodel->notify(new NewChatNotification($chat, $Usertwomodel));


        }

        return redirect()->route('dashboard', $chat->id);
    }

    public function showChats()
    {
        $user = Auth::user();

        $chats = Chat::where('person_one_id', $user->id)
            ->orWhere('person_two_id', $user->id)
            ->with(['userOne', 'userTwo'])->get();
        if (Auth::user()->hasRole(['Admin', 'manager'])) {
            $users = User::role('User')->get();
        } else {
            $users = User::role('manager')->get();
        }

        return view('chats.index', compact('chats', 'users'));
    }

}
