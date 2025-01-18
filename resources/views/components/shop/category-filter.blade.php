<div class="container mt-3">
    <span class="category-title fs-5">Kategori</span>
    <div class="custom-radio-group"  role="radiogroup" aria-label="Filter kategori produk">
        <input type="radio" id="semua_produk" name="kategori" value="" {{ request('kategori') == '' ? 'checked' : '' }} aria-label="Tampilkan semua produk">
        <label for="semua_produk">SEMUA</label>
        @foreach ($categories as $category)
            <input type="radio" 
            id="{{ $category->id }}" 
            name="kategori" 
            value="{{ $category->id }}"
            {{ request('kategori') == $category->id ? 'checked' : '' }}
            aria-label="Filter kategori {{ strtoupper($category->name) }}"
            >
            <label for="{{ $category->id }}">{{ strtoupper($category->name) }}</label>
        @endforeach
    </div>
</div>
