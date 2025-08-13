<?php

namespace App\Services\AI;

use App\Services\AI\OpenRouter;
use Illuminate\Support\Facades\Http;
use App\Contracts\AI\OpenRouterInterface;

/**
 * Class OpenRouterMistral
 * 
 * This class extends the OpenRouter abstract class to provide a specific implementation
 * for the Mistral AI model, allowing interaction with the OpenRouter API using this model.
 */
class OpenRouterMistral extends OpenRouter implements OpenRouterInterface {
    /**
     * Returns the string model ID for the Mistral AI model.
     *
     * @return string
     */
    public function getStringModelID(): string {
        return 'mistralai/mistral-small-3.2-24b-instruct:free';
    }
}