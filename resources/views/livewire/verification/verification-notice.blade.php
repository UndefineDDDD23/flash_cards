<main class="main">
    <h1>{{ __('pages-content.verification') }}</h1>
    <p>{{ __('pages-content.verification_instruction_description') }}</p>
    @if (session('verification-code-sent'))
        <p>{{ __('pages-content.verification_instruction_sent') }}</p>
    @endif
    <button wire:click="resendVerificationNotification">{{ __('pages-content.verification_resend_button_text') }}</button>
</main>