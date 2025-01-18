@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('content')
<section class="section">
<div class="pagetitle">
    <h2>Produk</h2>
        <x-breadcrumbs :links="[
            ['url' => route('products.index'), 'label' => 'Daftar Produk'],
        ]"/>
    </div><!-- End Page Title -->
    <div class="row">
        <div class="col-lg-12">
            <x-card title="Daftar Produk" :anchor="[
                'href' => route('products.create'),
                'label' => 'Tambah Produk',
                'class' => 'btn btn-sm btn-primary rounded-pill',
                ]">
                <!-- Tabel Produk -->
                 <x-table-product :products="$products"/>
                <!-- Tabel-Produk -->

            </x-card>

        </div>
    </div>
</section>
@endsection
@push('scripts')
    <script>
        let table = new DataTable('#productTable');
    </script>
@endpush
