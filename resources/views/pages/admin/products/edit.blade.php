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

    function initializeVariationHandlers() {
        const addVariationBtn = document.getElementById('add_variation');
        const hasVariationsCheckbox = document.getElementById('has_variations');

        if (addVariationBtn) {
            addVariationBtn.addEventListener('click', function() {
                const variationsContainer = document.getElementById('variations');
                if (!variationsContainer) return;

                const variations = document.querySelectorAll('.variation');
                const variationCount = variations.length;
                const firstVariation = variations[0];

                if (firstVariation) {
                    const newVariation = firstVariation.cloneNode(true);

                    // Reset and update inputs
                    newVariation.querySelectorAll('input').forEach(function(input) {
                        const newIndex = variationCount;
                        input.name = input.name.replace(/\d+/, newIndex);
                        input.id = input.id.replace(/\d+/, newIndex);

                        if (input.type === 'checkbox') {
                            input.checked = false;
                        } else if (input.type === 'hidden') {
                            input.value = '0';
                        } else {
                            input.value = '';
                        }
                        input.disabled = false;
                    });

                    const removeButton = newVariation.querySelector('.remove_variation');
                    if (removeButton) {
                        removeButton.disabled = false;
                    }

                    variationsContainer.appendChild(newVariation);
                    toggleRemoveButtons();
                }
            });
        }

        if (hasVariationsCheckbox) {
            hasVariationsCheckbox.addEventListener('change', function() {
                const variations = document.querySelectorAll('#variations input');
                const noVariationInputs = document.querySelectorAll('#no_variation input');
                const variationsSection = document.getElementById('variations');
                const addVariationBtn = document.getElementById('add_variation');
                const noVariationSection = document.getElementById('no_variation');

                if (this.checked) {
                    if (noVariationSection) noVariationSection.style.display = 'none';
                    noVariationInputs.forEach(input => input.disabled = true);

                    if (variationsSection) variationsSection.style.display = 'block';
                    if (addVariationBtn) addVariationBtn.style.display = 'block';
                    variations.forEach(input => input.disabled = false);
                } else {
                    if (noVariationSection) noVariationSection.style.display = 'block';
                    noVariationInputs.forEach(input => input.disabled = false);

                    if (variationsSection) variationsSection.style.display = 'none';
                    if (addVariationBtn) addVariationBtn.style.display = 'none';
                    variations.forEach(input => input.disabled = true);
                }

                toggleRemoveButtons();
            });
        }

        // Initialize checkbox handlers
        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const hiddenInput = this.previousElementSibling;
                if (hiddenInput) {
                    hiddenInput.value = this.checked ? '1' : '0';
                }
            });
        });
    }

    function toggleRemoveButtons() {
        const variations = document.querySelectorAll('.variation');
        const removeButtons = document.querySelectorAll('.remove_variation');

        removeButtons.forEach(button => {
            button.disabled = variations.length <= 1;
        });
    }

    function removeVariation(button) {
        const variations = document.querySelectorAll('.variation');
        if (variations.length > 1) {
            button.closest('.variation').remove();
            toggleRemoveButtons();
        }
    }

    // Delete image handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-image')) {
            e.preventDefault();
            const button = e.target.closest('.delete-image');
            const imageId = button.dataset.id;
            const url = '/dashboard/product/delete-image/' + imageId;

            if (confirm('Apakah Anda yakin ingin menghapus Foto ini?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showAlert('success', 'Sukses!', 'Foto berhasil dihapus.');
                    location.reload();
                })
                .catch(error => {
                    showAlert('error', 'Oops...', 'Terjadi kesalahan saat menghapus foto.');
                });
            }
        }
    });

    // Initialize all handlers when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeVariationHandlers();

        // Show alerts for session messages
        @if (session('success'))
            showAlert('success', 'Sukses!', '{{ session('success') }}');
        @endif

        @if (session('error'))
            @php $errorMessages = session('error'); @endphp
            @if (is_array($errorMessages))
                @foreach ($errorMessages as $errorMessage)
                    showAlert('error', 'Oops...', '{{ $errorMessage }}');
                @endforeach
            @else
                showAlert('error', 'Oops...', '{{ $errorMessages }}');
            @endif
        @endif
    });
</script>
@endpush
