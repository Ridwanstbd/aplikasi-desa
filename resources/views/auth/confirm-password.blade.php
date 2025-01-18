<<<<<<< HEAD
<x-Layouts.AuthLayout >
    <x-Layouts.FormAuthContainer title="Konfirmasi Password">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf    
            <!-- Password -->
            <x-Fragments.Form.FormGroup label="Password" for="password">
                <x-Fragments.Form.Input id="password" type="password" name="password" required autocomplete="current-password" />
            </x-Fragments.Form.FormGroup>
            <x-Elements.Form.FormSubmit>
                {{ __('Confirm') }}
            </x-Elements.Form.FormSubmit>
        </form>
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
=======
@extends('layouts.guest')
@section('content')
<div class="mb-4 text-sm text-gray-600">
    {{ __('Ini adalah area aman dari aplikasi. Harap konfirmasikan kata sandi Anda sebelum melanjutkan.') }}
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <!-- Password -->
    <div>
        <x-input-label for="password" :value="__('Password')" />

        <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="flex justify-end mt-4">
        <x-primary-button>
            {{ __('Confirm') }}
        </x-primary-button>
    </div>
</form>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
