@extends('layouts.app')
@section('title', 'Edit Blog')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Blog</h2>
        <x-breadcrumbs :links="[
            ['url' => route('admin.blog.index'), 'label' => 'Daftar Blog'],
            ['url' => '#', 'label' => 'Edit Blog'],
        ]" />
    </div><!-- End Page Title -->

    <x-form class="row g-3" action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <x-card title="Edit Blog">
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
                    <x-input 
                        type="text" 
                        label="Judul Blog" 
                        name="title" 
                        :value="$post->title"
                        placeholder="5 Cara Menyimpan Pakan Silase" 
                        required=true 
                    />
                </div>
                <div class="col-md-6">
                    <x-select 
                        name="category_id" 
                        label="Pilih Kategori" 
                        :options="$categories"
                        :selected="$post->category_id" 
                        required=true 
                    />
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
                    <div class="mt-2">
            @if($post->featured_image)
                <img 
                    id="imagePreview" 
                    src="{{ Storage::url($post->featured_image)}}" 
                    alt="Preview" 
                    class="mt-2 max-w-xs h-auto"
                    style="max-height: 200px;"
                >
            @else
                <img 
                    id="imagePreview" 
                    src="#" 
                    alt="Preview" 
                    class="mt-2 max-w-xs h-auto hidden"
                    style="max-height: 200px;"
                >
            @endif
        </div>
            </div>
            <div class="col-md-12">
                <label class="block mb-2">Konten</label>
                <input id="content" type="hidden" name="content" value="{{ old('content', $post->content) }}">
                <trix-editor input="content"></trix-editor>
            </div>
            <div class="col-md-12">
                <x-input 
                    type="textarea" 
                    placeholder="isi ringkasan" 
                    name="excerpt" 
                    label="Ringkasan"
                    :value="$post->excerpt"
                />
                <p class="text-sm text-gray-500 mt-1">
                    *Jika dikosongkan, ringkasan akan dibuat otomatis dari konten
                </p>
            </div>

            <!-- Status dan Tanggal Publikasi -->
            <div class="row">
                <div class="col-md-6">
                    <x-select 
                        name="status" 
                        label="Status" 
                        :options="[
                            'draft' => 'draft',
                            'published' => 'published'
                        ]" 
                        :selected="$post->status"
                        required=true 
                    />
                </div>
                <div class="col-md-6" id="published_at_container" style="display: none;">
                <x-input 
                    type="datetime-local" 
                    name="published_at" 
                    label="Tanggal Publikasi" 
                    :value="optional($post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at) : now())->format('Y-m-d\TH:i')"
                />
                </div>
            </div>

            <div class="col-md-12">
            <label for="tags" class="form-label">Tag</label>
            <select id="tags" name="tags[]" class="form-control" multiple="multiple">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->name }}"
                        @if ($post->tags->contains('name', $tag->name)) selected @endif>
                        {{ $tag->name }}
                    </option>
                @endforeach
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
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        const publishedAtContainer = document.getElementById('published_at_container');
        if (this.value === 'published') {
            publishedAtContainer.style.display = 'block';
        } else {
            publishedAtContainer.style.display = 'none';
        }
    });

    // Show/hide published_at field on page load
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="status"]');
        const publishedAtContainer = document.getElementById('published_at_container');
        if (statusSelect.value === 'published') {
            publishedAtContainer.style.display = 'block';
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
            output.classList.remove('hidden'); 
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush