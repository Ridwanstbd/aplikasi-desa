@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<section class="section">
    <div class="pagetitle">
    <h2>Produk</h2>
        <x-breadcrumbs :links="[
            ['url' => route('products.index'), 'label' => 'Daftar Produk'],
            ['url' => route('products.edit', $product->slug), 'label' => 'Edit Produk'],
        ]"/>
    </div><!-- End Page Title -->

    <x-card title="Informasi Produk">
        @if ($errors->any())
            <div class="container">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Informasi Produk -->
        <form class="row g-3" action="{{ route('products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <x-input type="text" label="Judul Produk" name="name" value="{{ $product->name }}" placeholder="Nama Barang" required=true />
            </div>
            <div class="col-md-6">
                @php
                    $selectedCategory = $product->category_id ?? null;
                @endphp
                <x-select name="category_id" label="Pilih Kategori" :options="$categories" :selected="$selectedCategory" required=true />
            </div>
            <div class="col-md-12">
                <x-input type="textarea" placeholder="Isi Deskripsi" value="{{ $product->description }}" name="description" label="Deskripsi Produk" required=true></x-input>
            </div>
            <div class="col-md-6">
                <x-input type="file" name="main_image" placeholder="Pilih Foto" label="Foto Sampul" />
                <div class="d-flex mb-2">
                @if ($product->main_image)
                    <x-image-preview :image="$product->main_image"/>
                @endif
                </div>
            </div>
            <div class="col-md-6">
                <x-input type="file" name="images[]" label="Foto Tambahan" multiple=true />
                <div class="d-flex mb-2">
                    @foreach ($product->images as $image)
                    <div class="position-relative m-2">
                        <img src="{{ Storage::url($image->images) }}" style="width: 10rem; height: 10rem;" class="rounded mt-3">
                        <button class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                                <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <x-input type="text" label="Form Order" name="url_form" placeholder="Link Form Order Produk (Opsional)" value="{{$product->url_form}}" />
            </div>
            <div class="d-grid mx-auto">
              <x-button type="submit" label="Simpan Informasi"/>
            </div>
        </form>

        <!-- Form Variasi Produk -->
        <form action="{{ route('products.update.variations', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <x-variation :variations="$product->variations" :disabled="false" style="display: block;" />

            <div class="text-start">
                <x-button type="button" label="Tambah Variasi" id="add_variation" class="btn-info my-2" />
            </div>
            <div class="d-grid mx-auto">
                <x-button type="submit" label="Simpan Variasi"/>
            </div>
        </form>
    </x-card>
</section>
@endsection

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
    $(document).on('click', '.delete-image', function(e) {
        e.preventDefault();
        var imageId = $(this).data('id');
        var url = '/dashboard/product/delete-image/' + imageId;

        if (confirm('Apakah Anda yakin ingin menghapus Foto ini?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    showAlert('success','Sukses!','Foto berhasil dihapus.');
                    location.reload();
                },
                error: function(xhr) {
                    showAlert('error','Oops...','Terjadi kesalahan saat menghapus foto.');
                }
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var hiddenInput = this.previousElementSibling;
                if (this.checked) {
                    hiddenInput.value = '1';
                } else {
                    hiddenInput.value = '0';
                }
            });
        });

        document.getElementById('has_variations').addEventListener('change', function() {
            var variations = document.querySelectorAll('#variations input');
            var noVariationInputs = document.querySelectorAll('#no_variation input');
            if (this.checked) {
                document.getElementById('no_variation').style.display = 'none';
                noVariationInputs.forEach(function(input) {
                    input.disabled = true;
                });
                document.getElementById('variations').style.display = 'block';
                document.getElementById('add_variation').style.display = 'block';
                variations.forEach(function(input) {
                    input.disabled = false;
                });
                document.querySelectorAll('.remove_variation').forEach(function(button) {
                    button.disabled = document.querySelectorAll('.variation').length === 1;
                });
            } else {
                document.getElementById('no_variation').style.display = 'block';
                noVariationInputs.forEach(function(input) {
                    input.disabled = false;
                });
                document.getElementById('variations').style.display = 'none';
                document.getElementById('add_variation').style.display = 'none';
                variations.forEach(function(input) {
                    input.disabled = true;
                });
                document.querySelectorAll('.remove_variation').forEach(function(button) {
                    button.disabled = true;
                });
            }
        });

        document.getElementById('add_variation').addEventListener('click', function() {
            var variationCount = document.querySelectorAll('.variation').length;
            var newVariation = document.querySelector('.variation').cloneNode(true);
            newVariation.querySelectorAll('input').forEach(function(input) {
                input.name = input.name.replace(/\d+/, variationCount);
                input.id = input.id.replace(/\d+/, variationCount);

                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.type === 'hidden') {
                    input.value = '0';
                } else {
                    input.value = '';
                }
                input.disabled = false;
            });
            newVariation.querySelector('.remove_variation').disabled = false;
            document.getElementById('variations').appendChild(newVariation);
            toggleRemoveButtons();
        });

        function removeVariation(button) {
            if (document.querySelectorAll('.variation').length > 1) {
                button.closest('.variation').remove();
                toggleRemoveButtons();
            }
        }

        function toggleRemoveButtons() {
            var variations = document.querySelectorAll('.variation');
            document.querySelectorAll('.remove_variation').forEach(function(button) {
                button.disabled = variations.length === 1;
            });
        }
    });
</script>
@endpush
