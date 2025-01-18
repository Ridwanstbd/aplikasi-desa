@if (!empty($shop->first()->description))
    <div class="p-3 p-md-8 m-md-3 text-center">
        <h2 class="fs-2">Selamat Datang di <span class="d-block d-md-inline">{{$shop->first()->name}}</span></h2>
        <p class="fs-4">{{$shop->first()->description}}</p>
    </div>
@endif
