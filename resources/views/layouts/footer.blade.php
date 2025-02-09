<footer class="navbar navbar-dark bg-dark shadow-sm mt-2" style="width: 100%; margin: 0; padding: 0;">
    <div class="container py-4">
        <div class="row w-100 d-flex align-items-center mb-3">
            <div class="d-flex align-items-center gap-3">
                @if (!empty($shop->first()->logo_url))
                    <img src="{{ Storage::url($shop->logo_url) }}" alt="Logo" style="max-width: 5rem; max-height: 5rem; height: auto;">
                @endif
                <span class="fs-5 text-white fw-bold">{{$shop->name}}</span>
            </div>
        </div>
        <div class="row w-100 mt-3">
            <div class="col-md-4 align-items-center">
                <div class="d-flex gap-3 mt-2">
                    <a href="https://www.instagram.com/eleanorfarm.id" target="_blank" alt="Instagram Account" class="text-white">
                        <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.tiktok.com/@eleanorfarm.id" target="_blank" alt="Tiktok Account" class="text-white">
                        <i class="bi bi-tiktok" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.facebook.com/eleanorfarmshop" target="_blank" alt="Facebook Account" class="text-white">
                        <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://www.youtube.com/@eleanorfarmofficial" target="_blank" alt="Youtube Account" class="text-white">
                        <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                    </a>
                    @if($cs->isNotEmpty() && $cs->first()->phone)
                        <a href="https://wa.me/{{$cs->first()->phone}}" target="_blank" alt="" class="text-white">
                            <i class="bi bi-whatsapp" style="font-size: 1.5rem"></i>
                        </a>
                    @else
                        <span class="text-white">
                            <i class="bi bi-whatsapp" style="font-size: 1.5rem;" class="text-white"></i>
                        </span>
                    @endif
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
                <h6 class="fw-bold text-white">Perusahaan</h6>
                <a href="{{route('blog.index')}}" target="_blank" class=" text-white text-decoration-none">Artikel Peternakan</a>
            </div>
        </div>
        <div class="row w-100">
            <p class="text-center text-white pt-4" style="font-size: smaller;">
                Copyright &copy 2024 PT ELEANOR PROJECT GLOBAL INDONESIA All Rights Reserved.
            </p>
        </div>
    </div>
</footer>
