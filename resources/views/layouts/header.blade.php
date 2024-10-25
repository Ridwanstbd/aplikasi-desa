<nav class="navbar navbar-expand-md navbar-light shadow-sm fixed-top" style="background-color: #2a401a;">
    <div class="container">
        <!-- Logo -->
        <a href="{{ route('home')}}" class="navbar-brand">
            <img src="{{ Storage::url($shop->logo_url) }}" alt="" style="width: 3rem; height: 3rem;">
        </a>

        <!-- Search Form -->
        <form action="{{ url('/#product-grid') }}" method="GET" class="d-flex flex-grow-1 mx-3">
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

        <!-- Mobile Cart and Toggler -->
        <div class="d-flex align-items-center">
            <!-- Mobile Cart -->
            <div class="d-md-none me-2">
                <a class="nav-link text-white" href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <i class="bi bi-cart-fill fs-4"></i>
                </a>
            </div>

            <!-- Toggler Button -->
            <button class="navbar-toggler border-white"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                @if (!empty($shop->first()->added_url))
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{$shop->first()->added_url}}" target="_blank">
                        <button class="btn btn-primary">
                            <span class="text-white">HEWAN QURBAN</span>
                        </button>
                    </a>
                </li>
                @endif

                @if (!empty($shop->first()->location_url))
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{$shop->first()->location_url}}" target="_blank">
                        <img src="{{asset('assets/icons/maps.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        MAPS
                    </a>
                </li>
                @endif

                @if (!empty($cs->first()->phone))
                <li class="nav-item">
                    <a class="nav-link text-white" href="https://wa.me/{{$cs->first()->phone}}" target="_blank">
                        <img src="{{asset('assets/icons/whatsapp.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        WA
                    </a>
                </li>
                @endif

                @if (!empty($mp))
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{$mp->marketplace_url}}" target="_blank">
                        @if ($mp->type === 'Shopee')
                            <img src="{{asset('assets/icons/shopee.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        @elseif ($mp->type === 'Tokopedia')
                            <img src="{{asset('assets/icons/tokopedia.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        @elseif ($mp->type === 'Tiktok')
                            <img src="{{asset('assets/icons/tiktok.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        @elseif ($mp->type === 'Lazada')
                            <img src="{{asset('assets/icons/lazada.png')}}" alt="" style="width: 2.5rem; height: 2.5rem;" class="me-2">
                        @endif
                        {{$mp->name}}
                    </a>
                </li>
                @endif
            </ul>

            <!-- Desktop Cart -->
            <div class="d-none d-md-block">
                <a class="nav-link text-white" href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <i class="bi bi-cart-fill fs-4"></i>
                </a>
            </div>
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
