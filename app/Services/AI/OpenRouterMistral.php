<?php

namespace App\Services\AI;

use App\Services\AI\OpenRouter;
use Illuminate\Support\Facades\Http;
use App\Contracts\AI\OpenRouterInterface;

class OpenRouterMistral extends OpenRouter implements OpenRouterInterface {
    public function getStringModelID(): string {
        return 'mistralai/mistral-small-3.2-24b-instruct:free';
    }
}