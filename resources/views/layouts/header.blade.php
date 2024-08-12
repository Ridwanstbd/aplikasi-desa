<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        @guest
        <div class="d-flex justify-content-between w-100 align-content-center">
            <div class="">
                <a href="{{ route('home')}}" class="text-decoration-none">
                    <img src="{{ Storage::url($shop->logo_url) }}" alt="" style="width: 3rem; height: 3rem; margin-right:1rem;">
                </a>
            </div>
            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Keranjang -->
                <div class="nav-item ms-2 d-md-none">
                    <a class="nav-link d-flex align-items-center " href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <div class="fs-2">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                        <!-- Jika ingin menampilkan jumlah item dalam keranjang -->
                    </a>
                </div>
            </div>
        </div>
        @endguest

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <!-- Tambahkan item navbar di sini jika diperlukan -->
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                 @if (!empty($shop->first()->added_url))
                 <li class="nav-item align-items-center px-2">
                     <a class="nav-link align-items-center" href="{{$shop->first()->added_url}}" target="_blank">
                        <button class="btn btn-primary" >
                            <span style="white-space: nowrap;">HEWAN QURBAN</span>
                        </button>
                     </a>
                 </li>
                 @endif
                 @if (!empty($shop->first()->location_url))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center" href="{{$shop->first()->location_url}}" target="_blank">
                         <img src="{{asset('assets/icons/maps.png')}}" alt="" srcset=""
                         style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;">
                         MAPS
                     </a>
                 </li>
                 @endif
                 @if (!empty($cs->first()->phone))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center" href="https://wa.me/{{$cs->first()->phone}}" target="_blank">
                         <img src="{{asset('assets/icons/whatsapp.png')}}" alt="" srcset=""
                         style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;">
                         WA
                     </a>
                 </li>
                 @endif
                 @if (!empty($mp))
                 <li class="nav-item d-flex align-items-center px-2">
                     <a class="nav-link d-flex align-items-center" href="{{$mp->marketplace_url}}" target="_blank">
                        @if ($mp->type === 'Shopee')
                        <img src="{{asset('assets/icons/shopee.png')}}" alt="" srcset="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Tokopedia')
                        <img src="{{asset('assets/icons/tokopedia.png')}}" alt="" srcset="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Tiktok')
                        <img src="{{asset('assets/icons/tiktok.png')}}" alt="" srcset="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                        @if ($mp->type === 'Lazada')
                        <img src="{{asset('assets/icons/lazada.png')}}" alt="" srcset="" style="width: 2.5rem;height:2.5rem; margin-right: 0.5rem;" />
                        @endif
                         {{$mp->name}}
                     </a>
                 </li>
                 @endif
                <!-- Keranjang -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link d-flex align-items-center" href="{{ route('cart.index') }}" aria-label="Keranjang">
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalQuantity }}</span>
                    @endif
                    <div class="fs-2">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                        <!-- Jika ingin menampilkan jumlah item dalam keranjang -->
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
