<<<<<<< HEAD
<x-Layouts.AuthLayout >
    <x-Layouts.FormAuthContainer title="Verifikasi Email">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
            <div class="mt-4 flex flex-row items-center justify-center">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-Elements.ButtonSubmit>
                        {{ __('Resend Verification Email') }}
                    </x-Elements.ButtonSubmit>        
                    
                </form>
        
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-Elements.ButtonSubmit>
                        {{ __('Log Out') }}
                    </x-Elements.ButtonSubmit>
                </form>
            </div>
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
=======
@extends('layouts.guest')

@section('content')
<div class="col-8 col-md-6 m-auto pt-4">
    <div class="mb-4 text-muted">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="container mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
