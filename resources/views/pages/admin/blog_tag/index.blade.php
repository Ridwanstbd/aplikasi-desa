@extends('layouts.app')

@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Tag</h2>
    </div><!-- End Page Title -->
    <x-card title="Daftar Tag" :button="['type' => 'button', 'class' => 'btn btn-sm btn-primary rounded-pill', 'dataBsToggle' => 'modal', 'dataBsTarget' => '#buatTag', 'label' => 'Tambah Tag']">
        <x-modal id="buatTag" title="Tambah Tag">
            <x-form class="" action="{{ route('admin.blog-tag.store') }}" method="POST">
                @csrf
                <div class="col-md-12">
                    <x-input type="text" label="Nama Tag" name="name" required=true  placeholder="pakan" />
                </div>
                <div class="modal-footer">
                    <x-button type="button" label="Batal" class="btn-secondary" dataBsDismiss="modal" />
                    <x-button type="submit" label="Tambah Tag" class="btn-primary"/>
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
        <table class="table" id="tagTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tag</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $index => $tag)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Ubah Tag Modal -->
                             <x-button type="button" class="btn-sm btn-info rounded-pill" label="Ubah" dataBsToggle="modal" dataBsTarget="#ubahTagModal{{ $tag->id }}"/>
                             <x-modal id="ubahTagModal{{ $tag->id }}" title="{{ $tag->name }}">
                                <form action="{{ route('admin.blog-tag.update', $tag->id) }}" method="POST" >
                                    @csrf
                                    @method('PUT')
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Tag" name="name" value="{{ $tag->name }}" required=true />
                                </div>                                            <div class="modal-footer">
                                    <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Batalkan"/>
                                    <x-button type="submit" class="btn-primary" label="Simpan"/>
                                </div>
                                </form>
                             </x-modal><!-- End Ubah Tag Modal-->
                            <!-- Hapus Tag Modal -->
                             <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusTagModal{{ $tag->id }}" label="Hapus" />
                             <x-modal id="hapusTagModal{{ $tag->id }}" title="{{ $tag->name }}">
                             <p>Yakin ingin menghapus Tag ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('admin.blog-tag.delete', $tag->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" class="btn btn-danger" label="Hapus"/>
                                </form>
                            </div>
                            </x-modal>
                            <!-- End Hapus Tag Modal-->
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
        let table = new DataTable('#tagTable');
    </script>
@endpush
