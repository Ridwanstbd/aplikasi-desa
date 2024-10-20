<div class="container mt-3">
    <span class="fs-5">Kategori</span>
    <div class="custom-radio-group">
        <input type="radio" id="semua_produk" name="kategori" value="" {{ request('kategori') == '' ? 'checked' : '' }}>
        <label for="semua_produk">SEMUA</label>
        @foreach ($categories as $category)
            <input type="radio" id="{{ $category->id }}" name="kategori" value="{{ $category->id }}"
                   {{ request('kategori') == $category->id ? 'checked' : '' }}>
            <label for="{{ $category->id }}">{{ strtoupper($category->name) }}</label>
        @endforeach
    </div>
</div>
