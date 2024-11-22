<footer class="navbar navbar-dark bg-dark shadow-sm mt-2" style="width: 100%; margin: 0; padding: 0;">
    <div class="container py-4">
        <div class="row w-100 d-flex align-items-center mb-3">
            <div class="d-flex align-items-center gap-3">
                @if (!empty($shop->first()->logo_url))
                    <img src="{{ Storage::url($shop->logo_url) }}" alt="Logo" style="width: 8rem; height: 5rem;">
                @endif
                <span class="fs-5 text-white fw-bold">{{$shop->name}}</span>
            </div>
        </div>
        <div class="row w-100 mt-3">
            <div class="col-md-4 align-items-center">
                <div class="d-flex gap-3 mt-2">
                    <a href="https://www.instagram.com/eleanorfarm.id" target="_blank" class="text-white">
                        <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.tiktok.com/@eleanorfarm.id" target="_blank" class="text-white">
                        <i class="bi bi-tiktok" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.facebook.com/eleanorfarmshop" target="_blank" class="text-white">
                        <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.youtube.com/@eleanorfarmofficial" target="_blank" class="text-white">
                        <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                @if (!empty($shop->first()->location_url))
                <h6 class="fw-bold text-white">Lokasi</h6>
                    <a class="nav-link d-flex text-decoration-none text-white align-items-center px-2 mb-2" href="{{$shop->first()->location_url}}" target="_blank">
                        <img src="{{asset('assets/icons/maps.png')}}" alt="maps" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        Google Maps
                    </a>
                @endif
                @if (!empty($shop->first()->added_url))
                     <a class="nav-link d-flex align-items-center" href="{{$shop->first()->added_url}}" target="_blank">
                        <button class="btn btn-primary">
                            HEWAN QURBAN
                        </button>
                     </a>
                @endif
            </div>
            <div class="col-md-4 mt-3">
                <p class="fw-bold text-white">Artikel Peternakan</p>
                <h6 class="fw-bold mt-3 text-white">Kontak</h6>
                @if (!empty($cs->first()->phone))
                    <a class="nav-link text-decoration-none text-white d-flex align-items-center px-2" href="https://wa.me/{{$cs->first()->phone}}" target="_blank">
                        <img src="{{asset('assets/icons/whatsapp.png')}}" alt="WhatsApp" style="width: 1.2rem; height: 1.2rem; margin-right: 0.5rem;">
                        WhatsApp
                    </a>
                @endif
            </div>
        </div>
        <div class="row w-100">
            <p class="text-center text-white pt-4" style="font-size: smaller;">
                Copyright &copy 2024 PT ELEANOR PROJECT GLOBAL INDONESIA All Rights Reserved.
            </p>
        </div>
    </div>
</footer>
