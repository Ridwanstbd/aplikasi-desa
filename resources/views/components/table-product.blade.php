@props(['products'])
<table class="table" id="productTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Variasi</th>
            <th>SKU</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @foreach($product->variations as $variation)
                        {{ $variation->name_variation }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($product->variations as $variation)
                        {{ $variation->sku }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($product->variations as $variation)
                        Rp. {{ number_format($variation->price, 0, ',', '.') }}<br>
                    @endforeach
                </td>
                <td>
                    <x-anchor class="btn btn-sm btn-info rounded-pill" label="Ubah" href="{{ route('products.edit', $product->slug) }}" />
                    <x-button type="button" label="Hapus" class="btn-sm btn-warning rounded-pill" dataBsToggle="modal"
                        dataBsTarget="#hapusProdukModal{{$product->id}}" />
                    <x-modal id="hapusProdukModal{{$product->id}}" title="Hapus Produk">
                        <p>Yakin, ingin menghapus Produk ini?</p>
                        <div class="modal-footer">
                        <x-button type="button" class="btn-secondary" label="Tidak,Batalkan" dataBsDismiss="modal"/>
                        <form action="{{ route('products.destroy', $product->slug) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yakin</button>
                        </form>
                        </div>
                    </x-modal>
                    <!-- End Hapus Kategori Modal-->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
