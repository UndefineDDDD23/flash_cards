<x-layouts.app :title="env('APP_NAME')">
    
    <x-pages-structure.header/>

        <main class="main">
            <a href="{{ route('flash-cards-panel') }}">Flash cards list</a>
        </main>

    <x-pages-structure.footer/>

</x-components.layouts.app>