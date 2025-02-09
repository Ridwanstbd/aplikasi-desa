<nav class="navbar navbar-expand-md navbar-light shadow-sm fixed-top" style="background-color: #2a401a; ">
    <div class="container">
        <div class="d-flex justify-content-start w-100 align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('home')}}" class="text-decoration-none" alt="Home">
                    <img src="{{ Storage::url($shop->logo_url) }}" alt="Eleanor Farm Shop" style="width: 3rem; height: 3rem; margin-right:1rem;">
                </a>
                <!-- Input Pencarian dengan Ikon Kaca Pembesar -->
                <form action="{{ url('/#product-grid') }}" method="GET" class="d-flex ms-auto">
                    {{-- Preserve existing filter if any --}}
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <div class="input-group">
                        <input type="search"
                               name="search"
                               id="searchInput"
                               class="form-control"
                               placeholder="Cari produk..."
                               value="{{ request('search') }}"
                               aria-label="Cari produk"
                               required>
                        <button class="btn btn-outline-primary" type="submit" aria-label="Tekan untuk mencari">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <span class="visually-hidden">Cari</span>
                        </button>
                    </div>
                </form>

            </div>
            <div class="d-flex gap-3 align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon text-white"></span>
                </button>
                <!-- Keranjang -->
                <div class="nav-item d-md-none">
                    <a class="nav-link d-flex align-items-center text-white" href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <div class="fs-2">
                        <i class="bi bi-cart-fill text-white"></i>
                    </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <!-- Authentication Links -->
                @if (!empty($shop->first()->added_url))
                 <li class="nav-item align-items-center px-2">
                     <a class="nav-link align-items-center text-white" href="{{$shop->first()->added_url}}" target="_blank">
                        <button class="btn btn-primary">
                            <span style="white-space: nowrap;" class="text-white">HEWAN QURBAN</span>
                        </button>
                     </a>
                 </li>
                 @endif
                 @if (!empty($shop->first()->location_url))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center text-white" href="{{$shop->first()->location_url}}" target="_blank">
                         <img src="{{asset('assets/icons/maps.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;">
                         MAPS
                     </a>
                 </li>
                 @endif
                 @if (!empty($cs->first()->phone))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center text-white" href="https://wa.me/{{$cs->first()->phone}}" target="_blank">
                         <img src="{{asset('assets/icons/whatsapp.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;">
                         WA
                     </a>
                 </li>
                 @endif
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center text-white" href="{{route('blog.index')}}" target="_blank">
                         Artikel
                     </a>
                 </li>
                 <!-- Blog -->
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Keranjang -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link d-flex align-items-center text-white" href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <div class="fs-2">
                        <i class="bi bi-cart-fill text-white"></i>
                    </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
