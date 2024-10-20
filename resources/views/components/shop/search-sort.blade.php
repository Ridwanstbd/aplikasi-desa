<div class="d-flex gap-2 mb-3">
    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
    <select name="sort" class="form-control" onchange="document.getElementById('filterForm').submit();">
        <option value="" disabled selected>Sortir berdasarkan</option>
        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
        <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
        <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
    </select>
</div>
