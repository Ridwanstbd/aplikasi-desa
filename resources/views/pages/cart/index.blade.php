@extends('layouts.guest')

@section('content')
<div class="container mt-5 field-content">
<x-breadcrumbs :links="[
        ['url' => route('home'), 'label' => 'Semua Produk'],
        ['url' => route('cart.index'), 'label' => 'Lihat Keranjang'],
    ]"/>
    <h2 class="mb-4">Keranjang Belanja</h2>
    @if(empty($cart))
        <p>Keranjang Anda kosong.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Produk</th>
                    <th scope="col">Variasi</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Total</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBelanja = 0; @endphp
                @foreach($cart as $item)
                    @php
                        $totalHarga = $item['price'] * $item['quantity'];
                        $totalBelanja += $totalHarga;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['name_variation'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <div class="fs-5 btn-decrement" data-variation-id="{{ $item['variation_id'] }}"><i class="bi bi-dash-circle"></i></div>
                                <input type="number" class="form-control quantity-input" style="width: 3rem;" data-variation-id="{{ $item['variation_id'] }}" value="{{ $item['quantity'] }}" min="1">
                                <div class="fs-5 btn-increment" data-variation-id="{{ $item['variation_id'] }}"><i class="bi bi-plus-circle"></i></div>
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('cart.remove', $item['variation_id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="my-3 d-flex justify-content-between">
            <h4>Total Belanja: Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h4>
            <a href="{{route('cart.checkout')}}" class="btn btn-primary">Buat Pesanan</a>
        </div>
    @endif
</div>
@endsection
@push('styles')
    <style>
        .field-content {
            height: 100vh;
        }
    </style>
@endpush
@push('scripts')
<script>
    function showAlert(icon, title, text){
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'Ok'
        });
    }
    @if (session('success'))
        showAlert('success','Sukses!','{{ session('success') }}')
    @endif
    @if (session('error'))
        @php $errorMessages = session('error'); @endphp
        @if (is_array($errorMessages))
            @foreach ($errorMessages as $errorMessage)
                showAlert('error','Oops...','{{ $errorMessage }}')
            @endforeach
        @else
                showAlert('error','Oops...','{{ $errorMessages }}')
        @endif
    @endif
    document.addEventListener('DOMContentLoaded', function() {
        const updateQuantity = (variationId, quantity) => {
            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    variation_id: variationId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        };

        document.querySelectorAll('.btn-decrement').forEach(button => {
            button.addEventListener('click', function() {
                const variationId = this.getAttribute('data-variation-id');
                const quantityInput = document.querySelector(`.quantity-input[data-variation-id="${variationId}"]`);
                const newQuantity = Math.max(1, parseInt(quantityInput.value) - 1);
                quantityInput.value = newQuantity;
                updateQuantity(variationId, newQuantity);
            });
        });

        document.querySelectorAll('.btn-increment').forEach(button => {
            button.addEventListener('click', function() {
                const variationId = this.getAttribute('data-variation-id');
                const quantityInput = document.querySelector(`.quantity-input[data-variation-id="${variationId}"]`);
                const newQuantity = parseInt(quantityInput.value) + 1;
                quantityInput.value = newQuantity;
                updateQuantity(variationId, newQuantity);
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const variationId = this.getAttribute('data-variation-id');
                const newQuantity = Math.max(1, parseInt(this.value));
                this.value = newQuantity;
                updateQuantity(variationId, newQuantity);
            });
        });
    });
</script>
@endpush
