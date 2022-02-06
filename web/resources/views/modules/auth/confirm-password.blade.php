<x-guest-layout>
    <x-front.auth-card>
        <x-slot name="logo">
            <a href="/" class="d-flex justify-content-center mb-4">
                <x-front.application-logo width=64 height=64 />
            </a>
        </x-slot>

        <div class="mb-4 text-muted">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <!-- Validation Errors -->
        <x-front.auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-front.label for="password" :value="__('Password')" />

                <x-front.input id="password" class=""
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <div class="d-flex justify-content-end mt-4">
                <x-front.button>
                    {{ __('Confirm') }}
                </x-front.button>
            </div>
        </form>
    </x-front.auth-card>
</x-guest-layout>
