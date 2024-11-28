@extends('layouts.app')
@section('title', 'Tambah Blog')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Blog</h2>
        <x-breadcrumbs :links="[
            ['url' => route('admin.blog.index'), 'label' => 'Daftar Blog'],
            ['url' => route('admin.blog.create'), 'label' => 'Tambah Blog'],
        ]" />
    </div><!-- End Page Title -->

    <x-form class="row g-3" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <x-card title="Buat Blog">
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
            <x-input type="hidden" name="author_id" value="{{ auth()->id() }}" />
            <div class="row">
                <div class="col-md-6">
                    <x-input type="text" label="Judul Blog" name="title" placeholder="5 Cara Menyimpan Pakan Silase" required=true />
                </div>
                <div class="col-md-6">
                    @php
                        $selectedCategory = $post->category_id ?? null;
                    @endphp
                    <x-select name="category_id" label="Pilih Kategori" :options="$categories"
                        :selected="$selectedCategory" required=true />
                </div>
            </div>
            <div class="col-12">
            <input 
                type="file" 
                id="featured_image" 
                name="featured_image" 
                accept="image/*" 
                class="form-control focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                onchange = previewImage(event)
                >                    
                        <img 
                            id="imagePreview" 
                            src="#" 
                            alt="Preview" 
                            class="mt-2 max-w-xs h-auto hidden"
                            style="max-height: 200px;"
                        >
            </div>
            <div class="col-md-12">
                <label class="block mb-2">Konten</label>
                    <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                <trix-editor input="content"></trix-editor>
            </div>
            <div class="col-md-12">
                <x-input type="textarea" placeholder="isi ringkasan" name="excerpt" label="Ringkasan" />
                <p class="text-sm text-gray-500 mt-1">*Jika dikosongkan, ringkasan akan dibuat otomatis dari konten</*Jika>
            </div>
            <!-- Status dan Tanggal Publikasi -->
            <div class="row">
                <div class="col-md-6">
                    <x-select name="status" label="Status" :options="[
                        'draft' => 'draft',
                        'published' => 'published'
                    ]" required=true />
                </div>
                <div class="col-md-6" id="published_at_container">
                    <x-input type="datetime-local" name="published_at" 
                        label="Tanggal Publikasi" 
                        value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" />
                </div>
            </div>

            <div class="col-md-12">
            <label for="tags" class="form-label">Tag</label>
                <select 
                    id="tags" 
                    name="tags[]" 
                    class="form-control" 
                    multiple="multiple">
                    @if (old('tags'))
                        @foreach (old('tags') as $tag)
                            <option value="{{ $tag }}" selected>{{ $tag }}</option>
                        @endforeach
                    @endif
                </select>
                <small class="text-muted">Isi dengan beberapa tag, pisahkan dengan koma</small>
            </div>
            <div class="d-grid mx-auto">
                    <x-button type="submit" label="Simpan" />
                </div>
        </x-card>
    </x-form>
</section>
@endsection
@push('scripts')
    <script>
    $(document).ready(function() {
        $('#tags').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Tambahkan tag",
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="status"]');
        const publishedAtContainer = document.getElementById('published_at_container');
        
        // Initial check
        togglePublishedAt();
        
        // Add event listener for changes
        statusSelect.addEventListener('change', togglePublishedAt);
        
        function togglePublishedAt() {
            if (statusSelect.value === 'published') {
                publishedAtContainer.style.display = 'block';
            } else {
                publishedAtContainer.style.display = 'none';
            }
        }
    });
    document.addEventListener("trix-attachment-add", function(event) {
        const attachment = event.attachment;

        if (attachment.file) {
            const formData = new FormData();
            formData.append("file", attachment.file);

            fetch("{{ route('trix.upload') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                attachment.setAttributes({ url: data.url });
            })
            .catch(error => {
                console.error("Error uploading image:", error);
            });
        }
    });
    function previewImage(event) {
        var reader = new FileReader();
        var output = document.getElementById('imagePreview');

        reader.onload = function() {
            output.src = reader.result;
            output.classList.remove('hidden'); // Menampilkan gambar
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
    </script>
@endpush
