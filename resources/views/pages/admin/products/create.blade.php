@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Produk</h2>
        <x-breadcrumbs :links="[
            ['url' => route('products.index'), 'label' => 'Daftar Produk'],
            ['url' => route('products.create'), 'label' => 'Tambah Produk'],
        ]" />
    </div><!-- End Page Title -->

    <x-form class="row g-3" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
            <!-- Multi Columns Form -->
            <div class="row">
                <div class="col-md-6">
                    <x-input type="text" label="Judul Produk" name="name" placeholder="Nama Barang" required=true />
                </div>
                <div class="col-md-6">
                    @php
                        $selectedCategory = $product->category_id ?? null;
                    @endphp
                    <x-select name="category_id" label="Pilih Kategori" :options="$categories"
                        :selected="$selectedCategory" required=true />
                </div>
            </div>
            <div class="col-md-12">
                <x-input type="textarea" placeholder="Isi Deskripsi" name="description" label="Deskripsi Produk"
                    required=true />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-input-image name="main_image" placeholder="Pilih Foto" label="Foto Sampul" required=true />
                </div>
                <div class="col-md-6">
                    <x-input-image name="images" label="Foto Tambahan" multiple=true />
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <!-- Konten tambahan jika diperlukan -->
                </div>
                <div class="col-md-6">
                    <x-input type="text" label="Form Order" name="url_form"
                        placeholder="Link Form Order Produk (Opsional)" />
                </div>
            </div>

            <input type="checkbox" class="form-check-input" name="has_variations" id="has_variations" >
            <label for="has_variations" class="form-check-label">Apakah produk memiliki variasi?</label>
            <!-- Produk Tidak bervariasi -->
            <div id="no_variation">
                <div class="row">
                    <div class="col-md-6">
                        <x-input type="number" name="price" placeholder="7999" label="Harga" required=true />
                    </div>
                    <div class="col-md-6">
                        <x-input type="text" name="sku" label="SKU" placeholder="A0001" required=true />
                    </div>
                </div>
                <div class="my-2">
                    <x-checkbox name="is_ready" label="Produk ini siap dijual" :checked=false />
                </div>
            </div><!-- End Tidak bervariasi -->
            <!-- Produk Bervariasi -->
            <div id="variations" style="display:none;">
                <h5 class="m-4">Variasi Produk</h5>
                @foreach ($variations as $index => $variation)
                    <div class="variation d-grid">
                        <div class="row">
                            <div class="col col-md-6">
                                <x-input type="text" name="variations[{{ $index }}][name_variation]" label="Nama Variasi"
                                    required=true placeholder="Merah, XL" :value="$variation['name'] ?? ''" />
                            </div>
                            <div class="col col-md-6">
                                <x-input type="number" name="variations[{{ $index }}][price]" label="Harga" required=true
                                    placeholder="7999" :value="$variation['price'] ?? ''" />
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col col-md-6">
                                <x-input type="text" name="variations[{{ $index }}][sku]" label="SKU" required=true
                                    placeholder="A0001" :value="$variation['sku'] ?? ''" />
                            </div>
                            <div class="col col-md-6">
                                <x-input-image name="variations[{{ $index }}][image]" label="Foto Variasi" />
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col col-md-6">
                                <x-checkbox label="Bisa dipesan" name="variations[{{ $index }}][is_ready]" :checked=false />
                            </div>
                        </div>
                        <div class="text-start">
                            <button class="btn btn-danger my-2 remove_variation"
                            onclick="removeVariation(this)">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End Produk Bervariasi -->

            <div class="text-start">
                <x-button type="button" label="Tambah Variasi" id="add_variation" class="btn-info my-2"
                    style="display: none;" />
                <div class="d-grid mx-auto">
                    <x-button type="submit" label="Simpan" />
                </div>
            </div>
        </x-card>
    </x-form>
</section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var hiddenInput = this.previousElementSibling;
                    if (this.checked) {
                        hiddenInput.value = '1';
                    } else {
                        hiddenInput.value = '0';
                    }
                });
            });

            document.getElementById('has_variations').addEventListener('change', function () {
                var variations = document.querySelectorAll('#variations input');
                var noVariationInputs = document.querySelectorAll('#no_variation input');
                if (this.checked) {
                    document.getElementById('no_variation').style.display = 'none';
                    noVariationInputs.forEach(function (input) {
                        input.disabled = true;
                    });
                    document.getElementById('variations').style.display = 'block';
                    document.getElementById('add_variation').style.display = 'block';
                    variations.forEach(function (input) {
                        input.disabled = false;
                    });
                } else {
                    document.getElementById('no_variation').style.display = 'block';
                    noVariationInputs.forEach(function (input) {
                        input.disabled = false;
                    });
                    document.getElementById('variations').style.display = 'none';
                    document.getElementById('add_variation').style.display = 'none';
                    variations.forEach(function (input) {
                        input.disabled = true;
                    });
                }
            });

            document.getElementById('add_variation').addEventListener('click', function () {
                var variationCount = document.querySelectorAll('.variation').length;
                var newVariation = document.querySelector('.variation').cloneNode(true);
                newVariation.querySelectorAll('input').forEach(function (input) {
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


            function toggleRemoveButtons() {
                var variations = document.querySelectorAll('.variation');
                document.querySelectorAll('.remove_variation').forEach(function (button) {
                    button.disabled = variations.length === 1;
                });
            }

            document.getElementById('has_variations').dispatchEvent(new Event('change'));
        });
        function removeVariation(button) {
            if (document.querySelectorAll('.variation').length > 1) {
                button.closest('.variation').remove();
                toggleRemoveButtons();
            }
        }
    </script>
@endpush
