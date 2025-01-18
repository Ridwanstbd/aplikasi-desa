<<<<<<< HEAD
<x-Layouts.AuthLayout>
    <x-Layouts.FormAuthContainer title="Login to your Account">
        <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <x-Fragments.Form.EmailInput 
                name="email"
                id="email"
                label="Email"
                placeholder="example@example.com"
                required
            />
            <x-Fragments.Form.PasswordInput 
                name="password"
                id="password"
                label="Password"
                placeholder="input password here"
                required
            />

            <div class="flex items-center justify-between">
                <x-Elements.Checkbox id="remember_me"name="remember"label="Remember Me"/>
                <div class="text-sm">
                    <x-Elements.Link href="{{ route('password.request')}}" >
                        Forgot your password?
                    </x-Elements.Link>
                </div>
            </div>

            <x-Elements.Form.ButtonSubmit type="submit">
                <h1>Login</h1>
            </x-Elements.Form.ButtonSubmit>
        </form>
        <x-Fragments.RegisterLink />
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
=======
@extends('layouts.guest')
@section('content')
<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />
<div class="col-8 col-md-6 m-auto pt-4">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
</div>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
