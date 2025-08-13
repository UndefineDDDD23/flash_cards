<header class="header">
    <a href="{{ env('APP_URL') }}" class="logo-link">
        <img src="{{ asset('storage/logo.png') }}" alt="" class="logo-link__image">
    </a>

    <nav class="header-nav-bar">
        <ul class="header-nav-bar-links-container">
            @guest()
                <li>
                    <a href="{{ route('login') }}" class="header-nav-bar-links-container__link">{{ __('pages-content.login') }}</a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="header-nav-bar-links-container__link">{{ __('pages-content.register') }}</a>
                </li>
            @endguest
            <li>
                <a href="{{ route('flash-cards-panel') }}" class="header-nav-bar-links-container__link">{{ __('pages-content.flash_cards_panel') }}</a>
            </li>
            <li>
                <a href="{{ route('flash-cards-study') }}" class="header-nav-bar-links-container__link">{{ __('pages-content.flash_cards_study') }}</a>
            </li>
        </ul>
    </nav>
</header>