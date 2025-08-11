<?php 

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use App\Contracts\AI\OpenRouterInterface;

abstract class OpenRouter implements OpenRouterInterface
{
    abstract protected function getStringModelID(): string;

    public function runApiQuery(string $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => $this->getStringModelID(),
            'messages' => [
                ['role' => 'system', 'content' => $message],
            ],
        ]);

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? null;
    }
}