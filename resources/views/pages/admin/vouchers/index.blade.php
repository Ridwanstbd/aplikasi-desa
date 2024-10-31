@extends('layouts.app')
@section('title', 'Daftar Voucher')
@push('scripts')
<script>
    let table = new DataTable('#VcTable');

    document.querySelector('input[name="slug"]').addEventListener('input', function(e) {
    const regex = /^[a-z0-9-]+$/;
    if (!regex.test(this.value)) {
        this.setCustomValidity('Hanya huruf kecil, angka, dan tanda strip (-) yang diperbolehkan');
    } else {
        this.setCustomValidity('');
    }
    });

    @if(session('copy_url'))
        window.addEventListener('DOMContentLoaded', (event) => {
            const textArea = document.createElement('textarea');
            textArea.value = '{{ session('copy_url') }}';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('{{ session('success') }}');
        });
    @endif

</script>
@endpush

@section('content')
<div class="section">
    <div class="pagetitle">
        <h2>Voucher</h2>
        <x-breadcrumbs :links="[
            ['url' => route('vouchers.index'), 'label' => 'Daftar Voucher'],
        ]"/>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <x-card title="Daftar Voucher" :button="['type' => 'button', 'class' => 'btn btn-sm btn-primary rounded-pill', 'dataBsToggle' => 'modal', 'dataBsTarget' => '#buatVC', 'label' => 'Tambah Voucher']">
                {{-- Modal Tambah --}}
                <x-modal id="buatVC" title="Buat Voucher">
                    <x-form action="{{route('vouchers.store')}}" method="POST">
                        <div class="col-md-12">
                            <x-input
                                type="text"
                                name="name"
                                label="Nama Voucher"
                                placeholder="loyaltyshopee"
                                value=""
                                required="true"
                            />
                        </div>
                        <div class="col-md-12">
                            <x-input
                                type="text"
                                name="slug"
                                label="Link"
                                placeholder="loyaltyshopee"
                                value=""
                                required="true"
                            />
                        </div>
                        <div class="col-md-12">
                            <x-input
                                type="number"
                                name="discount_amount"
                                label="Nominal Diskon"
                                placeholder="% atau Rp"
                                value=""
                                required="true"
                            />
                        </div>
                        <div class="col-md-12">
                            <x-input
                                type="textarea"
                                name="description"
                                label="Deskripsi"
                                placeholder="Deskripsi ditampilkan ke Customer"
                                value=""
                                required="true"
                            />
                        </div>
                        <div class="modal-footer">
                            <x-button type="button" label="Batal" class="btn-secondary" dataBsDismiss="modal" />
                            <x-button type="submit" label="Tambah" class="btn-primary"/>
                        </div>
                    </x-form>
                </x-modal>

                {{-- Tabel --}}
                <table class="table" id="VcTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Voucher</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $index => $voucher)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $voucher->slug }}</td>
                            <td>
                                {{-- Modal Ubah --}}
                            <x-button class="btn-sm btn-info rounded-pill" label="Ubah" dataBsToggle="modal" dataBsTarget="#ubahModal{{ $voucher->id }}"/>
                            <x-modal id="ubahModal{{ $voucher->id }}" title="{{ $voucher->slug }}">
                                <form action="{{route('vouchers.update', $voucher->slug)}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-md-12">
                                        <x-input
                                            type="text"
                                            name="name"
                                            label="Nama Voucher"
                                            :value="$voucher->name"
                                        />
                                    </div>
                                    <div class="col-md-12">
                                        <x-input
                                            type="text"
                                            name="slug"
                                            label="Link"
                                            :value="$voucher->slug"
                                        />
                                    </div>
                                    <div class="col-md-12">
                                        <x-input
                                            type="number"
                                            name="discount_amount"
                                            label="Nominal Diskon"
                                            placeholder="% atau Rp"
                                            :value="$voucher->discount_amount"
                                            required="true"
                                        />
                                    </div>
                                    <div class="col-md-12">
                                        <x-input
                                            type="textarea"
                                            name="description"
                                            label="Deskripsi"
                                            :value="$voucher->description"
                                        />
                                    </div>
                                    <div class="modal-footer">
                                        <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Batalkan"/>
                                        <x-button type="submit" class="btn-primary" label="Simpan"/>
                                    </div>
                                </form>
                            </x-modal>

                                {{-- Modal Hapus --}}
                                <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusCSModal{{ $voucher->id }}" label="Hapus" />
                                <x-modal id="hapusCSModal{{ $voucher->id }}" title="{{ $voucher->slug }}">
                                    <p>Yakin ingin menghapus Voucher ini?</p>
                                    <div class="modal-footer">
                                        <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                        <form action="{{ route('vouchers.delete', $voucher->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" class="btn btn-danger" label="Hapus"/>
                                        </form>
                                    </div>
                                </x-modal>

                                {{-- Download QR Code Button --}}
                                <a href="{{ route('vouchers.barcode', $voucher->slug) }}" class="btn btn-sm btn-success rounded-pill">
                                    <i class="bi bi-qr-code"></i> Download QRcode
                                </a>
                                {{-- Copy URL --}}
                                <a href="{{ route('vouchers.copy', $voucher->slug) }}" class="btn btn-sm btn-primary rounded-pill">
                                    Salin URL
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
</div>
@endsection
