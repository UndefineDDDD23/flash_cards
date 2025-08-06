<x-layouts.app :title="env('APP_NAME')">
    
    <x-pages-structure.header/>

        <main class="main">
            <livewire:flash-cards.study-flash-cards/>
        </main>

    <x-pages-structure.footer/>

</x-components.layouts.app>