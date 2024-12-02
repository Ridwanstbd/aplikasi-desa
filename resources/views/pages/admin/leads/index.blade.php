@extends('layouts.app')
@section('title', 'Daftar Leads')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Leads</h2>
        <x-anchor href="{{route('leads.syncs')}}" label="Sinkronkan Spreadsheet" class="btn btn-primary my-2"/>
        <x-anchor href="https://docs.google.com/spreadsheets/d/13D2Z4TzqAYL83lgxhazCuuQvgJ7aL1Mdv7JvVfagQ9Q/edit?usp=sharing" target="_blank" label="Lihat Spreadsheet" class="btn btn-success my-4"/>
    </div>
    <x-card title="Daftar Leads">
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
        <table class="table" id="leadTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nomor WhatsApp</th>
                    <th>Waktu Klik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leads as $index => $lead)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ $lead->time_order }}</td>
                    <td>
                        <!-- ubahCSModal -->
                        <x-button class="btn-sm btn-info rounded-pill" label="detail" dataBsToggle="modal" dataBsTarget="#ubahCSModal{{ $lead->id }}"/>
                        <x-modal id="ubahCSModal{{ $lead->id }}" title="{{ $lead->name }}">
                                <div class="col-md-12">
                                    <x-input type="text" label="Nama Leads" name="name" value="{{ $lead->name }}" readonly />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Nomor WhatsApp" name="phone" value="{{ $lead->phone }}" readonly />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="text" label="Waktu Klik" name="time_order" value="{{ $lead->time_order }}" readonly />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input type="text" label="Provinsi" name="province" value="{{ $lead->province }}" readonly />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input type="text" label="Kota/Kabupaten" name="regency" value="{{ $lead->regency }}" readonly />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input type="text" label="Kecamatan" name="province" value="{{ $lead->district }}" readonly />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input type="text" label="Kecamatan" name="regency" value="{{ $lead->village }}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <x-input type="textarea" label="Alamat Domisili" name="address" value="{{ $lead->address }}" readonly />
                                </div>
                                <div class="col-md-12">
                                    <x-input type="textarea" label="Detail Pesanan" name="detail_order" value="{{ $lead->detail_order }}" readonly />
                                </div>
                             </x-modal>
                             <!-- ubahCSModal -->
                            <!-- hapusCSModal -->
                            <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusCSModal{{ $lead->id }}" label="Hapus" />
                             <x-modal id="hapusCSModal{{ $lead->id }}" title="{{ $lead->name }}">
                             <p>Yakin ingin menghapus Leads ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline-block">
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
        let table = new DataTable('#leadTable');
    </script>
@endpush
