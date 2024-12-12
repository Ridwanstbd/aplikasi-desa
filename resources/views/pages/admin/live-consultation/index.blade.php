@extends('layouts.app')

@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Data Live</h2>
    </div><!-- End Page Title -->
    <x-card title="Daftar Data Konsultasi Live" >

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
                    <th>Kirim Pesan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($liveConsults as $index => $consult)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $consult->user_name }}</td>
                    <td>{{ $consult->user_whatsapp }}</td>
                    <td>
                        <a href="https://wa.me/{{$consult->user_whatsapp}}" target="_blank" alt="" class="text-black">
                            <i class="bi bi-whatsapp" style="font-size: 1.5rem"></i>
                        </a>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Ubah Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-info rounded-pill" label="Lihat Data" dataBsToggle="modal" dataBsTarget="#lihatModal{{ $consult->id }}"/>
                             <x-modal id="lihatModal{{ $consult->id }}" title="Detail Pesan Konsultasi">
                                    <x-input type="text" name="user_name" label="Nama Lengkap" value="{{$consult->user_name}}" required="true" />
                                    <x-input type="text" name="address" label="Alamat" value="{{$consult->address}}" required="true" />
                                    <x-input type="text" name="user_whatsapp" label="Nomor WhatsApp" value="{{$consult->user_whatsapp}}" required="true" />
                                    <x-input type="text" name="name_kandang" label="Nama Kandang" value="{{$consult->name_kandang}}" />
                                    <x-input type="text" name="jenis_hewan" label="Jenis Hewan" value="{{$consult->jenis_hewan}}" />
                                    <x-input type="textarea" name="data_pembelian" label="Data Pembelian" value="{{$consult->data_pembelian}}" />
                            </x-modal><!-- End Ubah Kategori Modal-->
                            <!-- Hapus Kategori Modal -->
                             <x-button type="button" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal" dataBsTarget="#hapusModal{{ $consult->id }}" label="Hapus" />
                             <x-modal id="hapusModal{{ $consult->id }}" title="{{ $consult->user_name }}">
                             <p>Yakin ingin menghapus Konsultasi ini?</p>
                             <div class="modal-footer">
                                <x-button type="button" class="btn-secondary" dataBsDismiss="modal" label="Tidak, Batalkan" />
                                <form action="{{ route('admin.live-konsul.destroy', $consult->id) }}" method="POST" class="inline-block">
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
