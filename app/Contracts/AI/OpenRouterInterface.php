<?php

namespace App\Contracts\AI;

/**
 * Interface for interacting with the OpenRouter API service.
 *
 * This interface defines the basic contract for making API queries
 * to the OpenRouter service.
 */
interface OpenRouterInterface
{
    /**
     * Executes an API query to the OpenRouter service.
     *
     * @param string $message The message/content to send to the API
     * @return mixed The response from the OpenRouter API
     */
    public function runApiQuery(string $message);
}