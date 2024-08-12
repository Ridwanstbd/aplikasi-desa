@if (isset($links) && count($links) > 0)
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($links as $link )
            @if (!$loop->last)
            <li class="breadcrumb-item">
                <x-anchor :href="$link['url']" :label="$link['label']"/>
            </li>
            @else
            <li class="breadcrumb-item active" aria-current="page">
                {{ $link['label'] }}
            </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif

