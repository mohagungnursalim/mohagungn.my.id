<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-black">{{ __('Welcome back') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Please enter your details to sign in.') }}</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-black">{{ __('Email') }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" 
                   class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm placeholder-gray-400
                          focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-colors" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-black">{{ __('Password') }}</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" 
                   class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm placeholder-gray-400
                          focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-colors" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-black shadow-sm focus:ring-black focus:border-black cursor-pointer">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black active:bg-gray-900 transition-colors">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</div>
