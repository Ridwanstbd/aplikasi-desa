<nav class="navbar navbar-expand-md navbar-light shadow-sm fixed-top" style="background-color: #2a401a; ">
    <div class="container">
        <div class="d-flex justify-content-start w-100 align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('home')}}" class="text-decoration-none">
                    <img src="{{ Storage::url($shop->logo_url) }}" alt="" style="width: 3rem; height: 3rem; margin-right:1rem;">
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
                               class="form-control"
                               placeholder="Cari produk..."
                               value="{{ request('search') }}"
                               required>
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
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
                 @if (!empty($mp))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center text-white" href="{{$mp->marketplace_url}}" target="_blank">
                        @if ($mp->type === 'Shopee')
                        <img src="{{asset('assets/icons/shopee.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Tokopedia')
                        <img src="{{asset('assets/icons/tokopedia.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Tiktok')
                        <img src="{{asset('assets/icons/tiktok.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Lazada')
                        <img src="{{asset('assets/icons/lazada.png')}}" alt="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        {{$mp->name}}
                     </a>
                 </li>
                 @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get all the links inside the navbar
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');

    // Add click event to all links
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // If navbar is expanded (shown) and window is in mobile view
            if (window.innerWidth < 768 && navbarCollapse.classList.contains('show')) {
                navbarToggler.click(); // Programmatically click the toggler to close the navbar
            }
        });
    });
});
</script>
