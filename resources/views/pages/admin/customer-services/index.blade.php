@extends('layouts.app')
@section('title', 'Daftar Customer Service')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Customer Service</h2>
    </div>
    <x-card title="Daftar CS" :button="['type' => 'button', 'class' => 'btn btn-sm btn-primary rounded-pill', 'dataBsToggle' => 'modal', 'dataBsTarget' => '#buatCS', 'label' => 'Tambah Customer Service']" >
        <x-modal id="buatCS" title="Buat Customer Service">
            <x-form action="{{route('customer-service.create')}}">
                <div class="col-md-12">
                    <x-input type="text" label="Nama Customer Service" name="name" required=true  placeholder="Diana" />
                </div>
                <div class="col-md-12">
                    <x-input type="text" label="Nomor WhatsApp" name="phone" placeholder="628123456789 (mulai dengan 62)" />
                </div>
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
        <table class="table" id="csTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No WhatsApp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerServices as $index => $cs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cs->name }}</td>
                    <td>{{ $cs->phone }}</td>
                    <td>
                        <!-- ubahCSModal -->
                        <x-button class="btn-sm btn-info rounded-pill" label="Ubah" dataBsToggle="modal" dataBsTarget="#ubahCSModal{{ $cs->id }}"/>
                        <x-modal id="ubahCSModal{{ $cs->id }}" title="{{ $cs->name }}">
                                <form action="{{ route('customer-service.update', $cs->id) }}" method="POST" >
                                    @csrf
                                    @method('PUT')
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Customer Service" name="name" value="{{ $cs->name }}" required=true />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Customer Service" name="phone" value="{{ $cs->name }}" required=true />
                                </div>                                            <div class="modal-footer">
                                    <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Batalkan"/>
                                    <x-button type="submit" class="btn-primary" label="Simpan"/>
                                </div>
                                </form>
                             </x-modal>
                             <!-- ubahCSModal -->
                            <!-- hapusCSModal -->
                            <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusCSModal{{ $cs->id }}" label="Hapus" />
                             <x-modal id="hapusCSModal{{ $cs->id }}" title="{{ $cs->name }}">
                             <p>Yakin ingin menghapus CS ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('customer-service.destroy', $cs->id) }}" method="POST" class="inline-block">
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
        let table = new DataTable('#csTable');
    </script>
@endpush
