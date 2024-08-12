@props(['type','url', 'name'])
@if ($type === 'Shopee')
<a class="btn btn-warning d-flex align-items-center" href="{{$url}}" target="_blank">
    <img src="{{asset('assets/icons/shopee.png')}}" alt="" srcset=""
        style="width: 1.2rem;height:1.2rem; margin-right: 0.5rem;">
    {{$name}}
</a>
@endif
@if ($type === 'Tokopedia')
<a class="btn btn-success d-flex align-items-center" href="{{$url}}" target="_blank">
    <img src="{{asset('assets/icons/tokopedia.png')}}" alt="" srcset=""
        style="width: 1.2rem;height:1.2rem; margin-right: 0.5rem;">
    {{$name}}
</a>
@endif
@if ($type === 'Tiktok')
<a class="btn btn-dark d-flex align-items-center" href="{{$url}}" target="_blank">
    <img src="{{asset('assets/icons/tiktok.png')}}" alt="" srcset=""
        style="width: 1.2rem;height:1.2rem; margin-right: 0.5rem;">
    {{$name}}
</a>
@endif
@if ($type === 'Lazada')
<a class="btn btn-primary d-flex align-items-center" href="{{$url}}" target="_blank">
    <img src="{{asset('assets/icons/lazada.png')}}" alt="" srcset=""
        style="width: 1.2rem;height:1.2rem; margin-right: 0.5rem;">
    {{$name}}
</a>
@endif
