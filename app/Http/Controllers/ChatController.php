<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->take(3)->get();

        $data = [
            'rooms' => $rooms,
        ];

        return view('chat.index', $data);
    }
    
    public function show($id)
    {
        $rooms = Room::latest()->take(3)->get();
        $room = Room::with(['chats'])->findOrFail($id);
        

        $data = [
            'rooms' => $rooms,
            'room' => $room,
        ];

        return view('chat.show', $data);
    }
}
