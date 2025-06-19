@extends('layout.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* .messages {
                                                    height: 400px;
                                                    overflow-y: auto;
                                                    padding: 15px;
                                                    border: 1px solid #ddd;
                                                    margin-bottom: 10px;
                                                    background: #f9f9f9;
                                                } */

        /* .message {
                                                margin-bottom: 10px;
                                                padding: 10px;
                                                background-color: #d1e7dd;
                                                border-radius: 5px;
                                                width: fit-content;
                                                } */
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 bg-light border-end">
                <h5 class="p-3">Sidebar</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item chat-user">
                        {{ $chatWithName ?? '' }}
                    </li>

                </ul>

            </div>

            <!-- Chat Window -->
            <div class="col-md-9">
                <h3 class="p-3">Chat Area</h3>
                {{-- Chat Section --}}
                <div class="chat">

                    <!-- Header -->
                    <div class="top p-2 bg-light">
                        <img src="{{ asset('assets/image/images.jpeg') }}" alt="" width="50">
                        <h5 id="chatUserName">
                            {{ $chatWithName ?? '' }}
                        </h5>

                        {{-- <pre>{{ dd($user) }}</pre> --}}

                    </div>
                    <!-- End Header -->

                    <!-- Chat -->
                    <div class="messages">
                        @include('receive', ['message' => "Hey! What's up!  👋"])
                        @foreach ($messages as $msg)
                            <div class="message mb-2">
                                <div class=" text-{{ $msg->sender_id == auth()->id() ? 'end' : 'start' }}">
                                    <span class="badge bg-{{ $msg->sender_id == auth()->id() ? 'secondary' : 'primary' }}">
                                        {{ $msg->message }}
                                    </span>
                                    <br>
                                    <small>{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- End Chat -->

                    <!-- Footer -->
                    <div class="bottom">
                        <form>
                            @csrf
                            {{-- <input type="hidden" name="chat_id" value=""> --}}
                            <input type="hidden" id="receiver_id" name="receiver_id"
                                value="{{ $user->id ?? ($chatReceiver->id ?? '') }}">

                            <input type="text" id="message" name="message" placeholder="Enter message..."
                                autocomplete="off">
                            <button type="submit"></button>
                        </form>
                    </div>
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>




    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'us2'
        });

        const channel = pusher.subscribe('public');

        //Receive messages
        channel.bind('chat', function(data) {
            console.log('Received:', data);
            if (data.receiver_id == {{ auth()->id() }}) {
                $.post("/receive", {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        message: data.message,
                    })
                    .done(function(res) {
                        $(".messages > .message").last().after(res);
                        $(document).scrollTop($(document).height());
                    });
            }
        });



        //         let messagesHtml = '';
        //         res.messages.forEach(function(msg) {
        //             messagesHtml += `<div class="message">${msg.message}</div>`;
        //         });

        //         $('.messages').html(messagesHtml);
        //     });
        // });

        // document.querySelectorAll('.chat-user').forEach(item => {
        //     item.addEventListener('click', function() {
        //         let userName = this.dataset.name;

        //         // Set user name in top header
        //         document.getElementById('chatUserName').textContent = userName;
        //     });
        // });


        //Broadcast messages
        $("form").submit(function(event) {
            event.preventDefault();

            let selectedUserId = document.getElementById('receiver_id').value;
            console.log('Selected Receiver ID:', selectedUserId); // check value

            // continue with ajax


            $.ajax({
                url: "{{ route('broadcast') }}",
                method: 'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    sender_id: {{ auth()->id() }},
                    receiver_id: selectedUserId,
                    // chat_id: null,
                    message: $("form #message").val(),
                }



            }).done(function(res) {
                if (res.status === 'success') {
                    let isSender = res.data.sender_id === res.current_user_id;

                    let html = `
            <div class="message mb-2">
                <div class="text-${isSender ? 'end' : 'start'}">
                    <span class="badge bg-${isSender ? 'secondary' : 'primary'}">
                        ${res.data.message}
                    </span><br>
                    <small class="text-muted">${res.data.created_at}</small>
                </div>
            </div>
        `;
                    // console.log(typeof selectedUserId); // ye `"string"` hona chahiye
                    // console.log(selectedUserId); // ye `"1"` hona chahiye
                    $('.messages').append(html);
                    $('.messages').scrollTop($('.messages')[0].scrollHeight);
                    $("form #message").val('');

                }
            });

        });


        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}")
        @endif
        @if (session('info'))
            toastr.info("{{ session('info') }}")
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}")
            @endforeach
        @endif
    </script>
@endsection
