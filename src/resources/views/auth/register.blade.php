<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-slate-800">Create an account</h2>
        <p class="text-slate-500 text-sm mt-1">One email can be shared across multiple accounts (e.g. parent &amp; children)</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Display Name -->
        <div>
            <x-input-label for="name" :value="__('Display Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name"
                placeholder="e.g. Emma" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username (unique login identifier) -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                :value="old('username')" required autocomplete="username"
                placeholder="e.g. emma2024" />
            <p class="mt-1 text-xs text-slate-400">Letters, numbers, hyphens and underscores only. Used to log in.</p>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email (optional, non-unique — shared by parent + children) -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email (optional)')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" autocomplete="email"
                placeholder="parent@example.com" />
            <p class="mt-1 text-xs text-slate-400">Multiple accounts can share the same email address.</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password" name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-slate-500 hover:text-indigo-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
