@extends('layouts.guest')
@section('content')

<div class="container">
    <form action="{{ route('order.details.submit') }}" method="POST" class="">
        @csrf
    <div class="row g-5 mb-4">
        <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Data Pengiriman</h4>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="firstName" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Rizky Alexander" required>
              <div class="invalid-feedback">
                Nama harus diisi.
              </div>
            </div>

            <div class="col-md-6">
              <label for="username" class="form-label">Nomor WhatsApp</label>
              <div class="input-group has-validation">
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
              <div class="invalid-feedback">
                  Nomor WhatsApp harus diisi.
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="province" class="form-label">Provinsi</label>
              <select class="form-select select2" id="province" name="province" required>
                </select>
                <input type="hidden" id="province_name" name="province_name">
            </div>

            <div class="col-md-6">
              <label for="regency" class="form-label">Kabupaten/Kota</label>
                    <select class="form-select select2" id="regency" name="regency" required>
                    </select>
                <input type="hidden" id="regency_name" name="regency_name">
            </div>
            <div class="col-md-6">
                <label for="district" class="form-label">Kecamatan</label>
                <select class="form-select select2" id="district" name="district" required>
                </select>
                <input type="hidden" id="district_name" name="district_name">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">Desa/Kelurahan</label>
                <select class="form-control select2" id="village" name="village" required>
                </select>
                <input type="hidden" id="village_name" name="village_name">
            </div>
            <div class="col-12">
              <label for="address" class="form-label">Alamat</label>
              <textarea class="form-control" id="address" name="address"  value="{{ old('address') }}" required placeholder="Tambahkan RT/RW Atau Nama Jalan"></textarea>

            </div>
          </div>

        </div>

        <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Pembelianmu</span>
        </h4>
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">{{ $product->name }}</h6>
              <small class="text-body-secondary">{{$variation->name_variation}}, Rp {{ number_format($variation->price, 0, ',', '.') }}</small>
            </div>
            <span class="text-body-secondary">{{ $orderData['quantity'] }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total</span>
            <strong>Rp {{ number_format($orderData['total_price'], 0, ',', '.') }}</strong>
          </li>
        </ul>
        <div class="my-2 d-flex justify-content-end">
                <button type="submit" class="btn btn-warning">Checkout</button>
                <a href="{{ route('order.cancel') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        allowClear: true,
        width: 'resolve'
    });
    $.ajax({
        url: 'https://api.cahyadsn.com/provinces',
        type: 'GET',
        success: function(data) {
            $('#province').empty().append('<option value="">Pilih Provinsi</option>');
            $.each(data.data, function(key, value) {
                $('#province').append('<option value="'+ value.kode +'" data-name="'+ value.nama +'">'+ value.nama +'</option>');
            });
        }
    });

    $('#province').change(function() {
        var provinceId = $(this).val();
        var provinceName = $(this).find('option:selected').data('name');
        $('#province_name').val(provinceName);
        $.ajax({
            url: 'https://api.cahyadsn.com/regencies/'+ provinceId,
            type: 'GET',
            success: function(data) {
                $('#regency').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa</option>');
                $.each(data.data, function(key, value) {
                    $('#regency').append('<option value="'+ value.kode +'" data-name="'+ value.nama +'">'+ value.nama +'</option>');
                });
            }
        });
    });

    $('#regency').change(function() {
        var regencyId = $(this).val();
        var regencyName = $(this).find('option:selected').data('name');
        $('#regency_name').val(regencyName);
        $.ajax({
            url: 'https://api.cahyadsn.com/districts/' + regencyId,
            type: 'GET',
            success: function(data) {
                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa</option>');
                $.each(data.data, function(key, value) {
                    $('#district').append('<option value="'+ value.kode +'" data-name="'+ value.nama +'">'+ value.nama +'</option>');
                });
            }
        });
    });

    $('#district').change(function() {
        var districtId = $(this).val();
        var districtName = $(this).find('option:selected').data('name');
        $('#district_name').val(districtName);
        $.ajax({
            url: 'https://api.cahyadsn.com/villages/' + districtId,
            type: 'GET',
            success: function(data) {
                $('#village').empty().append('<option value="">Pilih Desa</option>');
                $.each(data.data, function(key, value) {
                    $('#village').append('<option value="'+ value.kode +'" data-name="'+ value.nama +'">'+ value.nama +'</option>');
                });
            }
        });
    });
    $('#village').change(function() {
        var villageName = $(this).find('option:selected').data('name');
        $('#village_name').val(villageName);
    });
});
</script>
@endpush
