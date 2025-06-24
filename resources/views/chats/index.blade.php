@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-12">
            <form action="{{ route('chats.search') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search for a user"
                    aria-label="Search for a user">
                <button type="submit" class="btn ml-2 btn-primary">Search</button>
            </form>
        </div>

        <div class="col-12">
            <h3>Your Chats</h3>
            @if ($chats->isEmpty())
                <p>No chats available. Start a conversation!</p>
            @else
                <ul class="list-group">
                    @foreach ($chats as $chat)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $chat->userOne->name === auth()->user()->name ? $chat->userTwo->name : $chat->userOne->name }}</strong>
                                <small>Last message:
                                    {{ $chat->messages->last()->message ?? 'No messages yet' }}</small>
                            </div>
                            <a href="{{ route('chats.show', $chat->id) }}" class="btn btn-sm btn-info">Continue</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- User List --}}
        <div class="col-12 mt-5">
            <h3>Start a New Chat</h3>
            <ul class="list-group">
                @foreach ($users as $chatUser)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <p>{{ $chatUser->name }}
                            {{-- (ID: {{ $chatUser->id }}) --}}
                        </p>
                        <a href="{{ route('broadcastshow', ['id' => $chatUser->id]) }}" class="btn btn-sm btn-success">Start
                            Chat</a>
                    </li>
                @endforeach

            </ul>

        </div>
    </div>

@endsection
