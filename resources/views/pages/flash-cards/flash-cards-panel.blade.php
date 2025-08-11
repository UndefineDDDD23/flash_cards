<x-layouts.app :title="env('APP_NAME')">
    
    <x-pages-structure.header/>

        <main class="main">
            <livewire:flash-cards.create-flash-card/>
            <livewire:flash-cards.flash-cards-list/>
        </main>

    <x-pages-structure.footer/>

</x-components.layouts.app>