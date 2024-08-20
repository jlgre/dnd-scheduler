<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscordWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        switch ($payload['type']) {
            case 1:
                return $this->pong($payload);
            case 2:
                return $this->handleSlashCommand($payload);
        }
    }

    public function pong($payload)
    {
        return $payload;
    }

    public function handleSlashCommand($payload)
    {
        return [
            'type' => 4,
            'data' => [
                'content' => 'response from discord app'
            ]
        ];
    }
}
