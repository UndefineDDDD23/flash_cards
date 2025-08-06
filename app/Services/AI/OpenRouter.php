<?php

namespace App\Services\AI;

use App\Contracts\AI\OpenRouterInterface;
use Illuminate\Support\Facades\Http;

abstract class OpenRouter implements OpenRouterInterface {
    public function runApiQuery(string $message, string $model) {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type'  => 'application/json',
        ])
        ->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => $model,
            'messages' => [
                ['role' => 'system', 'content' => $message],
            ],
        ]);
        dd($response);
        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? null;
    }
}