@extends('layouts.app')
@section('content')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <div id="chat_vue">
        <chat-vue :current_id="{{json_encode($seeker)}}"
                  :provider_id="{{json_encode($provider->id)}}"
                  :conversation_id="{{json_encode($documentId)}}"
                  :provider_name="{{json_encode($provider->name)}}"></chat-vue>
    </div>
@endsection
