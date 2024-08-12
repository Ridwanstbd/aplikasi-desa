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
                    <x-input type="text" label="Url Marketplace" name="marketplace_url" placeholder="contoh: https://shopee.co.id/xxxxxx" />
                </div>
                <x-input type="hidden" label="id shop" name="shop_id" placeholder="##" value="{{$shop->first()->id}}" hidden/>

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
        <table class="table" id="mpTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Link Marketplace</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($MarketplaceLinks as $index => $mp)
                <tr>
                    <td>{{ $index + 1 }}</td>
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
                                    <x-input type="text" label="Nama Marketplace" name="name" value="{{ $mp->name }}" required=true />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Marketplace" name="marketplace_url" value="{{ $mp->name }}" required=true />
                                </div>                                            <div class="modal-footer">
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
@push('scripts')
    <script>
        let table = new DataTable('#mpTable');
    </script>
@endpush
