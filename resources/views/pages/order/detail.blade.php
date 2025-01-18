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
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinces as $code => $name)
                        <option value="{{ $code }}" data-name="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
                <input type="hidden" id="province_name" name="province_name">
            </div>

            <div class="col-md-6">
              <label for="regency" class="form-label">Kabupaten/Kota</label>
              <select class="form-select select2" id="regency" name="regency" required>
                  <option value="">Pilih Kabupaten/Kota</option>
                  @foreach($regencies as $code => $name)
                      <option value="{{ $code }}" data-name="{{ $name }}" {{ $selectedRegencyCode == $code ? 'selected' : '' }}>
                          {{ $name }}
                      </option>
                  @endforeach
              </select>
              <input type="hidden" id="regency_name" name="regency_name">
          </div>
          <div class="col-md-6">
            <label for="district" class="form-label">Kecamatan</label>
            <select class="form-select select2" id="district" name="district" required>
                <option value="">Pilih Kecamatan</option>
                @foreach($districts as $code => $name)
                    <option value="{{ $code }}" data-name="{{ $name }}" {{ $selectedDistrictCode == $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="district_name" name="district_name">
        </div>
        <div class="col-md-6">
          <label for="village" class="form-label">Desa/Kelurahan</label>
          <select class="form-select select2" id="village" name="village" required>
              <option value="">Pilih Desa/Kelurahan</option>
              @foreach($villages as $code => $name)
                  <option value="{{ $code }}" data-name="{{ $name }}">{{ $name }}</option>
              @endforeach
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
        <small class="text-body-secondary">*Total harga belum termasuk ongkos kirim</small>
        <div class="my-2 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                    Checkout via WhatsApp
                    <svg class="ms-2" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                </button>
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

    function updateRegion(type, code) {
        $.ajax({
            url: '{{ route("update.region") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: type,
                code: code
            },
            success: function(data) {
                let target, placeholder;
                
                switch(type) {
                    case 'province':
                        target = '#regency';
                        placeholder = 'Pilih Kabupaten/Kota';
                        // Reset dependent selects
                        $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                        $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                        break;
                    case 'regency':
                        target = '#district';
                        placeholder = 'Pilih Kecamatan';
                        // Reset dependent select
                        $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                        break;
                    case 'district':
                        target = '#village';
                        placeholder = 'Pilih Desa/Kelurahan';
                        break;
                }
                
                $(target).empty().append(`<option value="">${placeholder}</option>`);
                $.each(data, function(code, name) {
                    $(target).append(`<option value="${code}" data-name="${name}">${name}</option>`);
                });
                
                // Reinitialize select2
                $(target).trigger('change');
            }
        });
    }

    $('#province').change(function() {
        var code = $(this).val();
        var name = $(this).find('option:selected').data('name');
        $('#province_name').val(name);
        if(code) {
            updateRegion('province', code);
        }
    });

    $('#regency').change(function() {
        var code = $(this).val();
        var name = $(this).find('option:selected').data('name');
        $('#regency_name').val(name);
        if(code) {
            updateRegion('regency', code);
        }
    });

    $('#district').change(function() {
        var code = $(this).val();
        var name = $(this).find('option:selected').data('name');
        $('#district_name').val(name);
        if(code) {
            updateRegion('district', code);
        }
    });

    $('#village').change(function() {
        var name = $(this).find('option:selected').data('name');
        $('#village_name').val(name);
    });
});
</script>
@endpush
