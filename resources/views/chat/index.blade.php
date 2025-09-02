@extends('layout.app')

@section('content')
    @push('styles')
        @vite(['resources/js/chat.js'])
    @endpush

    <div class="mt-3" id="vue-app">
        <chat-component :rooms="{{ json_encode($rooms) }}"></chat-component>
    </div>
@endsection