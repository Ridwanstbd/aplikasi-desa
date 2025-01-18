<<<<<<< HEAD
<x-Layouts.AuthLayout>
    <x-Layouts.FormAuthContainer title="Lupa Password">
        <div class="text-md text-gray-600">
            {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan email berisi tautan pengaturan ulang kata sandi sehingga Anda dapat memilih yang baru.') }}
        </div>
        <!-- Session Status -->
        <x-Elements.AuthSessionStatus class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-Fragments.Form.FormGroup :message="$errors->get('email')" label="Email" for="email">
                <x-Elements.Form.Input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </x-Fragments.Form.FormGroup>
               
        </form>
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
=======
@extends('layouts.guest')

@section('content')
<div class="col-8 col-md-6 m-auto pt-4">
    <div class="mb-4 text-muted">
        {{ __('Lupa kata sandi? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk menyetel ulang kata sandi melalui email yang akan memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="container mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</div>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
