@extends('layouts.app')
@section('contentChat')
<div class="row char-row">
    <div class="col-md-3">
        <div class="user">
            <h5>Users</h5>
            <ul class="list-group list-chat-item">
                @if($users->count())
                @foreach ($users as $user)
                <li class="chat-user-list">
                    <a class="nav-link" href="{{ route('message.conversation', $user->id) }}">
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
</div>
@endsection
@push('scripts')
    <script>
        $(function() {
            let $chatInput = $(".chat-input");
            let $chatInputToolbar = $(".chat-input-toolbar");
            let $chatBody = $(".chat-body");
    
            let user_id = "{{ auth()->user()->id }}";
            let ip_address = '127.0.0.1';
            let socket_port = '2022';
            let socket = io(ip_address + ':' + socket_port);
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
                        console.log(key);
                        let $userIcon = $(".user-icon-"+key);
                        $userIcon.addClass('text-success');
                        $userIcon.attr('title','Đang hoạt động');
                    }
                });
            });
        }); 
    </script>
@endpush