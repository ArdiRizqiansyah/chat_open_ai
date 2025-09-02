@extends('layout.app')

@section('content')
    @push('styles')
        @vite(['resources/js/chat.js'])
    @endpush

    <div class="mt-3" id="vue-app">
        <chat-component :rooms="{{ json_encode($rooms) }}" :room="{{ json_encode($room) }}" :chats="{{ json_encode($room->chats) }}"></chat-component>
    </div>
@endsection