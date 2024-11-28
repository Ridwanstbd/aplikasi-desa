@extends('layouts.app')

@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Kategori Product</h2>
    </div><!-- End Page Title -->
    <x-card title="Daftar Kategori" :button="['type' => 'button', 'class' => 'btn btn-sm btn-primary rounded-pill', 'dataBsToggle' => 'modal', 'dataBsTarget' => '#buatKategori', 'label' => 'Tambah Kategori']">
        <x-modal id="buatKategori" title="Tambah Kategori">
            <x-form class="" action="{{ route('categories.create') }}" method="POST">
                @csrf
                <div class="col-md-12">
                    <x-input type="text" label="Nama Kategori" name="name" required=true  placeholder="Obat Ternak" />
                </div>
                <div class="col-md-12">
                    <x-input type="textarea" label="Deskripsi" name="description" placeholder="(opsional)" />
                </div>
                <div class="modal-footer">
                    <x-button type="button" label="Batal" class="btn-secondary" dataBsDismiss="modal" />
                    <x-button type="submit" label="Tambah Kategori" class="btn-primary"/>
                </div>
            </x-form>
        </x-modal>

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

        <!-- Table with stripped rows -->
        <table class="table" id="categoryTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Ubah Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-info rounded-pill" label="Ubah" dataBsToggle="modal" dataBsTarget="#ubahKategoriModal{{ $category->id }}"/>
                             <x-modal id="ubahKategoriModal{{ $category->id }}" title="{{ $category->name }}">
                                <form action="{{ route('categories.update', $category->id) }}" method="POST" >
                                    @csrf
                                    @method('PUT')
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Kategori" name="name" value="{{ $category->name }}" required=true />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="textarea" label="Deskripsi" name="description" value="{{ $category->description ?: 'Tidak Ada Deskripsi Kategori' }}" />
                                </div>                                            <div class="modal-footer">
                                    <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Batalkan"/>
                                    <x-button type="submit" class="btn-primary" label="Simpan"/>
                                </div>
                                </form>
                             </x-modal><!-- End Ubah Kategori Modal-->
                            <!-- Hapus Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusKategoriModal{{ $category->id }}" label="Hapus" />
                             <x-modal id="hapusKategoriModal{{ $category->id }}" title="{{ $category->name }}">
                             <p>Yakin ingin menghapus Kategori ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" class="btn btn-danger" label="Hapus"/>
                                </form>
                            </div>
                            </x-modal>
                            <!-- End Hapus Kategori Modal-->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</section>
@endsection
@push('scripts')
    <script>
        let table = new DataTable('#categoryTable');
    </script>
@endpush
