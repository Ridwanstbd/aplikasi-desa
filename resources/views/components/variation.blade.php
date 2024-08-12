@props(['variations', 'disabled' == false,'style'])

<div id="variations" style="{{$style}}">
    @php
    $isHasVariation = true;
    @endphp
    <x-checkbox name="has_variations" label="Produk ini memiliki variasi" :checked="$isHasVariation" checked/>
    <h5 class="m-4">Variasi Produk</h5>

    @foreach ($variations as $index => $variation)
    <div class="variation d-grid">
        <div class="row">
            <div class="col col-md-6">
                <x-input type="text" name="variations[{{ $index }}][name_variation]" label="Nama Variasi" placeholder="Merah, XL" :value="$variation['name_variation'] ?? ''"/>
            </div>
            <div class="col col-md-6">
                <x-input type="number" name="variations[{{ $index }}][price]" label="Harga" placeholder="7999" :value="$variation['price'] ?? ''"/>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col col-md-6">
                <x-input type="text" name="variations[{{ $index }}][sku]" label="SKU" placeholder="A0001" :value="$variation['sku'] ?? ''"/>
            </div>
            <div class="col col-md-6">
                @if (!empty($variation['image']))
                    <x-image-preview :image="$variation['image']"/>
                @endif
                <input type="hidden" name="variations[{{ $index }}][existing_image]" value="{{ $variation['image'] ?? '' }}">
                <input type="hidden" name="variations[{{ $index }}][index]" value="{{ $index }}">
                <x-input-image name="variations[{{ $index }}][image]" label="Foto Variasi"/>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col col-md-6">
                <input type="hidden" name="variations[{{ $index }}][is_ready]" value="{{ $variation['is_ready'] }}">
                <x-checkbox label="Bisa dipesan" name="variations[{{ $index }}][is_ready]" :checked="$variation['is_ready']" />
            </div>
        </div>
        <div class="text-start">
            <x-button type="button" label="Hapus" class="btn-danger my-2 remove_variation" onclick="removeVariation(this)"/>
        </div>
    </div>
    @endforeach
</div>
