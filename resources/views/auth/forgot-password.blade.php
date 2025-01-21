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
