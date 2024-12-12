@extends('layouts.guest')

@section('title', 'Data Live Konsultation ')

@section('content')
<div class="min-vh-100 bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Voucher Information Card --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h2 mb-0">Formulir Registrasi Live Konsultasi</h1>
                        </div>
                    </div>
                </div>

                {{-- Claim Form --}}
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-4">Isi Formulir dibawah</h2>
                        <form action="{{route('live.konsul.store')}}" method="POST">
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
                                <x-input type="textarea" placeholder="Isi Deskripsi" name="address" label="Alamat" required=true></x-input>
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

                            <div class="mb-3">
                                <x-input type="text" label="Nama Kandang" name="name_kandang" placeholder="isi nama farm" required=true />
                            </div>

                            <div class="mb-3">
                                <x-input type="text" label="Jenis Hewan Ternak" name="jenis_hewan" placeholder="Kambing / Sapi" required=true />
                            </div>

                            <div class="mb-3">
                                <x-input type="textarea" placeholder="Eartag, Kolam Silase" name="data_pembelian" label="Barang Yang Pernah Di Beli di Eleanor Farm" required=true></x-input>
                            </div>

                            <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                                Kirim Formulir
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
