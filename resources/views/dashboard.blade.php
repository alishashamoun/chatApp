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
                        <div>
                            <p class="top">Ross Edlin</p>
                            <small>
                                online
                            </small>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Chat -->
                    <div class="messages">
                        @include('receive', ['message' => "Hey! What's up!  👋"])
                    </div>
                    <!-- End Chat -->

                    <!-- Footer -->
                    <div class="bottom">
                        <form>
                            @csrf
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

        // jQuery click handler
        $(document).ready(function() {
            $('.chat-user').click(function() {
                var name = $(this).text();
                $('.top').text(name);
            });
        });
    </script>

</x-app-layout>
