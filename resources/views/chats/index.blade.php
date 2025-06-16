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

            {{-- Agar search hua hai aur koi user mila hai --}}
            @if (request('search'))
                @if ($users->count() > 0)
                    <ul class="list-group">
                        @foreach ($users as $user)
                            @if ($user->id !== auth()->user()->id)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                    <a href="{{ route('broadcast', ['id' => $user->id]) }}"
                                        class="btn btn-sm btn-success">Start Chat</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p>No users found.</p>
                @endif
            @else
                {{-- Jab search nahi hua to normal user list dikhao --}}
                <ul class="list-group">
                    @foreach ($users as $user)
                        @if ($user->id !== auth()->user()->id)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                                <a href="{{ route('broadcast', ['id' => $user->id]) }}" class="btn btn-sm btn-success">Start
                                    Chat</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

@endsection
