{{-- whatsapp-setup.blade.php --}}
@extends('layouts.app')
@section('content')
<h1>Setup Bot Whatsapp</h1>
<div class="card-body">
    <div id="status-container" class="text-center">
        <div id="qr-container" class="mb-4">
            <!-- QR code akan ditampilkan di sini -->
        </div>
        <div id="status-message" class="mb-3">
            Memuat status WhatsApp...
        </div>
        <div class="alert alert-info">
            <p class="mb-0">Petunjuk:</p>
            <ol class="text-start mb-0">
                <li>Buka WhatsApp di ponsel Anda</li>
                <li>Ketuk Menu atau Setelan dan pilih WhatsApp Web</li>
                <li>Scan QR code yang muncul di layar</li>
            </ol>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function checkStatus() {
        fetch('{{ route("admin.whatsapp.status") }}')
            .then(response => response.json())
            .then(data => {
                const statusMessage = document.getElementById('status-message');
                const qrContainer = document.getElementById('qr-container');

                if (data.status === 'READY') {
                    statusMessage.innerHTML = '<div class="">WhatsApp sudah terhubung!</div>';
                    qrContainer.innerHTML = '';
                } else {
                    statusMessage.innerHTML = '<div class="">Menunggu scan QR Code...</div>';
                    if (data.qr) {
                        // Hapus QR sebelumnya
                        qrContainer.innerHTML = '';

                        // Inisialisasi QR Code
                        try {
                            const qrCode = new QRCode(qrContainer, {
                                text: data.qr,
                                width: 256,
                                height: 256
                            });
                        } catch (error) {
                            console.error("Error initializing QRCode:", error);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('status-message').innerHTML = 
                    '<div class="">Gagal terhubung ke server WhatsApp</div>';
            });
    }
    // Check status setiap 5 detik
    checkStatus();
    setInterval(checkStatus, 5000);
</script>
@endpush
