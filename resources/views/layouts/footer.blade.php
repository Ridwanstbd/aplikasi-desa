<footer class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm mt-5 pt-5">
    <div class="container">
        <div class="row w-100">
            <div class="col-md-4 align-items-center">
            @if (!empty($shop->first()->logo_url))
            <img src="{{ Storage::url($shop->logo_url) }}" alt="Logo" style="width: 3rem; height: 3rem; margin-right:1rem;">
            @endif
            <span class="fs-5 text-white fw-bold">{{$shop->name}}</span>

            <div class="d-flex gap-3 mt-2">
                <a href="/" target="_blank" class="text-white">
                    <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                </a>
                <a href="/" target="_blank" class="text-white">
                    <i class="bi bi-tiktok" style="font-size: 1.5rem;"></i>
                </a>
                <a href="/" target="_blank" class="text-white">
                    <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                </a>
                <a href="/" target="_blank" class="text-white">
                    <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                </a>
            </div>
            </div>
            <div class="col-md-4">
                @if (!empty($shop->first()->location_url))
                <h6 class="fw-bold text-white">Lokasi</h6>
                    <a class="nav-link d-flex text-decoration-none text-white align-items-center px-2 mb-2" href="{{$shop->first()->location_url}}" target="_blank">
                        <img src="{{asset('assets/icons/maps.png')}}" alt="maps" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;"">
                        Google Maps
                    </a>
                @endif
                @if (!empty($shop->first()->added_url))
                     <a class="nav-link d-flex align-items-center" href="{{$shop->first()->added_url}}" target="_blank">
                        <button class="btn btn-primary" >
                            HEWAN QURBAN
                        </button>
                     </a>
                 @endif
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-white">Marketplace</h6>
                @if (!empty($mp))
                    <a class="nav-link text-decoration-none text-white d-flex align-items-center px-2" href="{{$mp->marketplace_url}}" target="_blank">
                        @if ($mp->type === 'Shopee')
                            <img src="{{asset('assets/icons/shopee.png')}}" alt="Shopee" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        @endif
                        @if ($mp->type === 'Tokopedia')
                            <img src="{{asset('assets/icons/tokopedia.png')}}" alt="Tokopedia" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        @endif
                        @if ($mp->type === 'Tiktok')
                            <img src="{{asset('assets/icons/tiktok.png')}}" alt="Tiktok" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        @endif
                        @if ($mp->type === 'Lazada')
                            <img src="{{asset('assets/icons/lazada.png')}}" alt="Lazada" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        @endif
                        {{$mp->name}}
                    </a>
                @endif
                <h6 class="fw-bold mt-3 text-white">Kontak</h6>
                @if (!empty($cs->first()->phone))
                    <a class="nav-link text-decoration-none text-white d-flex align-items-center px-2" href="https://wa.me/{{$cs->first()->phone}}" target="_blank">
                        <img src="{{asset('assets/icons/whatsapp.png')}}" alt="WhatsApp" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        WhatsApp
                    </a>
                @endif
            </div>
            <p class="text-center text-white pt-4" style="font-size: smaller;">Copyright &copy 2024 PT ELEANOR PROJECT GLOBAL INDONESIA All Rights Reserved.</p>
        </div>
    </div>
</footer>

