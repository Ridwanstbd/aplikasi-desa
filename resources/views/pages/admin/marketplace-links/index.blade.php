@extends('layouts.app')
@section('title', 'Daftar Marketplace')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Marketplace</h2>
    </div>
    <x-card title="Daftar Marketplace" :button="['type' => 'button', 'class' => 'btn btn-sm btn-primary rounded-pill', 'dataBsToggle' => 'modal', 'dataBsTarget' => '#buatCS', 'label' => 'Tambah Marketplace']" >
        <x-modal id="buatCS" title="Buat Marketplace">
            <x-form action="{{route('marketplace-links.create')}}">
                <div class="col-md-12">
                <select name="type" id="type" class="form-select">
                    <option value="Shopee">Shopee</option>
                    <option value="Tokopedia">Tokopedia</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Lazada">Lazada</option>
                </select>
                </div>
                <div class="col-md-12">
                    <x-input type="text" label="Nama" name="name" placeholder="Shopee Madiun" />
                </div>
                <div class="col-md-12">
                    <x-input type="text" label="Url Marketplace" name="marketplace_url" placeholder="contoh: https://shopee.co.id/xxxxxx" />
                </div>
                <input type="hidden" name="shop_id" placeholder="##" value="{{$shop->first()->id}}" hidden />

                <div class="modal-footer">
                    <x-button type="button" label="Batal" class="btn-secondary" dataBsDismiss="modal" />
                    <x-button type="submit" label="Tambah" class="btn-primary"/>
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
        <div class="d-flex align-items-center p-3 my-3 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3 " role="alert">
            <div class="me-3">
                <i class="bi bi-info-circle-fill fs-4"></i>
            </div>
            <div>
                Anda dapat mengubah urutan dengan drag and drop baris tabel
            </div>
        </div>
        <table class="table" id="mpTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Link Marketplace</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="sortable-list">
                @foreach ($MarketplaceLinks as $index => $mp)
                <tr class="sortable-item" data-id="{{ $mp->id }}">
                    <td class="handle"><i class="bi bi-grip-vertical"></i> {{ $index + 1 }}</td>
                    <td>{{ $mp->name }}</td>
                    <td>{{ $mp->marketplace_url }}</td>
                    <td>
                        <!-- ubahCSModal -->
                        <x-button class="btn-sm btn-info rounded-pill" label="Ubah" dataBsToggle="modal" dataBsTarget="#ubahCSModal{{ $mp->id }}"/>
                        <x-modal id="ubahCSModal{{ $mp->id }}" title="{{ $mp->name }}">
                                <form action="{{route('marketplace-links.update',$mp->id)}}" method="POST" >
                                    @csrf
                                    @method('PUT')
                                <div class="col-md-12">
                                <select name="type" id="type" class="form-select">
                                    <option value="Shopee" {{ $mp->type == 'Shopee' ? 'selected' : '' }}>Shopee</option>
                                    <option value="Tokopedia" {{ $mp->type == 'Tokopedia' ? 'selected' : '' }}>Tokopedia</option>
                                    <option value="Tiktok" {{ $mp->type == 'Tiktok' ? 'selected' : '' }}>Tiktok</option>
                                    <option value="Lazada" {{ $mp->type == 'Lazada' ? 'selected' : '' }}>Lazada</option>
                                </select>
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Marketplace" name="name" value="{{ $mp->name }}" required=true />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Url Marketplace" name="marketplace_url" value="{{ $mp->marketplace_url }}" required=true />
                                </div>
                                    <input type="hidden" name="shop_id" placeholder="##" value="{{$shop->first()->id}}" hidden />
                                <div class="modal-footer">
                                    <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Batalkan"/>
                                    <x-button type="submit" class="btn-primary" label="Simpan"/>
                                </div>
                                </form>
                             </x-modal>
                             <!-- ubahCSModal -->
                            <!-- hapusCSModal -->
                            <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusCSModal{{ $mp->id }}" label="Hapus" />
                             <x-modal id="hapusCSModal{{ $mp->id }}" title="{{ $mp->name }}">
                             <p>Yakin ingin menghapus marketplace ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{route('marketplace-links.destroy',$mp->id)}}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" class="btn btn-danger" label="Hapus"/>
                                </form>
                            </div>
                            </x-modal>
                            <!-- hapusCSModal -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</section>
@endsection
@push('styles')
<style>
    .sortable-item {
        cursor: move;
    }
    .sortable-ghost {
        background-color: #f8f9fa;
        opacity: 0.5;
    }
    .handle {
        cursor: grab;
    }
    .handle i {
        color: #6c757d;
        margin-right: 5px;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let table = new DataTable('#mpTable',{
            "ordering": false
        });
        const sortable = new Sortable(document.querySelector('.sortable-list'), {
        animation: 150,
        handle: '.handle',
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            const oldPosition = evt.oldIndex + 1;
            const newPosition = evt.newIndex + 1;

            // Show loading state
            Swal.fire({
                title: 'Memperbarui urutan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send to server
            fetch('/dashboard/marketplace/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    old_position: oldPosition,
                    new_position: newPosition
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Urutan berhasil diperbarui',
                        timer: 1500
                    }).then(() => {
                        // Refresh the page to get updated order
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengubah urutan: ' + error.message
                }).then(() => {
                    // Refresh the page to reset order
                    window.location.reload();
                });
            });
        }
    });
    </script>
@endpush
