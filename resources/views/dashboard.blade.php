<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 bg-light border-end">
                <h5 class="p-3">Sidebar</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($users as $user)
                        <li class="chat-user">{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Chat Window -->
            <div class="col-md-9">
                <h3 class="p-3">Chat Area</h3>
                {{-- Chat Section --}}
                <div class="chat">

                    <!-- Header -->
                    <div class="top">
                        <img src="{{ asset('assets/image/images.jpeg') }}" alt="" width="50">
                        @foreach ($users as $user)
                            <li class="chat-user" data-id="{{ $user->id }}">{{ $user->name }}</li>
                        @endforeach
                    </div>
                    <!-- End Header -->

                    <!-- Chat -->
                    <div class="messages">
                        @include('receive', ['message' => "Hey! What's up!  👋"])
                    </div>
                    <!-- End Chat -->

                    <!-- Footer -->
                    <div class="bottom">
                        <form method="POST" action="">
                            @csrf
                            <input type="hidden" name="chat_id" value="{{ $chat->id ?? '' }}">
                            <input type="hidden" name="receiver_id" value="{{ $user->id }}">

                            <input type="text" id="message" name="message" placeholder="Enter message..."
                                autocomplete="off">
                            <button type="submit">Send</button>
                        </form>

                    </div>
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!--! Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'us2'
        });
        console.log(pusher.connection.state);
        const channel = pusher.subscribe('public');

        //Receive messages
        channel.bind('chat', function(data) {
            $.post("/receive", {
                    _token: '{{ csrf_token() }}',
                    message: data.message,
                })
                .done(function(res) {
                    $(".messages > .message").last().after(res);
                    $(document).scrollTop($(document).height());
                });
        });

        $('.chat-user').click(function() {
            var userId = $(this).data('id');
            $('#receiver_id').val(userId);


            $.get(`/chat/${userId}`, function(res) {
                $('.top').html(`
            <img src="/assets/image/images.jpeg" width="50">
            <div>
                <p>${res.user.name}</p>
                <small>online</small>
            </div>
        `);

                let messagesHtml = '';
                res.messages.forEach(function(msg) {
                    messagesHtml += `<div class="message">${msg.message}</div>`;
                });

                $('.messages').html(messagesHtml);
            });
        });


        //Broadcast messages
        $("form").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "/broadcast",
                method: 'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    message: $("form #message").val(),
                }
            }).done(function(res) {
                $(".messages > .message").last().after(res);
                $("form #message").val('');
                $(document).scrollTop($(document).height());
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

</x-app-layout>
