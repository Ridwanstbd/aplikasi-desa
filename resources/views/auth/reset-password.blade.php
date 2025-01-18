<<<<<<< HEAD
<x-Layouts.AuthLayout>
    <x-Layouts.FormAuthContainer title="Reset Pasword">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <x-Fragments.Form.FormGroup :message="$errors->get('email')" label="__('Email')" for="email">
                <x-Elements.Form.Input id="email" type="email" name="email" value="old('email', $request->email)" required autofocus autocomplete="username" />
            </x-Fragments.Form.FormGroup>
            <x-Fragments.Form.FormGroup :message="$errors->get('password')" label="__('Password')" for="password">
                <x-Elements.Form.Input id="password" type="password" name="password" required autocomplete="new-password" />
            </x-Fragments.Form.FormGroup>
            <x-Fragments.Form.FormGroup :message="$errors->get('password_confirmation')" label="__('Confirm Password')" for="password_confirmation">
                <x-Elements.Form.Input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </x-Fragments.Form.FormGroup>
            <x-Elements.Form.ButtonSubmit>
                {{ __('Reset Password') }}
            </x-Elements.Form.ButtonSubmit> 
        </form>
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
=======
@extends('layouts.guest')

@section('content')
<div class="col-8 col-md-6 m-auto pt-4">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
