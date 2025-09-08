<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // $response = Http::withToken(env('OPENAI_API_KEY'))
            //     ->post('https://api.openai.com/v1/responses', [
            //         'model' => 'gpt-3.5-turbo',
            //         'previous_response_id' => $request->post('id') ?? null,
            //         'input' => $request->post('content'),
            //     ]);

            $response = Prism::text()
                ->using(Provider::OpenAI, 'gpt-3.5-turbo')
                ->withProviderOptions([ 
                    'previous_response_id' => $request->post('id')?? null,
                ]) 
                ->withPrompt($request->post('content'))
                ->asText();
            
            $id = $request->post('id') ?? $response->meta->id;;
            $content = $response->text;

            if (@$request->post('id')) {
                // find room
                $room = Room::where('response_open_ai_id', $request->post('id'))->first();
            }else{
                // store room 
                $room = Room::create([
                    'title' => substr($request->post('content'), 0, 50),
                    'response_open_ai_id' => $id,
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
                // 'raw_response' => json_encode($response),
            ]);

            DB::commit();

            return [
                'id' => $id,
                'content' => $content,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
