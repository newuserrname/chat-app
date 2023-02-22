@extends('layouts.app')
@section('contentChat')
<div class="row char-row">
    <div class="col-md=3">
        <div class="user">
            <h5>Users</h5>
            <ul class="list-group list-chat-item">
                @if($users->count())
                @foreach ($users as $user)
                <li class="chat-user-list">
                    <a class="nav-link" 
                    href="{{ route('message.conversation', $user->id) }}"
                        @if($user->id == $friendInfo->id) style="color: #671f23" @endif>
                        <div class="chat-image">
                            {!! makeImageFromName($user->name) !!}
                            <i class="fa fa-circle user-status-icon user-icon-{{ $user->id }}" title="Hoạt động vài phút trước"></i>
                        </div>
                        <div class="chat-name font-weight-bold">
                            {{ $user->name }}
                        </div>
                    </a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-9 chat-section">
        <div class="chat-header">
            <div class="chat-image">
                {!! makeImageFromName($friendInfo->name) !!}
            </div>
            <div class="chat-name front-weight-bold">
                {{ $friendInfo->name }}
                <i title="Đang hoạt động" 
                id="userStatusHead{{ $friendInfo->id }}"></i>
            </div>
        </>
        <div class="chat-body overflow-auto">
            <div class="message-listing" id="messageWrapper">
                {{--  New Message  --}}
            </div>
        </div>
        <div class="chat-box">
            <b id="text-typing"></b>
            <div class="chat-input-toolbar">
                <button title="Add File" class="btn btn-light btn-sm btn-file-upload">
                    <i class="fa-solid fa-paperclip"></i>
                </button>
                <button title="Bold" class="btn btn-light btn-sm tool-items"
                onclick="document.execCommand('bold', false, '')">
                    <i class="fa-solid fa-bold"></i>
                </button>
                <button title="italic" class="btn btn-light btn-sm tool-items"
                onclick="document.execCommand('italic', false, '')">
                    <i class="fa-solid fa-italic"></i>
                </button>
            </div>
            <div class="chat-input bg-white" id="chatInput" contenteditable="">

            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function () {
            let $chatInput = $(".chat-input");
            let $chatInputToolbar = $(".chat-input-toolbar");
            let $chatBody = $(".chat-body");
            let $messageWrapper = $("#messageWrapper");

            let user_id = "{{ auth()->user()->id }}";
            let ip_address = '127.0.0.1';
            let socket_port = '2022';
            let socket = io(ip_address + ':' + socket_port);
            let friendId = "{{ $friendInfo->id }}";
            //socket.current = io("ws://localhost:4000")

            socket.on('connect', function() {
                socket.emit('user_connected', user_id);
            });

            socket.on('updateUserStatus', (data) => {
                let $userStatusIcon = $('.user-status-icon');
                $userStatusIcon.removeClass('text-success');
                $userStatusIcon.attr('title', 'Hoạt động vài phút trước');
                console.log(data);

                $.each(data, function (key, val) {
                    if (val !== null && val !== 0) {
                        //console.log(key);
                        let $userIcon = $(".user-icon-"+key);
                        $userIcon.addClass('text-success');
                        $userIcon.attr('title','Đang hoạt động');
                    }
                });
            });

            $chatInput.keypress(function (e) {
                let message = $(this).html();
                if (e.which === 13 && !e.shiftKey) {
                    $chatInput.html("");
                    sendMessage(message);
                    //console.log(message); 
                    return false;
                }
            });

            function sendMessage(message) {
                let url = "{{ route('message.send-message') }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
                
                formData.append('message', message);
                formData.append('_token', token);
                formData.append('receiver_id', friendId);

                appendMessageToSender(message);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success) {
                            console.log(response.data);
                        }
                    }
                });
            }

            function appendMessageToSender(message) {
                let name = '{{ $myInfo->name }}';
                let image = '{!! makeImageFromName($myInfo->name) !!}';

                let userInfo = '<div class="col-md-12 user-info">\n' +
                    '<div class="chat-image">\n' + image +
                    '</div>\n' + '\n' +
                    '<div class="chat-name font-weight-bold">\n' + name +
                        '<span class="small time" title="'+ getCurrentDateTime() +'">\n' + getCurrentTime() + '</span>\n' +
                    '</div>\n' +
                '</div>\n';

                let messageContent = '<div class="col-md-12 message-content">\n' +
                    '<div class="message-text">\n' + message +
                    '</div>\n' +
                '</div>\n';

                let newMessage = '<div class="row message align-items-center mb-2">' + userInfo + messageContent + '</div>';

                $messageWrapper.append(newMessage);
            };

            function appendMessageToReceiver(message) {
                let name = '{{ $friendInfo->name }}';
                let image = '{!! makeImageFromName($friendInfo->name) !!}';

                let userInfo = '<div class="col-md-12 user-info">\n' +
                    '<div class="chat-image">\n' + image +
                    '</div>\n' + '\n' +
                    '<div class="chat-name font-weight-bold">\n' + name +
                        '<span class="small time" title="'+ dateFormat(message.created_at) +'">\n' + timeFormat(message.created_at) + '</span>\n' +
                    '</div>\n' +
                '</div>\n';

                let messageContent = '<div class="col-md-12 message-content">\n' +
                    '<div class="message-text">\n' + message.content +
                    '</div>\n' +
                '</div>\n';

                let newMessage = '<div class="row message align-items-center mb-2">' + userInfo + messageContent + '</div>';

                $messageWrapper.append(newMessage);
            };

            socket.on("private-channel:App\\Events\\PrivateMessageEvent", function(message) {
                appendMessageToReceiver(message);
            });

        });
    </script>
    <script>
        var isTyping = false;
            var isNotTyping;
            document.getElementById('chatInput').onkeypress = () => {
                sendIsTypingToUser()
                if (isNotTyping != undefined) clearTimeout(isNotTyping);
                isNotTyping = setTimeout(sendIsNotTyping, 900);
                };
                function sendIsTypingToUser(){
                    if(!isTyping){
                        var parent = document.getElementById('text-typing').innerHTML = "đang soạn tin...";
                       isTyping = true
                       }
                    }
                 function sendIsNotTyping(){
                    var parent = document.getElementById('text-typing').innerHTML = "";
                    isTyping = false
                    }
    </script>
@endpush
