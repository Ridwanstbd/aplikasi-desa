@extends('layouts.app')
@section('title', 'Daftar Blog')
@section('content')
<section class="section">
<div class="pagetitle">
    <h2>Blog</h2>
        <x-breadcrumbs :links="[
            ['url' => route('admin.blog.index'), 'label' => 'Daftar Blog'],
        ]"/>
    </div><!-- End Page Title -->
    <div class="row">
        <div class="col-lg-12">
            <x-card title="Daftar Blog" :anchor="[
                'href' => route('admin.blog.create'),
                'label' => 'Tambah Blog',
                'class' => 'btn btn-sm btn-primary rounded-pill',
                ]">
                <!-- Tabel Blog -->
                <table class="table" id="tagTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $index => $post)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Ubah Blog Modal -->
                                    <x-anchor class="btn btn-sm btn-info rounded-pill" label="Ubah" href="{{ route('admin.blog.edit', $post->id) }}" />
                                    <!-- End Ubah Blog Modal-->
                                    <!-- Hapus Blog Modal -->
                                    <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusTagModal{{ $post->id }}" label="Hapus" />
                                    <x-modal id="hapusTagModal{{ $post->id }}" title="{{ $post->name }}">
                                    <p>Yakin ingin menghapus Blog ini?</p>
                                    <div class="modal-footer">
                                        <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                        <form action="{{ route('admin.blog.delete', $post->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" class="btn btn-danger" label="Hapus"/>
                                        </form>
                                    </div>
                                    </x-modal>
                                    <!-- End Hapus Blog Modal-->
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Tabel-Blog -->
            </x-card>
        </div>
    </div>
</section>
@endsection
@push('scripts')
    <script>
        let table = new DataTable('#blogTable');
    </script>
@endpush
