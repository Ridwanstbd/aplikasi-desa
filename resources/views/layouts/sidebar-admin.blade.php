<sidebar class="d-flex flex-column flex-shrink-0 ">
    <div class="d-flex container justify-content-between">
        <a href="{{route('products.index')}}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <img src="{{Storage::url($shop->logo_url)}}" alt="" style="width:1.8rem;height:1.8rem;">
            <span class="fs-4 px-2">{{$shop->name}}</span>
        </a>
        <button class="navbar-toggler d-lg-none d-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list navbar-toggler-icon" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
        </button>
    </div>
    <div class="collapse navbar-collapse min-vh-100" id="navbarSupportedContent">
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                {{ __('Edit Profile') }}
                </a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('products.index') ? 'active text-white' : '' }}" aria-current="page">
                    Produk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('marketplace-links.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('marketplace-links.index') ? 'active text-white' : '' }}" aria-current="page">
                    Marketplace
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('categories.index') ? 'active text-white' : '' }}">
                    Kategori
                </a>
            </li>
            <li>
                <a href="{{route('customer-service.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('customer-service.index') ? 'active text-white' : '' }}">
                    CS Rotator
                </a>
            </li>
            <li>
                <a href="{{route('leads.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('leads.index') ? 'active text-white' : '' }}">
                    Leads
                </a>
            </li>
            <li>
                <a href="{{route('vouchers.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('vouchers.index') ? 'active text-white' : '' }}">
                    Voucher
                </a>
            </li>
            <li>
                <a href="{{route('settings.index')}}" class="nav-link link-body-emphasis {{ Request::routeIs('settings.index') ? 'active text-white' : '' }}">
                    Pengaturan Toko
                </a>
            </li>

        </ul>
    </div>

</sidebar>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarSupportedContent = document.getElementById('navbarSupportedContent');
        function checkScreenWidth() {
            if (window.innerWidth >= 768) {
                navbarSupportedContent.classList.add('show');
            }
        }
        checkScreenWidth();
        window.addEventListener('resize', checkScreenWidth);
    });
</script>
@endpush
