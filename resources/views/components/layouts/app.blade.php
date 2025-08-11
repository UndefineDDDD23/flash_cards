@props(["title"])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Prefer explicit title; fallback to app name for SEO-friendly defaults --}}
    <title>{{ isset($title) ? $title : env('APP_NAME') }}</title>

    {{-- Application assets compiled via Vite --}}
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @livewireStyles()
    @fluxAppearance()
</head>
<body>    
    {{ $slot }}

    {{-- Livewire and Flux runtime scripts --}}
    @livewireScripts()
    @fluxScripts()
</body>
</html>