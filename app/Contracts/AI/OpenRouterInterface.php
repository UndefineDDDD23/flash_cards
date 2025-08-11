<?php

namespace App\Contracts\AI;

interface OpenRouterInterface {
    public function runApiQuery(string $message);
}