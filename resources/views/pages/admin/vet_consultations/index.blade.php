@extends('layouts.app')

@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Data Konsultasi Dokter</h2>
    </div><!-- End Page Title -->
    <x-card title="Daftar Kategori" >

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
        <table class="table" id="consultTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Nomor WhatsApp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultations as $index => $consult)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $consult->full_name }}</td>
                    <td>{{ $consult->phone_number }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Ubah Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-info rounded-pill" label="Lihat Data" dataBsToggle="modal" dataBsTarget="#lihatModal{{ $consult->id }}"/>
                             <x-modal id="lihatModal{{ $consult->id }}" title="Detail Pesan Konsultasi">
                                    <x-input type="text" name="full_name" label="Nama Lengkap" value="{{$consult->full_name}}" required="true" />
                                    <x-input type="text" name="address" label="Alamat" value="{{$consult->address}}" required="true" />
                                    <x-input type="text" name="phone_number" label="Nomor WhatsApp" value="{{$consult->phone_number}}" required="true" />
                                    <x-input type="text" name="consultation_date" label="Tanggal" value="{{$consult->consultation_date}}" />
                                    <x-input type="textarea" name="notes" label="Detail Sakit Hewan" value="{{$consult->notes}}" />
                            </x-modal><!-- End Ubah Kategori Modal-->
                            <!-- Hapus Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusModal{{ $consult->id }}" label="Hapus" />
                             <x-modal id="hapusModal{{ $consult->id }}" title="{{ $consult->full_name }}">
                             <p>Yakin ingin menghapus Konsultasi ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('vet-consult.destroy', $consult->id) }}" method="POST" class="inline-block">
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
        let table = new DataTable('#consultTable');
    </script>
@endpush
