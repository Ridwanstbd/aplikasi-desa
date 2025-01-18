@extends('layouts.guest')

@section('title', 'Claim Voucher - ' . $voucher->title)

@section('content')
<div class="min-vh-100 bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Voucher Information Card --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h2 mb-0">{{ $voucher->title }}</h1>
                        </div>

                        @if($voucher->description)
                            <div class="mt-3">
                                <p class="text-muted">{{ $voucher->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Claim Form --}}
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-4">Klaim Voucher</h2>
                        <form action="{{ route('vouchers.claim', $voucher->slug) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user_name" class="form-label">Nama Lengkap</label>
                                <input type="text"
                                       class="form-control"
                                       name="user_name"
                                       id="user_name"
                                       value="{{ old('user_name') }}"
                                       required
                                       placeholder="Masukkan nama lengkap Anda">
                            </div>

                            <div class="mb-3">
                                <label for="user_whatsapp" class="form-label">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel"
                                           class="form-control"
                                           name="user_whatsapp"
                                           id="user_whatsapp"
                                           value="{{ old('user_whatsapp') }}"
                                           required
                                           placeholder="8xxxxxxxxxx">
                                </div>
                                <div class="form-text">Contoh: 81234567890 (tanpa angka 0 di depan)</div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                                Klaim Voucher via WhatsApp
                                <svg class="ms-2" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('user_whatsapp').addEventListener('input', function(e) {
    // Remove any non-digit characters
    let value = e.target.value.replace(/\D/g, '');

    // Remove leading zero if present
    if (value.startsWith('0')) {
        value = value.substring(1);
    }

    // Update the input value
    e.target.value = value;
});
</script>
@endpush
@endsection
