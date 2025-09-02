<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $response = Http::withToken(env('OPENAI_KEY'))
                ->post('https://api.openai.com/v1/responses', [
                    'model' => 'gpt-3.5-turbo',
                    'previous_response_id' => $request->post('id') ?? null,
                    'input' => $request->post('content'),
                ]);

            $id = $request->post('id') ?? $response->json('id');
            $content = $response->json('output.{first}.content.{first}.text');
            $message_id = $response->json('output.{first}.id');

            if (@$request->post('id')) {
                // find room
                $room = Room::where('response_open_ai_id', $request->post('id'))->first();
            }else{
                // store room 
                $room = Room::create([
                    'title' => substr($request->post('content'), 0, 50),
                    'response_open_ai_id' => $response->json('id'),
                ]);
            }

            // add user chat
            Chat::create([
                'message' => $request->post('content'),
                'side' => 'user',
                'room_id' => $room->id,
            ]);

            // add ai chat
            Chat::create([
                'message' => $content,
                'side' => 'assistant',
                'room_id' => $room->id,
                'message_open_ai_id' => $message_id,
            ]);

            DB::commit();

            return [
                'raw' => $response,
                'id' => $id,
                'content' => $content,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
